<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Billing\CreateCheckoutSession;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Plan;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function __construct(
        private readonly CreateCheckoutSession $createCheckoutSession,
    ) {}

    public function index(): Factory|View
    {
        return view('admin.billing.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Factory|View
    {
        return view('admin.billing.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $plan    = Plan::query()->findOrFail($request->integer('plan_id'));
        $company = Auth::user()->currentCompany;

        $session = $this->createCheckoutSession->handle(
            $company,
            $plan,
            $request->string('interval', 'monthly')->value()
        );

        return redirect($session->url);
    }

    public function show(Company $billing): Factory|View
    {
        return view('admin.billing.show', ['company' => $billing]);
    }

    public function edit(Company $billing): Factory|View
    {
        return view('admin.billing.edit', ['company' => $billing]);
    }
}
