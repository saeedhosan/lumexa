<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\LeadStatus;
use App\Events\LeadCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreLeadRequest;
use App\Http\Requests\Api\UpdateLeadRequest;
use App\Http\Resources\LeadCollection;
use App\Http\Resources\LeadResource;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $perPage = min((int) $request->query('per_page', 15), 100);

        $leads = Lead::query()
            ->with('company')
            ->latest()
            ->paginate($perPage);

        return new LeadCollection($leads);
    }

    public function store(StoreLeadRequest $request): JsonResource
    {
        $user = $request->user();

        abort_unless($user instanceof User, 401);

        $lead = Lead::query()->create([
            ...$request->validated(),
            'user_id' => $user->getAuthIdentifier(),
            'status'  => LeadStatus::pending,
        ]);

        event(new LeadCreated($lead));

        return new LeadResource($lead);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $lead = Lead::query()
            ->with('company', 'leadList')
            ->findOrFail($id);

        $this->authorize('view', $lead);

        return response()->json([
            'data' => new LeadResource($lead),
        ]);
    }

    public function update(UpdateLeadRequest $request, int $id): JsonResource
    {
        $lead = Lead::query()->findOrFail($id);

        $this->authorize('update', $lead);

        $lead->update($request->validated());

        return new LeadResource($lead->load('company'));
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $lead = Lead::query()->findOrFail($id);

        $this->authorize('delete', $lead);

        $lead->delete();

        return response()->json(['message' => 'Lead deleted successfully.']);
    }
}
