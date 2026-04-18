<?php

declare(strict_types=1);

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

    protected $queryString = ['search'];

    public function updatedSearch(): void
    {
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
            ->when($this->search, fn ($query) => $query->where('name', 'like', sprintf('%%%s%%', $this->search))
                ->orWhere('email', 'like', sprintf('%%%s%%', $this->search)))
            ->when(! Auth::user()->isSuper(), fn ($query) => $query->whereHas('companies', fn ($q) => $q->whereIn('companies.id', Auth::user()->companies()->pluck('companies.id'))))
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
};
