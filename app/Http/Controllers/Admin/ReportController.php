<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ReportController extends Controller
{
    public function index(): Factory|View
    {
        $user = auth()->user();
        $tenantKeys = currentTenant()->tenantKeys();

        $companyIds = $user->isSuper()
            ? Company::query()->pluck('id')
            : $tenantKeys;

        $stats = [
            'total_users'     => User::query()->whereHas('companies', fn ($q) => $q->whereIn('companies.id', $companyIds))->count(),
            'total_companies' => $companyIds->count(),
            'users_by_type'   => User::query()->whereHas('companies', fn ($q) => $q->whereIn('companies.id', $companyIds))
                ->selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
            'users_per_company' => Company::query()->whereIn('id', $companyIds)
                ->withCount('users')
                ->get()
                ->pluck('users_count', 'name')
                ->toArray(),
        ];

        return view('admin.reports.index', ['stats' => $stats]);
    }
}
