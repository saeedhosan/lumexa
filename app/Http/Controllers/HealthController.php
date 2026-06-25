<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthController
{
    public function __invoke(Request $request): JsonResponse
    {
        $database = $this->checkDatabase();
        $cache    = $this->checkCache();

        $status = $database && $cache ? 'ok' : 'degraded';
        $code   = $database && $cache ? 200 : 503;

        return response()->json([
            'status'      => $status,
            'environment' => app()->environment(),
            'app_name'    => config('app.name'),
            'debug'       => config('app.debug'),
            'checks'      => [
                'database' => $database,
                'cache'    => $cache,
            ],
            'timestamp' => now()->toIsoString(),
        ], $code);
    }

    private function checkDatabase(): bool
    {
        try {
            DB::connection()->getPdo();

            return true;
        } catch (Throwable) {
            return false;
        }
    }

    private function checkCache(): bool
    {
        try {
            $key = 'health:'.now()->timestamp;
            Cache::store(config('cache.default'))->put($key, true, 1);
            $result = Cache::store(config('cache.default'))->get($key);
            Cache::store(config('cache.default'))->forget($key);

            return $result === true;
        } catch (Throwable) {
            return false;
        }
    }
}
