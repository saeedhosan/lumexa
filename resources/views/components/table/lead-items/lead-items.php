<?php

declare(strict_types=1);

use App\Models\Lead;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    protected $queryString = ['search', 'sortBy', 'sortDirection'];

    #[On('refresh-leads')]
    public function refreshLeads()
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy        = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function leads()
    {
        return Lead::query()
            ->when(
                $this->search,
                fn ($query) => $query->where('title', 'like', "%{$this->search}%"),
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    public function delete(Lead $lead): void
    {
        $lead->delete();

        Flux::toast(__('Lead deleted successfully.'), variant: 'success');
    }
};
