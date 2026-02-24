<?php

declare(strict_types=1);

namespace App\Logging;

use Monolog\Logger;

class DatabaseLogger
{
    /**
     * Return a Monolog instance
     *
     * @param  array<string, mixed>  $config
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('database');
        $logger->pushHandler(new DatabaseHandler());

        return $logger;
    }
}
