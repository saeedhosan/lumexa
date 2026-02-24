<?php

declare(strict_types=1);

namespace App\Logging;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

class DatabaseHandler extends AbstractProcessingHandler
{
    /**
     * Write a log record to the database.
     */
    protected function write(LogRecord $record): void
    {
        Log::query()->create([
            'level'   => $record->level->getName(),
            'message' => $record->message,
            'context' => $record->context,
            'user_id' => $record->context['user_id'] ?? Auth::id(),
            'sources' => $record->extra,
        ]);
    }
}
