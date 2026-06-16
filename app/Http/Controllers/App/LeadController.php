<?php

declare(strict_types=1);

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request): Factory|View
    {
        return view('app.leads.index', [
            'search'    => $request->query('search'),
            'sort'      => $request->query('sort', 'created_at'),
            'direction' => $request->query('direction', 'desc'),
        ]);
    }

    public function create(): RedirectResponse
    {
        return to_route('app.leads.index', ['action' => 'create']);
    }

    public function store(Request $request): void
    {
        //
    }

    public function show(Lead $lead): Factory|View
    {
        return view('app.leads.show', ['lead' => $lead]);
    }

    public function edit(Lead $lead): Factory|View
    {
        return view('app.leads.edit', ['lead' => $lead]);
    }

    public function update(Request $request, Lead $lead): void
    {
        //
    }

    public function destroy(Lead $lead): void
    {
        //
    }
}
