<?php

declare(strict_types=1);

use App\Enums\LeadStatus;
use App\Imports\LeadListImport;
use App\Models\Lead;
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

        $this->redirect(route('app.leads.index', false), true);
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'file'  => 'required|file|mimes:xlsx,xls,csv',
        ];
    }
};
