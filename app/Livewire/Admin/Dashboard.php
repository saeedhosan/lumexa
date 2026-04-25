<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Company;
use App\Models\Lead;
use App\Models\Plan;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class Dashboard extends Component
{
    public int $totalUsers = 0;

    public int $activeUsers = 0;

    public int $newUsersThisWeek = 0;

    public int $totalCompanies = 0;

    public int $activeCompanies = 0;

    public int $totalServices = 0;

    public int $totalPlans = 0;

    public int $activePlans = 0;

    public Collection $recentRegistrations;

    public Collection $recentActivities;

    public Collection $quickStats;

    public function mount(): void
    {
        $this->loadUserStats();
        $this->loadCompanyStats();
        $this->loadServiceStats();
        $this->loadPlanStats();
        $this->loadRecentRegistrations();
        $this->loadRecentActivities();
        $this->loadQuickStats();
    }

    public function render(): View
    {
        return view('livewire.admin.dashboard');
    }

    private function loadUserStats(): void
    {
        $this->totalUsers       = User::query()->count();
        $this->activeUsers      = User::query()->where('is_active', true)->count();
        $this->newUsersThisWeek = User::query()
            ->whereBetween('created_at', [
                Date::now()->startOfWeek(),
                Date::now()->endOfWeek(),
            ])
            ->count();
    }

    private function loadCompanyStats(): void
    {
        $this->totalCompanies  = Company::query()->count();
        $this->activeCompanies = Company::query()->where('is_active', true)->count();
    }

    private function loadServiceStats(): void
    {
        $this->totalServices = Service::query()->count();
    }

    private function loadPlanStats(): void
    {
        $this->totalPlans  = Plan::query()->count();
        $this->activePlans = Plan::query()->where('is_active', true)->count();
    }

    private function loadRecentRegistrations(): void
    {
        $this->recentRegistrations = User::query()
            ->with('companies')
            ->latest()
            ->take(5)
            ->get();
    }

    private function loadRecentActivities(): void
    {
        $this->recentActivities = Activity::query()
            ->with('causer')
            ->latest()
            ->take(10)
            ->get();
    }

    private function loadQuickStats(): void
    {
        $this->quickStats = collect([
            ['label' => 'Leads', 'count' => Lead::query()->count()],
            ['label' => 'Companies', 'count' => $this->totalCompanies],
            ['label' => 'Services', 'count' => $this->totalServices],
            ['label' => 'Plans', 'count' => $this->totalPlans],
        ]);
    }
}
