<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Domain\Company\CompanyService;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AllCompanyController extends Controller
{
    public function __construct(
        private readonly CompanyService $service
    ) {}

    public function index(): Factory|View
    {
        $companies = $this->service->paginate();

        return view('admin.all-companies.index', ['companies' => $companies]);
    }

    public function create(): Factory|View
    {
        return view('admin.all-companies.create');
    }

    public function store(Request $request): void
    {
        //
    }

    public function show(Company $company): Factory|View
    {
        return view('admin.all-companies.show', ['company' => $company]);
    }

    public function edit(Company $company): Factory|View
    {
        return view('admin.all-companies.edit', ['company' => $company]);
    }

    public function update(Request $request, Company $company): void
    {
        //
    }

    public function destroy(Company $company): void
    {
        //
    }
}
