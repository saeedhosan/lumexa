<?php

declare(strict_types=1);

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public ?Company $currentCompany = null;

    public Collection|array $companies = [];

    public function mount(): void
    {
        $this->currentCompany = Auth::user()?->currentCompany;
        $this->companies      = Auth::user()?->companies ?? [];
    }

    public function switchCompany(Company $company, mixed $url = null): void
    {

        if (! Auth::user()->companies()->where('companies.id', $company->id)->exists()) {
            return;
        }

        Auth::user()->update(['current_company_id' => $company->id]);

        $this->currentCompany = $company;

        $this->redirect($url ?? route('dashboard'), navigate: true);
    }
};
