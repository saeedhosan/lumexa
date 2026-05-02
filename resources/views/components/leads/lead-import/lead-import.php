<?php

declare(strict_types=1);

use App\Enums\LeadStatus;
use App\Imports\LeadListImport;
use App\Models\Lead;
use Flux\Flux;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

new class extends Component
{
    use WithFileUploads;

    public string $title = '';

    public $file = null;

    public function mount()
    {
        if (app(Request::class)->input('action') === 'create') {
            Flux::modal('lead-import')->show();
        }
    }

    public function import(): void
    {
        $this->validate();

        DB::transaction(function (): Lead {

            $lead = Lead::query()->create([
                'user_id'    => Auth::id(),
                'company_id' => Auth::user()->current_company_id,
                'title'      => $this->title,
                'status'     => LeadStatus::pending,
            ]);

            Excel::import(new LeadListImport($lead), $this->file);

            $lead->update(['status' => LeadStatus::approved]);

            return $lead;
        });

        $this->reset(['title', 'file']);

        Flux::modals()->close();

        Flux::toast(__('Lead imported successfully.'), variant: 'success');

        $this->dispatch('refresh-leads');
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'file'  => 'required|file|mimes:xlsx,xls,csv',
        ];
    }
};
