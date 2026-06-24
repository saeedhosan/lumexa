<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCompanyRequest;
use App\Http\Requests\Api\UpdateCompanyRequest;
use App\Http\Resources\CompanyCollection;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $perPage = min((int) $request->query('per_page', 15), 100);

        $companies = Company::query()
            ->with('plan')
            ->latest()
            ->paginate($perPage);

        return new CompanyCollection($companies);
    }

    public function store(StoreCompanyRequest $request): JsonResource
    {
        $company = Company::query()->create(
            $request->validated() + ['created_by' => $request->user()?->id]
        );

        return new CompanyResource($company);
    }

    public function show(int $id): JsonResource
    {
        $company = Company::query()->with('plan')->findOrFail($id);

        return new CompanyResource($company);
    }

    public function update(UpdateCompanyRequest $request, int $id): JsonResource
    {
        $company = Company::query()->findOrFail($id);
        $company->update($request->validated());

        return new CompanyResource($company->load('plan'));
    }

    public function destroy(int $id): JsonResponse
    {
        Company::query()->findOrFail($id)->delete();

        return response()->json(['message' => 'Company deleted successfully.']);
    }
}
