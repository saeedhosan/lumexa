<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\AddExistingUserToCompany;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendInviteRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class InviteController extends Controller
{
    public function __construct(
        private readonly AddExistingUserToCompany $addExistingUserToCompany,
    ) {}

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
            'defaultRole' => Company::ROLE_USER,
        ]);
    }

    public function store(SendInviteRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $company   = Company::query()->findOrFail($validated['company_id']);

        $this->addExistingUserToCompany->handle(
            auth()->user(),
            $company,
            $validated['email'],
            $validated['role']
        );

        return to_route('admin.invites.index')
            ->with('toast', 'User added to company successfully.');
    }
}
