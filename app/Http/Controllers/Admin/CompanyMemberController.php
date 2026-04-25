<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\UserStatus;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddCompanyMemberRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CompanyMemberController extends Controller
{
    public function index(Company $company): Factory|View
    {
        $members = $company->users()->get();

        return view('admin.companies.members.index', [
            'company'  => $company,
            'members'  => $members,
            'statuses' => UserStatus::values(),
            'types'    => UserType::values(),
        ]);
    }

    public function store(AddCompanyMemberRequest $request, Company $company): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::query()->create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
            'status'   => $validated['status'] ?? UserStatus::active,
            'type'     => $validated['type']   ?? UserType::customer,
        ]);

        $company->users()->attach($user->id, [
            'role'       => $validated['role'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('admin.companies.members.index', $company)
            ->with('toast', 'Member added successfully.');
    }

    public function destroy(Company $company, User $user): RedirectResponse
    {
        $company->users()->detach($user->id);

        return redirect()
            ->route('admin.companies.members.index', $company)
            ->with('toast', 'Member removed successfully.');
    }
}
