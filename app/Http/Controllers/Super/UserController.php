<?php

declare(strict_types=1);

namespace App\Http\Controllers\Super;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Super\UserStoreRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = User::query()
            ->with('currentCompany')
            ->when($search, fn ($query) => $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
            ))
            ->latest()
            ->paginate()
            ->withQueryString();

        return view('super.users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        $statuses  = UserStatus::values();
        $types     = UserType::values();

        return view('super.users.create', compact('companies', 'statuses', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create($data);

        if ($data['current_company_id']) {
            $user->companies()->attach($data['current_company_id'], ['role' => Company::ROLE_CUSTOMER]);
        }

        return redirect()->route('super.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('super.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('super.users.eidt', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('super.users.index')->with('success', 'User deleted successfully.');
    }
}
