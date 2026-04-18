<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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

    public function store(Request $request): void
    {
        //
    }

    public function show(Plan $plan): Factory|View
    {
        return view('admin.plans.show', ['plan' => $plan]);
    }

    public function edit(Plan $plan): Factory|View
    {
        return view('admin.plans.edit', ['plan' => $plan]);
    }

    public function update(Request $request, Plan $plan): void
    {
        //
    }

    public function destroy(Plan $plan): void
    {
        //
    }
}
