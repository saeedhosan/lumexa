<?php

declare(strict_types=1);

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Factory|View
    {
        $services = Service::query()->latest()->paginate(15);

        return view('app.services.index', ['services' => $services]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Factory|View
    {
        return view('app.services.create');
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
    public function show(Service $service): Factory|View
    {
        return view('app.services.show', ['service' => $service]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service): Factory|View
    {
        return view('app.services.edit', ['service' => $service]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service): void
    {
        //
    }
}
