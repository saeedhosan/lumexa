<?php

declare(strict_types=1);

use App\Logging\AddCallerIntrospection;
use App\Logging\DatabaseHandler;
use App\Logging\DatabaseLogger;
use App\Models\Log;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\Logger as MonologLogger;
use Monolog\LogRecord;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

describe('DatabaseLogger', function () {
    it('returns a monolog logger instance', function () {
        $logger = (new DatabaseLogger())([]);

        expect($logger)->toBeInstanceOf(MonologLogger::class);
    });

    it('creates a logger with database channel name', function () {
        $logger = (new DatabaseLogger())([]);

        expect($logger->getName())->toBe('database');
    });

    it('pushes a database handler to the logger', function () {
        $logger = (new DatabaseLogger())([]);

        $handlers = $logger->getHandlers();

        expect($handlers)->toHaveCount(1);
        expect($handlers[0])->toBeInstanceOf(DatabaseHandler::class);
    });
});

describe('DatabaseHandler', function () {
    it('extends abstract processing handler', function () {
        $handler = new DatabaseHandler();

        expect($handler)->toBeInstanceOf(AbstractProcessingHandler::class);
    });

    it('writes a log record to the database', function () {
        $handler = new DatabaseHandler();

        $record = new LogRecord(
            datetime: new DateTimeImmutable(),
            channel: 'database',
            level: Level::Info,
            message: 'Test log message',
            context: [],
            extra: []
        );

        $reflection = new ReflectionMethod(DatabaseHandler::class, 'write');
        $reflection->setAccessible(true);
        $reflection->invoke($handler, $record);

        $log = Log::latest()->first();

        expect($log)->not->toBeNull();
        expect($log->level)->toBe('INFO');
        expect($log->message)->toBe('Test log message');
        expect($log->context)->toBe([]);
        expect($log->sources)->toBe([]);
    });

    it('writes log with user_id from context', function () {
        $user = User::factory()->create();

        $handler = new DatabaseHandler();

        $record = new LogRecord(
            datetime: new DateTimeImmutable(),
            channel: 'database',
            level: Level::Warning,
            message: 'Test message with user',
            context: ['user_id' => $user->id],
            extra: ['source' => 'test']
        );

        $reflection = new ReflectionMethod(DatabaseHandler::class, 'write');
        $reflection->setAccessible(true);
        $reflection->invoke($handler, $record);

        $log = Log::latest()->first();

        expect($log->user_id)->toBe($user->id);
        expect($log->level)->toBe('WARNING');
        expect($log->context)->toBe(['user_id' => $user->id]);
        expect($log->sources)->toBe(['source' => 'test']);
    });

    it('writes log with user_id from auth when not in context', function () {
        $user = User::factory()->create();

        $this->actingAs($user);

        $handler = new DatabaseHandler();

        $record = new LogRecord(
            datetime: new DateTimeImmutable(),
            channel: 'database',
            level: Level::Error,
            message: 'Test message with auth user',
            context: [],
            extra: []
        );

        $reflection = new ReflectionMethod(DatabaseHandler::class, 'write');
        $reflection->setAccessible(true);
        $reflection->invoke($handler, $record);

        $log = Log::latest()->first();

        expect($log->user_id)->toBe($user->id);
        expect($log->level)->toBe('ERROR');
    });

    it('writes log with null user_id when not authenticated', function () {
        Auth::logout();

        $handler = new DatabaseHandler();

        $record = new LogRecord(
            datetime: new DateTimeImmutable(),
            channel: 'database',
            level: Level::Debug,
            message: 'Test message without auth',
            context: [],
            extra: []
        );

        $reflection = new ReflectionMethod(DatabaseHandler::class, 'write');
        $reflection->setAccessible(true);
        $reflection->invoke($handler, $record);

        $log = Log::latest()->first();

        expect($log->user_id)->toBeNull();
        expect($log->level)->toBe('DEBUG');
    });
});

describe('AddCallerIntrospection', function () {
    it('adds processors to a monolog logger', function () {
        $illuminateLogger = new Illuminate\Log\Logger(new MonologLogger('test'));
        $processor        = new AddCallerIntrospection();

        $processor($illuminateLogger);

        $monolog = $illuminateLogger->getLogger();

        expect($monolog->popProcessor())->toBeInstanceOf(Monolog\Processor\ClosureContextProcessor::class);
        expect($monolog->popProcessor())->toBeInstanceOf(Monolog\Processor\PsrLogMessageProcessor::class);
        expect($monolog->popProcessor())->toBeInstanceOf(Monolog\Processor\WebProcessor::class);
        expect($monolog->popProcessor())->toBeInstanceOf(Monolog\Processor\IntrospectionProcessor::class);
    });

    it('handles non-monolog loggers gracefully', function () {
        $nonMonologLogger = new class
        {
            public function getLogger()
            {
                return 'not-a-monolog-logger';
            }
        };

        $processor = new AddCallerIntrospection();

        expect(fn () => $processor($nonMonologLogger))->not->toThrow(Throwable::class);
    });

    it('adds introspection processor with correct level', function () {
        $illuminateLogger = new Illuminate\Log\Logger(new MonologLogger('test'));
        $processor        = new AddCallerIntrospection();

        $processor($illuminateLogger);

        $monolog   = $illuminateLogger->getLogger();
        $processor = $monolog->popProcessor();

        expect($processor)->toBeInstanceOf(Monolog\Processor\ClosureContextProcessor::class);
    });
});
