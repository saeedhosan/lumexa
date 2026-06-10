<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Onboarding extends Component
{
    public int $step = 1;

    public string $companyName;

    public string $name;

    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;

        $company = $user->currentCompany;

        $this->companyName = $company?->name ?? $user->name."'s Company";
    }

    public function nextStep(): void
    {
        $this->validateStep();

        $this->step++;
    }

    public function previousStep(): void
    {
        $this->step--;
    }

    public function complete(): void
    {
        $this->validateStep();

        /** @var User $user */
        $user = Auth::user();

        $company = $user->currentCompany;

        if ($company instanceof Company && $company->name !== $this->companyName) {
            $company->update(['name' => $this->companyName]);
        }

        if ($user->name !== $this->name) {
            $user->update(['name' => $this->name]);
        }

        $user->update(['onboarded_at' => now()]);

        $this->dispatch('toast', message: 'Welcome aboard! Your workspace is ready.', type: 'success');

        $this->redirectRoute('dashboard', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.onboarding');
    }

    private function validateStep(): void
    {
        if ($this->step === 2) {
            $this->validate(['companyName' => ['required', 'string', 'max:255']]);
        }

        if ($this->step === 3) {
            $this->validate(['name' => ['required', 'string', 'max:255']]);
        }
    }
}
