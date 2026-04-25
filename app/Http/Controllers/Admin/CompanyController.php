<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateCompany;
use App\Actions\Admin\UpdateCompany;
use App\Domain\Company\CompanyService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCompanyRequest;
use App\Http\Requests\Admin\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function __construct(
        private readonly CompanyService $service
    ) {}

    public function index(): Factory|View
    {
        $companies = $this->service->paginateForAdmin(Auth::user());

        return view('admin.companies.index', ['companies' => $companies]);
    }

    public function create(): Factory|View
    {
        return view('admin.companies.create');
    }

    public function store(StoreCompanyRequest $request): RedirectResponse
    {
        $company = resolve(CreateCompany::class)->handle($request->validated(), Auth::id());

        return to_route('admin.companies.show', $company)
            ->with('toast', 'Company created successfully.');
    }

    public function show(Company $company): Factory|View
    {
        return view('admin.companies.show', ['company' => $company]);
    }

    public function edit(Company $company): Factory|View
    {
        return view('admin.companies.edit', ['company' => $company]);
    }

    public function update(UpdateCompanyRequest $request, Company $company): RedirectResponse
    {
        resolve(UpdateCompany::class)->handle($company, $request->validated());

        return to_route('admin.companies.show', $company)
            ->with('toast', 'Company updated successfully.');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return to_route('admin.companies.index')
            ->with('toast', 'Company deleted successfully.');
    }
}
