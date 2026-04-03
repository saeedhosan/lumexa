<?php

declare(strict_types=1);

namespace App\Livewire\App\Leads;

use App\Models\LeadList;
use Livewire\Attributes\On;
use Livewire\Component;

class LeadListDelete extends Component
{
    #[On('lead-list-delete')]
    public function delete(LeadList $leadList): void
    {
        $leadList->delete();

        $this->dispatch('success', 'Lead deleted successfully.');
    }

    public function render(): string
    {
        return '<div></div>';
    }
}
