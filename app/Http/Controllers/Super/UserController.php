<?php

declare(strict_types=1);

namespace App\Http\Controllers\Super;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Super\UserStoreRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Factory|View
    {
        $search = $request->get('search');

        $users = User::query()
            ->with('currentCompany')
            ->when($search, fn ($query) => $query->where(fn ($q) => $q
                ->where('name', 'like', sprintf('%%%s%%', $search))
                ->orWhere('email', 'like', sprintf('%%%s%%', $search))
            ))
            ->latest()
            ->paginate()
            ->withQueryString();

        return view('super.users.index', ['users' => $users, 'search' => $search]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Factory|View
    {
        $companies = Company::query()->where('is_active', true)->orderBy('name')->get();
        $statuses  = UserStatus::values();
        $types     = UserType::values();

        return view('super.users.create', ['companies' => $companies, 'statuses' => $statuses, 'types' => $types]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::query()->create($data);

        if ($data['current_company_id']) {
            $user->companies()->attach($data['current_company_id'], ['role' => Company::ROLE_CUSTOMER]);
        }

        return to_route('super.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): Factory|View
    {
        return view('super.users.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Factory|View
    {
        return view('super.users.eidt', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return to_route('super.users.index')->with('success', 'User deleted successfully.');
    }
}
