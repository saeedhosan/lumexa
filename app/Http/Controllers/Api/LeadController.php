<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Events\LeadCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreLeadRequest;
use App\Http\Requests\Api\UpdateLeadRequest;
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

    public function store(StoreLeadRequest $request): JsonResource
    {
        $lead = Lead::query()->create($request->validated());

        event(new LeadCreated($lead));

        return new LeadResource($lead);
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

    public function update(UpdateLeadRequest $request, int $id): JsonResource
    {
        $user = $request->user();

        $lead = Lead::query()
            ->whereIn('company_id', $user->companies()->select('companies.id'))
            ->findOrFail($id);

        $this->authorize('update', $lead);

        $lead->update($request->validated());

        return new LeadResource($lead->load('company'));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $lead = Lead::query()
            ->whereIn('company_id', $user->companies()->select('companies.id'))
            ->findOrFail($id);

        $this->authorize('delete', $lead);

        $lead->delete();

        return response()->json(['message' => 'Lead deleted successfully.']);
    }
}
