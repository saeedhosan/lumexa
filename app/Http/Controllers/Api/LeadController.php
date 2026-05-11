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

class LeadController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $perPage = min((int) $request->query('per_page', 15), 100);

        $user = $request->user();

        $leads = Lead::query()
            ->whereIn('company_id', $user->companies()->select('companies.id'))
            ->with('company')
            ->latest()
            ->paginate($perPage);

        return new LeadCollection($leads);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $lead = Lead::query()
            ->whereIn('company_id', $user->companies()->select('companies.id'))
            ->with('company', 'leadList')
            ->findOrFail($id);

        $this->authorize('view', $lead);

        return response()->json([
            'data' => new LeadResource($lead),
        ]);
    }
}
