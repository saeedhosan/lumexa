<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(): Factory|View
    {
        return view('super.settings.index');
    }

    public function create(): Factory|View
    {
        return view('super.settings.create');
    }

    public function show(string $id): Factory|View
    {
        return view('super.settings.show', ['id' => $id]);
    }

    public function edit(string $id): Factory|View
    {
        return view('super.settings.edit', ['id' => $id]);
    }

    public function create(): Factory|View
    {
        return view('admin.settings.create');
    }

    public function store(Request $request): void
    {
        //
    }

    public function show(string $id): Factory|View
    {
        return view('admin.settings.show', ['id' => $id]);
    }

    public function edit(string $id): Factory|View
    {
        return view('admin.settings.edit', ['id' => $id]);
    }

    public function update(Request $request, string $id): void
    {
        //
    }

    public function destroy(string $id): void
    {
        //
    }
}
