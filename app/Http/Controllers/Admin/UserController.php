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
    public function index(Request $request): Factory|View
    {
        $search = $request->query('search');

        $users = User::query()
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(10);

        return view('admin.users.index', ['users' => $users, 'search' => $search]);
    }

    public function create(): Factory|View
    {
        return view('super.users.create');
    }

    public function store(Request $request): void
    {
        //
    }

    public function show(User $user): Factory|View
    {
        return view('super.users.show', ['user' => $user]);
    }

    public function edit(User $user): Factory|View
    {
        return view('super.users.edit', ['user' => $user]);
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
