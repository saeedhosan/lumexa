<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeadCollection;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class LeadController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $perPage = min((int) $request->query('per_page', 15), 100);

        $companyId = $this->authorizeCompanyAccess(
            $request,
            $request->query('company_id', $request->user()?->current_company_id),
        );

        $leads = Lead::query()
            ->with('company')
            ->when($companyId, fn ($q, $id) => $q->where('company_id', $id))
            ->latest()
            ->paginate($perPage);

        return new LeadCollection($leads);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $companyId = $this->authorizeCompanyAccess(
            $request,
            $request->query('company_id', $request->user()?->current_company_id),
        );

        $lead = Lead::query()
            ->with('company', 'leadList')
            ->when($companyId, fn ($q, $id) => $q->where('company_id', $id))
            ->findOrFail($id);

        return response()->json([
            'data' => new LeadResource($lead),
        ]);
    }

    private function authorizeCompanyAccess(Request $request, int|string|null $companyId): int|string|null
    {
        $user = $request->user();

        if ($companyId === null) {
            return null;
        }

        $userCompanyIds = $user->companies()->pluck('companies.id')->toArray();

        throw_unless(in_array((int) $companyId, $userCompanyIds, true), AccessDeniedHttpException::class, "You do not have access to this company's data.");

        return $companyId;
    }
}
