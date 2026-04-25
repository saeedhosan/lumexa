<?php

declare(strict_types=1);

use App\Models\Company;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $companyId = null;

    protected $queryString = ['search', 'companyId'];

    public function mount(?int $companyId = null): void
    {
        $this->companyId = $this->validateCompanyId($companyId);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCompanyId(): void
    {
        $this->companyId = $this->validateCompanyId($this->companyId);
        $this->resetPage();
    }

    public function delete(User $user): void
    {
        if ($user->id === Auth::id()) {
            Flux::toast(__('You cannot delete your own account.'), variant: 'warning');

            return;
        }

        $this->authorize('delete', $user);

        $user->delete();

        Flux::toast(__('User deleted successfully.'), variant: 'success');
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->with('companies')
            ->when($this->search, fn ($query) => $query->where(fn ($q) => $q->where('name', 'like', '%'.$this->search.'%')->orWhere('email', 'like', '%'.$this->search.'%')))
            ->when($this->companyId, fn ($query) => $query->whereHas('companies', fn ($q) => $q->where('companies.id', $this->companyId)))
            ->when(! Auth::user()->isSuper(), fn ($query) => $query->whereHas('companies', fn ($q) => $q->whereIn('companies.id', Auth::user()->companies()->pluck('companies.id'))))
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    #[Computed]
    public function companies()
    {
        $user = Auth::user();

        if ($user->isSuper()) {
            return Company::query()->orderBy('name')->get();
        }

        return $user->companies()->orderBy('name')->get();
    }

    private function validateCompanyId(?int $companyId): ?int
    {
        if (! $companyId) {
            return null;
        }

        $user = Auth::user();

        if ($user->isSuper()) {
            return $companyId;
        }

        return $user->companies()->where('companies.id', $companyId)->exists()
            ? $companyId
            : null;
    }
};
