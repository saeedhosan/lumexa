<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePlanRequest;
use App\Http\Requests\Admin\UpdatePlanRequest;
use App\Models\Plan;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PlanController extends Controller
{
    public function index(): Factory|View
    {
        return view('admin.plans.index');
    }

    public function create(): Factory|View
    {
        return view('admin.plans.create');
    }

    public function store(StorePlanRequest $request): RedirectResponse
    {
        Plan::query()->create($request->validated());

        return to_route('admin.plans.index')
            ->with('toast', 'Plan created successfully.');
    }

    public function show(Plan $plan): Factory|View
    {
        return view('admin.plans.show', ['plan' => $plan]);
    }

    public function edit(Plan $plan): Factory|View
    {
        return view('admin.plans.edit', ['plan' => $plan]);
    }

    public function update(UpdatePlanRequest $request, Plan $plan): RedirectResponse
    {
        $plan->update($request->validated());

        return to_route('admin.plans.index')
            ->with('toast', 'Plan updated successfully.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $plan->delete();

        return to_route('admin.plans.index')
            ->with('toast', 'Plan deleted successfully.');
    }
}
