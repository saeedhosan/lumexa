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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Factory|View
    {
        $this->authorize('viewAny');

        $search = $request->query('search');

        $users = User::query()
            ->when($search, fn ($query) => $query->where('name', 'like', sprintf('%%%s%%', $search))->orWhere('email', 'like', sprintf('%%%s%%', $search)))
            ->when(Auth::user()->type !== UserType::super, fn ($query) => $query->whereHas('companies', fn ($q) => $q->whereIn('companies.id', Auth::user()->companies()->pluck('companies.id'))))
            ->orderBy('name')
            ->paginate(10);

        return view('admin.users.index', ['users' => $users, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Factory|View
    {
        $this->authorize('create');

        return view('admin.users.create');
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
    public function show(User $user): Factory|View
    {
        $this->authorize('show', $user);

        return view('admin.users.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Factory|View
    {
        $this->authorize('edit', $user);

        return view('admin.users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): void
    {

        $this->authorize('update', $user);

        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): void
    {
        //
    }
}
