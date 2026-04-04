<?php

declare(strict_types=1);

use App\Models\Lead;
use App\Models\LeadList;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public Lead $lead;

    public string $search = '';

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    protected $queryString = ['search', 'sortBy', 'sortDirection'];

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
    public function leadLists()
    {
        return $this->lead
            ->leadList()
            ->when(
                $this->search,
                fn ($query) => $query
                    ->where('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('phone', 'like', "%{$this->search}%"),
            )
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    public function delete(LeadList $leadList): void
    {
        $leadList->delete();

        Flux::toast('Deleted', __('Lead deleted successfully.'), variant: 'success');
    }
};
