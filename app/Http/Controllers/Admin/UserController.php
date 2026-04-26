<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): Factory|View
    {
        $this->authorize('viewAny', User::class);

        $search = $request->query('search');

        $user = auth()->user();

        $users = User::query()
            ->with('companies')
            ->when($user->type !== UserType::super, fn ($query) => $query->whereHas('companies', fn ($q) => $q->whereIn('companies.id', currentTenant()->tenantKeys())))
            ->when($search, fn ($query) => $query->where(fn ($q) => $q->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')))
            ->orderBy('name')
            ->paginate(10);

        return view('admin.users.index', ['users' => $users, 'search' => $search]);
    }

    public function create(): Factory|View
    {
        $this->authorize('create', User::class);

        $user = auth()->user();
        $tenantKey = currentTenant()->tenantKey();

        $companies = $user->isSuper()
            ? Company::query()->get()
            : currentTenant()->tenantKeys()
                ? Company::query()->whereIn('id', currentTenant()->tenantKeys())->get()
                : collect();

        return view('admin.users.create', [
            'companies' => $companies,
            'statuses'  => UserStatus::values(),
            'types'     => $user->isSuper() ? UserType::values() : null,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();

        $user = auth()->user();

        if ($user->isSuper() && isset($validated['type'])) {
            $type = $validated['type'];
            unset($validated['type']);
        } else {
            $type = UserType::user;
        }

        $companyId = $validated['current_company_id'] ?? currentTenant()->tenantKey();
        unset($validated['current_company_id']);

        $newUser = User::query()->create(array_merge($validated, ['type' => $type]));

        if ($companyId) {
            $newUser->companies()->attach($companyId, ['role' => Company::ROLE_ADMIN]);
        }

        return to_route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user): Factory|View
    {
        $this->authorize('view', $user);

        return view('admin.users.show', ['user' => $user]);
    }

    public function edit(User $user): Factory|View
    {
        $this->authorize('update', $user);

        $currentUser = auth()->user();

        $companies = $currentUser->isSuper()
            ? Company::query()->get()
            : currentTenant()->tenantKeys()
                ? Company::query()->whereIn('id', currentTenant()->tenantKeys())->get()
                : collect();

        return view('admin.users.edit', [
            'user'      => $user,
            'companies' => $companies,
            'statuses'  => UserStatus::values(),
            'types'     => $currentUser->isSuper() ? UserType::values() : null,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        $data = $request->validated();

        $currentUser = auth()->user();

        if (isset($data['password']) && empty($data['password'])) {
            unset($data['password']);
        }

        if ($currentUser->isAdmin() && isset($data['type'])) {
            unset($data['type']);
        }

        $user->update($data);

        $companyId = $data['current_company_id'] ?? currentTenant()->tenantKey();
        if ($companyId) {
            $user->companies()->sync([$companyId => ['role' => Company::ROLE_ADMIN]]);
        }

        return to_route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return to_route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
