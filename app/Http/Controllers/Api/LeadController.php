<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min($request->query('per_page', 15), 100);

        $leads = Lead::query()
            ->with('company')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'data' => $leads->items(),
            'meta' => [
                'current_page' => $leads->currentPage(),
                'last_page'    => $leads->lastPage(),
                'per_page'     => $leads->perPage(),
                'total'        => $leads->total(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $lead = Lead::query()->with('company', 'leadList')->findOrFail($id);

        return response()->json([
            'data' => $lead,
        ]);
    }
}
