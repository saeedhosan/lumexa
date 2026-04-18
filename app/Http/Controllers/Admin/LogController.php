<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(): Factory|View
    {
        return view('admin.logs.index');
    }

    public function create(): Factory|View
    {
        return view('admin.logs.create');
    }

    public function store(Request $request): void
    {
        //
    }

    public function show(Log $log): Factory|View
    {
        return view('admin.logs.show', ['log' => $log]);
    }

    public function edit(Log $log): Factory|View
    {
        return view('admin.logs.edit', ['log' => $log]);
    }

    public function update(Request $request, Log $log): void
    {
        //
    }

    public function destroy(Log $log): void
    {
        //
    }
}
