<?php

declare(strict_types=1);

namespace App\Http\Controllers\App;

use App\Domain\Lead\LeadService;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(
        private LeadService $service
    ) {}

    public function index(Request $request): Factory|View
    {
        $search    = $request->query('search');
        $sort      = $request->query('sort', 'created_at');
        $direction = $request->query('direction', 'desc');

        $leads = $this->service->search($search, $sort, $direction);

        return view('app.leads.index', [
            'leads'     => $leads,
            'search'    => $search,
            'sort'      => $sort,
            'direction' => $direction,
        ]);
    }

    public function create(): Factory|View
    {
        return view('app.leads.create');
    }

    public function store(Request $request): void
    {
        //
    }

    public function show(Lead $lead, Request $request): View|Factory
    {
        $lead->load('leadList');

        $search = $request->query('search');

        $leadLists = $lead->leadList()
            ->when($search, fn ($query) => $query->where('first_name', 'like', sprintf('%%%s%%', $search))
                ->orWhere('last_name', 'like', sprintf('%%%s%%', $search))
                ->orWhere('email', 'like', sprintf('%%%s%%', $search))
                ->orWhere('phone', 'like', sprintf('%%%s%%', $search)))
            ->get();

        return view('app.leads.show', ['lead' => $lead, 'leadLists' => $leadLists, 'search' => $search]);
    }

    public function edit($id): void
    {
        //
    }

    public function update(Request $request, $id): void
    {
        //
    }

    public function destroy($id): void
    {
        //
    }
}
