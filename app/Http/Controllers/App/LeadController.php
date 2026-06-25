<?php

declare(strict_types=1);

namespace App\Http\Controllers\App;

use App\Events\LeadCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\StoreLeadRequest;
use App\Http\Requests\App\UpdateLeadRequest;
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

    public function store(StoreLeadRequest $request): RedirectResponse
    {
        $this->authorize('create', Lead::class);

        $lead = Lead::query()->create([
            'title'      => $request->validated()['title'],
            'user_id'    => auth()->id(),
            'company_id' => auth()->user()->current_company_id,
        ]);

        event(new LeadCreated($lead));

        return to_route('app.leads.index')
            ->with('toast', 'Lead created successfully.');
    }

    public function show(Lead $lead): Factory|View
    {
        return view('app.leads.show', ['lead' => $lead]);
    }

    public function edit(Lead $lead): Factory|View
    {
        return view('app.leads.edit', ['lead' => $lead]);
    }

    public function update(UpdateLeadRequest $request, Lead $lead): RedirectResponse
    {
        $this->authorize('update', $lead);

        $lead->update($request->validated());

        return to_route('app.leads.index')
            ->with('toast', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $this->authorize('delete', $lead);

        $lead->delete();

        return to_route('app.leads.index')
            ->with('toast', 'Lead deleted successfully.');
    }
}
