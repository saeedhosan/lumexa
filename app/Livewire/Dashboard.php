<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\LeadStatus;
use App\Models\Lead;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
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

    public function clearCache(): void
    {
        Cache::forget($this->cachePrefix('statistics'));
        Cache::forget($this->cachePrefix('line_chart:'.$this->daysRange));
        Cache::forget($this->cachePrefix('donut_chart'));

        $this->loadStatistics();
        $this->loadLineChartData();
        $this->loadDonutChartData();
    }

    private function cachePrefix(string $name = ''): string
    {
        $tenantKey = currentTenant()->tenantKey();

        return 'app:dashboard:'.$tenantKey.':'.$name;
    }

    private function loadStatistics(): void
    {
        $cacheKey = $this->cachePrefix('statistics');

        $stats = Cache::remember($cacheKey, now()->addMinutes(5), function (): array {
            $counts = Lead::query()
                ->selectRaw('count(*) as total')
                ->selectRaw('count(case when status = ? then 1 end) as pending', [LeadStatus::pending->value])
                ->selectRaw('count(case when status = ? then 1 end) as approved', [LeadStatus::approved->value])
                ->selectRaw('count(case when status = ? then 1 end) as rejected', [LeadStatus::rejected->value])
                ->toBase()
                ->first();

            $totalLeads    = (int) ($counts->total ?? 0);
            $pendingLeads  = (int) ($counts->pending ?? 0);
            $approvedLeads = (int) ($counts->approved ?? 0);
            $rejectedLeads = (int) ($counts->rejected ?? 0);

            $totalProcessed = $approvedLeads + $rejectedLeads;
            $conversionRate = $totalProcessed > 0
                ? round(($approvedLeads / $totalProcessed) * 100, 1)
                : 0;

            return [
                'totalLeads'     => $totalLeads,
                'pendingLeads'   => $pendingLeads,
                'approvedLeads'  => $approvedLeads,
                'rejectedLeads'  => $rejectedLeads,
                'conversionRate' => $conversionRate,
            ];
        });

        $this->totalLeads     = $stats['totalLeads'];
        $this->pendingLeads   = $stats['pendingLeads'];
        $this->approvedLeads  = $stats['approvedLeads'];
        $this->rejectedLeads  = $stats['rejectedLeads'];
        $this->conversionRate = $stats['conversionRate'];
    }

    private function loadTrendData(): void
    {
        $counts = Lead::query()
            ->whereBetween('created_at', [
                Date::now()->subWeek()->startOfWeek(),
                Date::now()->endOfWeek(),
            ])
            ->selectRaw('count(case when created_at >= ? then 1 end) as current_week', [
                Date::now()->startOfWeek()->toDateTimeString(),
            ])
            ->selectRaw('count(case when created_at < ? then 1 end) as last_week', [
                Date::now()->startOfWeek()->toDateTimeString(),
            ])
            ->toBase()
            ->first();

        $this->thisWeekLeads = (int) ($counts->current_week ?? 0);
        $this->lastWeekLeads = (int) ($counts->last_week ?? 0);

        $this->avgLeadsPerDay = $this->daysRange > 0
            ? (int) round($this->totalLeads / $this->daysRange)
            : 0;
    }

    private function loadLineChartData(): void
    {
        $cacheKey = $this->cachePrefix('line_chart:'.$this->daysRange);

        $chartData = Cache::remember($cacheKey, now()->addMinutes(10), function (): array {
            $labels = [];
            $data   = [];

            $startDate = Date::now()->subDays($this->daysRange - 1)->startOfDay();

            $dateExpression = DB::getDriverName() === 'sqlite'
                ? "strftime('%Y-%m-%d', created_at)"
                : 'DATE(created_at)';

            $counts = Lead::query()
                ->where('created_at', '>=', $startDate)
                ->selectRaw($dateExpression.' as date, count(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            for ($i = $this->daysRange - 1; $i >= 0; $i--) {
                $date     = Date::now()->subDays($i);
                $labels[] = $date->format('M d');
                $data[]   = $counts->get($date->toDateString(), 0);
            }

            return ['labels' => $labels, 'data' => $data];
        });

        $this->lineChartData = $chartData;
    }

    private function loadDonutChartData(): void
    {
        $cacheKey = $this->cachePrefix('donut_chart');

        $chartData = Cache::remember($cacheKey, now()->addMinutes(10), function (): array {
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

            return ['labels' => $labels, 'data' => $data, 'colors' => $colors];
        });

        $this->donutChartData = $chartData;
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
