<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\LeadCreated;

class LogLeadCreated
{
    public function handle(LeadCreated $event): void
    {
        activity()
            ->on($event->lead)
            ->withProperties([
                'title'   => $event->lead->title,
                'company' => $event->lead->company?->name,
            ])
            ->log('Lead created via API');
    }
}
