<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendInviteRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class InviteController extends Controller
{
    public function index(): Factory|View
    {
        $user = auth()->user();

        $companyIds = $user->isSuper()
            ? Company::query()->pluck('id')
            : $user->companies()->pluck('companies.id');

        $users = User::query()->whereHas('companies', fn ($q) => $q->whereIn('companies.id', $companyIds))
            ->with('companies')
            ->latest()
            ->paginate(15);

        return view('admin.invites.index', ['users' => $users]);
    }

    public function create(): Factory|View
    {
        $user = auth()->user();

        $companies = $user->isSuper()
            ? Company::query()->get()
            : $user->companies()->get();

        return view('admin.invites.create', [
            'companies'   => $companies,
            'defaultRole' => Company::ROLE_CUSTOMER,
        ]);
    }

    public function store(SendInviteRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user    = auth()->user();
        $company = Company::query()->findOrFail($validated['company_id']);

        abort_if(
            ! $user->isSuper() && ! $user->companies()->where('companies.id', $company->id)->exists(),
            403,
            'You do not have permission to invite users to this company.'
        );

        $existingUser = User::query()->where('email', $validated['email'])->firstOrFail();

        if ($existingUser->companies()->where('company_id', $company->id)->exists()) {
            return back()
                ->with('toast', 'This user already has access to this company.')
                ->withInput();
        }

        $existingUser->companies()->attach($company->id, ['role' => $validated['role']]);

        return to_route('admin.invites.index')
            ->with('toast', 'User added to company successfully.');
    }
}
