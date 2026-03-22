<?php

declare(strict_types=1);

use App\Logging\AddCallerIntrospection;
use App\Logging\DatabaseHandler;
use App\Logging\DatabaseLogger;
use App\Models\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Monolog\Level;
use Monolog\Logger as MonologLogger;
use Monolog\LogRecord;
use Monolog\Processor\ClosureContextProcessor;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('DatabaseLogger - returns monolog logger with database handler', function (): void {
    $logger = (new DatabaseLogger())([]);

    expect($logger)
        ->toBeInstanceOf(MonologLogger::class)
        ->and($logger->getName())->toBe('database')
        ->and($logger->getHandlers())->toHaveCount(1)
        ->and($logger->getHandlers()[0])->toBeInstanceOf(DatabaseHandler::class);
});

it('DatabaseHandler - writes log record to database', function (): void {
    $handler = new DatabaseHandler();

    $record = new LogRecord(
        datetime: new DateTimeImmutable(),
        channel: 'database',
        level: Level::Info,
        message: 'Test log message',
        context: [],
        extra: []
    );

    $handler->handle($record);

    $log = Log::query()->latest()->first();

    expect($log)
        ->not->toBeNull()
        ->and($log->level)->toBe('INFO')
        ->and($log->message)->toBe('Test log message');
});

it('DatabaseHandler - writes log with user_id from context', function (): void {
    $user = User::factory()->create();

    $handler = new DatabaseHandler();

    $record = new LogRecord(
        datetime: new DateTimeImmutable(),
        channel: 'database',
        level: Level::Warning,
        message: 'Test message',
        context: ['user_id' => $user->id],
        extra: []
    );

    $handler->handle($record);

    expect(Log::query()->latest()->first()->user_id)->toBe($user->id);
});

it('AddCallerIntrospection - adds processors to monolog logger', function (): void {
    $illuminateLogger = new Illuminate\Log\Logger(new MonologLogger('test'));
    $processor        = new AddCallerIntrospection();

    $processor($illuminateLogger);

    $monolog = $illuminateLogger->getLogger();

    expect($monolog->popProcessor())->toBeInstanceOf(ClosureContextProcessor::class);
});

it('AddCallerIntrospection - handles non-monolog loggers gracefully', function (): void {
    $nonMonologLogger = new class
    {
        public function getLogger(): string
        {
            return 'not-a-monolog-logger';
        }
    };

    $processor = new AddCallerIntrospection();

    expect(fn () => $processor($nonMonologLogger))->not->toThrow(Throwable::class);
});
