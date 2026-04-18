<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): Factory|View
    {
        return view('admin.users.index');
    }

    public function create(): Factory|View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): void
    {
        //
    }

    public function show(User $user): Factory|View
    {
        return view('admin.users.show', ['user' => $user]);
    }

    public function edit(User $user): Factory|View
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(Request $request, User $user): void
    {
        //
    }

    public function destroy(User $user): void
    {
        //
    }
}
