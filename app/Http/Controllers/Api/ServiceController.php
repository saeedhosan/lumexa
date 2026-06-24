<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCollection;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $perPage = min((int) $request->query('per_page', 15), 100);

        $services = Service::query()
            ->latest()
            ->paginate($perPage);

        return new ServiceCollection($services);
    }

    public function show(int $id): JsonResource
    {
        $service = Service::query()->findOrFail($id);

        return new ServiceResource($service);
    }
}
