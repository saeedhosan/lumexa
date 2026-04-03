<?php

declare(strict_types=1);

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Factory|View
    {
        $companies = Auth::user()->companies()->orderBy('name')->get();

        return view('app.companies.index', ['companies' => $companies]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Factory|View
    {
        return view('app.companies.create');
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
    public function show(Company $company): Factory|View
    {
        return view('app.companies.show', ['company' => $company]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company): Factory|View
    {
        return view('app.companies.edit', ['company' => $company]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company): void
    {
        //
    }
}
