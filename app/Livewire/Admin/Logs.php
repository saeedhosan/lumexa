<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

class Logs extends Component
{
    public string $search = '';

    public string $filterLogName = '';

public function resetFilters(): void
    {
        $this->search = '';
        $this->filterLogName = '';
    }

    public function refresh(): void
    {
        $this->reset();
    }

    public function render(): \Illuminate\View\View
    {
        $activities = Activity::query()
            ->with('causer')
            ->when($this->search, fn (Builder $q) => $q->where('description', 'like', '%'.$this->search.'%'))
            ->when($this->filterLogName, fn (Builder $q) => $q->where('log_name', $this->filterLogName))
            ->latest()
            ->paginate(20);

        $logNames = Activity::query()
            ->select('log_name')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name');

        return view('livewire.admin.logs', [
            'activities' => $activities,
            'logNames'   => $logNames,
        ]);
    }
}
