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
        $perPage = min($request->query('per_page', 15), 100);

        $leads = Lead::query()
            ->with('company')
            ->latest()
            ->paginate($perPage);

        return new LeadCollection($leads);
    }

    public function show(int $id): JsonResponse
    {
        $lead = Lead::query()
            ->with('company', 'leadList')
            ->findOrFail($id);

        return response()->json([
            'data' => new LeadResource($lead),
        ]);
    }
}
