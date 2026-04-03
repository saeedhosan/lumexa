<?php

declare(strict_types=1);

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Factory|View
    {
        $search    = $request->query('search');
        $sort      = $request->query('sort', 'created_at');
        $direction = $request->query('direction', 'desc');

        $leads = Lead::query()
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('app.leads.index', [
            'leads'     => $leads,
            'search'    => $search,
            'sort'      => $sort,
            'direction' => $direction,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Factory|View
    {
        return view('app.leads.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): void
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): void
    {
        //
    }
}
