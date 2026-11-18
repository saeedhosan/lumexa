<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\UserType;
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
        $this->authorize('viewAny', Company::class);

        $perPage = min((int) $request->query('per_page', 15), 100);

        $query = Company::query()->with('plan');

        if ($request->user()->type === UserType::user) {
            $query->whereIn('companies.id', $request->user()->companies()->select('companies.id'));
        }

        return new CompanyCollection($query->latest()->paginate($perPage));
    }

    public function store(StoreCompanyRequest $request): JsonResource
    {
        $this->authorize('create', Company::class);

        $company = Company::query()->create(
            $request->validated() + ['created_by' => $request->user()?->id]
        );

        return new CompanyResource($company);
    }

    public function show(Request $request, int $id): JsonResource
    {
        $company = Company::query()->with('plan')->findOrFail($id);

        $this->authorize('view', $company);

        return new CompanyResource($company);
    }

    public function update(UpdateCompanyRequest $request, int $id): JsonResource
    {
        $company = Company::query()->findOrFail($id);

        $this->authorize('update', $company);

        $company->update($request->validated());

        return new CompanyResource($company->load('plan'));
    }

    public function destroy(int $id): JsonResponse
    {
        $company = Company::query()->findOrFail($id);

        $this->authorize('delete', $company);

        $company->delete();

        return response()->json(['message' => 'Company deleted successfully.']);
    }
}
