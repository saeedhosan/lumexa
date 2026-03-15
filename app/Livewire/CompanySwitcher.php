<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CompanySwitcher extends Component
{
    public ?Company $currentCompany = null;

    public function mount(): void
    {
        $this->currentCompany = Auth::user()?->currentCompany;
    }

    public function getCompaniesProperty(): \Illuminate\Database\Eloquent\Collection
    {
        return Auth::user()?->companies ?? collect();
    }

    public function switchCompany(Company $company): void
    {
        if (! auth()->user()->companies()->where('companies.id', $company->id)->exists()) {
            return;
        }

        auth()->user()->update(['current_company_id' => $company->id]);
        $this->currentCompany = $company;
    }

    public function render()
    {
        return view('livewire.company-switcher', [
            'companies'      => $this->companies,
            'currentCompany' => $this->currentCompany,
        ]);
    }
}
