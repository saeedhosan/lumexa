<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\SendInvite;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendInviteRequest;
use App\Models\Company;
use App\Models\Invite;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    public function __construct(
        private readonly SendInvite $sendInvite
    ) {}

    public function index(): Factory|View
    {
        $user = Auth::user();

        $companyIds = $user->isSuper()
            ? Company::query()->pluck('id')
            : $user->companies()->pluck('companies.id');

        $invites = Invite::with(['company', 'inviter'])
            ->whereIn('company_id', $companyIds)
            ->latest()
            ->paginate(15);

        return view('admin.invites.index', ['invites' => $invites]);
    }

    public function create(): Factory|View
    {
        $user = Auth::user();

        $companies = $user->isSuper()
            ? Company::query()->get()
            : $user->companies()->get();

        return view('admin.invites.create', ['companies' => $companies]);
    }

    public function store(SendInviteRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user    = Auth::user();
        $company = Company::findOrFail($validated['company_id']);

        if (! $user->isSuper() && ! $user->companies()->where('companies.id', $company->id)->exists()) {
            abort(403, 'You do not have permission to invite users to this company.');
        }

        $this->sendInvite->handle(
            $company,
            $validated['email'],
            $validated['role'],
            $user->id()
        );

        return redirect()
            ->route('admin.invites.index')
            ->with('toast', 'Invitation sent successfully.');
    }

    public function show(Invite $invite): Factory|View
    {
        return view('admin.invites.show', ['invite' => $invite]);
    }

    public function destroy(Invite $invite): RedirectResponse
    {
        $user = Auth::user();

        if (! $user->isSuper() && ! $user->companies()->where('companies.id', $invite->company_id)->exists()) {
            abort(403, 'You do not have permission to delete this invitation.');
        }

        $invite->delete();

        return redirect()
            ->route('admin.invites.index')
            ->with('toast', 'Invitation deleted.');
    }
}
