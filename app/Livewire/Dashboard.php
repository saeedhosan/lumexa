<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\LeadStatus;
use App\Models\Lead;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\View\View;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class Dashboard extends Component
{
    public array $lineChartData = [];

    public array $donutChartData = [];

    public int $totalLeads = 0;

    public int $pendingLeads = 0;

    public int $approvedLeads = 0;

    public int $rejectedLeads = 0;

    public int $thisWeekLeads = 0;

    public int $lastWeekLeads = 0;

    public float $conversionRate = 0;

    public int $avgLeadsPerDay = 0;

    public int $daysRange = 30;

    public Collection $recentLeads;

    public Collection $recentActivities;

    public function mount(): void
    {
        $this->loadStatistics();
        $this->loadLineChartData();
        $this->loadDonutChartData();
        $this->loadTrendData();
        $this->loadRecentLeads();
        $this->loadRecentActivities();
    }

    public function updatedDaysRange(): void
    {
        $this->loadLineChartData();
    }

    public function render(): View
    {
        return view('livewire.dashboard');
    }

    private function loadStatistics(): void
    {
        $this->totalLeads    = Lead::query()->count();
        $this->pendingLeads  = Lead::query()->where('status', LeadStatus::pending)->count();
        $this->approvedLeads = Lead::query()->where('status', LeadStatus::approved)->count();
        $this->rejectedLeads = Lead::query()->where('status', LeadStatus::rejected)->count();

        $totalProcessed       = $this->approvedLeads + $this->rejectedLeads;
        $this->conversionRate = $totalProcessed > 0
            ? round(($this->approvedLeads / $totalProcessed) * 100, 1)
            : 0;
    }

    private function loadTrendData(): void
    {
        $this->thisWeekLeads = Lead::query()
            ->whereBetween('created_at', [
                Date::now()->startOfWeek(),
                Date::now()->endOfWeek(),
            ])
            ->count();

        $this->lastWeekLeads = Lead::query()
            ->whereBetween('created_at', [
                Date::now()->subWeek()->startOfWeek(),
                Date::now()->subWeek()->endOfWeek(),
            ])
            ->count();

        $this->avgLeadsPerDay = $this->daysRange > 0
            ? (int) round($this->totalLeads / $this->daysRange)
            : 0;
    }

    private function loadLineChartData(): void
    {
        $labels = [];
        $data   = [];

        for ($i = $this->daysRange - 1; $i >= 0; $i--) {
            $date     = Date::now()->subDays($i);
            $labels[] = $date->format('M d');

            $count = Lead::query()
                ->whereDate('created_at', $date->toDateString())
                ->count();

            $data[] = $count;
        }

        $this->lineChartData = [
            'labels' => $labels,
            'data'   => $data,
        ];
    }

    private function loadDonutChartData(): void
    {
        $statusCounts = Lead::query()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $labels = [];
        $data   = [];
        $colors = [];

        foreach (LeadStatus::cases() as $status) {
            $count = $statusCounts[$status->value] ?? 0;
            if ($count > 0) {
                $labels[] = $status->label();
                $data[]   = $count;
                $colors[] = $this->getStatusColor($status);
            }
        }

        $this->donutChartData = [
            'labels' => $labels,
            'data'   => $data,
            'colors' => $colors,
        ];
    }

    private function loadRecentLeads(): void
    {
        $this->recentLeads = Lead::query()
            ->with('company')
            ->latest()
            ->take(5)
            ->get();
    }

    private function loadRecentActivities(): void
    {
        $this->recentActivities = Activity::query()
            ->with('causer')
            ->latest()
            ->take(5)
            ->get();
    }

    private function getStatusColor(LeadStatus $status): string
    {
        return match ($status) {
            LeadStatus::pending  => '#6b7280',
            LeadStatus::process  => '#eab308',
            LeadStatus::cleaned  => '#3b82f6',
            LeadStatus::blocked  => '#ef4444',
            LeadStatus::approved => '#22c55e',
            LeadStatus::rejected => '#f97316',
        };
    }
}
