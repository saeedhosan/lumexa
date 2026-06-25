<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Lead;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadCreated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public Lead $lead
    ) {}

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('company.'.$this->lead->company_id);
    }

    public function broadcastWith(): array
    {
        return [
            'id'    => $this->lead->id,
            'title' => $this->lead->title,
        ];
    }
}
