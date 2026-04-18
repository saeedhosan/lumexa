<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request): Factory|View
    {
        $search = $request->query('search');

        $users = User::query()
            ->when($search, fn ($query) => $query->where('name', 'like', sprintf('%%%s%%', $search))->orWhere('email', 'like', sprintf('%%%s%%', $search)))
            ->when(Auth::user()->type !== UserType::super, fn ($query) => $query->whereHas('companies', fn ($q) => $q->whereIn('companies.id', Auth::user()->companies()->pluck('companies.id'))))
            ->orderBy('name')
            ->paginate(10);

        return view('admin.users.index', ['users' => $users, 'search' => $search]);
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
