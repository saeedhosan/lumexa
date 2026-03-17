<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('play', 'Your deub or test code play')]
class PlayCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
