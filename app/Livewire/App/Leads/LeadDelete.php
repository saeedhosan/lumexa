<?php

declare(strict_types=1);

namespace App\Livewire\App\Leads;

use App\Models\Lead;
use Livewire\Attributes\On;
use Livewire\Component;

class LeadDelete extends Component
{
    #[On('lead-delete')]
    public function destroy(Lead $lead): void
    {
        $lead->delete();

        $this->dispatch('success', 'Lead deleted successfully.');
        $this->redirect(route('app.leads.index', absolute: false), true);
    }

    public function render(): string
    {
        return '<div></div>';
    }
}
