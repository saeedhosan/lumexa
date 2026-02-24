<?php

declare(strict_types=1);

namespace App\Logging;

use Illuminate\Log\Logger as IlluminateLogger; // Laravel wrapper
use Monolog\Level;
use Monolog\Logger as MonologLogger;
use Monolog\Processor\ClosureContextProcessor;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\WebProcessor;

class AddCallerIntrospection
{
    /**
     * Classes/partials to skip when finding the caller.
     *
     * @var array<string>
     */
    private array $skipClassesPartials = [
        'Monolog\\',
        'Illuminate\\',
        'Symfony\\',
        'App\\Logging\\',
        'Barryvdh\\Debugbar\\',     // ⬅️ skip Debugbar
        'Facade\\Ignition\\',       // ⬅️ skip Ignition (older Laravel)
        'Laravel\\SerializableClosure\\',
        'Psy\\',                    // tinker
    ];

    public function __invoke(IlluminateLogger $logger): void
    {
        $monolog = $logger->getLogger();

        if (! $monolog instanceof MonologLogger) {
            return; // avoid errors for non-Monolog loggers
        }

        // Capture file, line, class and function of the log call
        $monolog->pushProcessor(new IntrospectionProcessor(Level::Debug, $this->skipClassesPartials));
        $monolog->pushProcessor(new WebProcessor());
        $monolog->pushProcessor(new PsrLogMessageProcessor());
        $monolog->pushProcessor(new ClosureContextProcessor());
    }
}
