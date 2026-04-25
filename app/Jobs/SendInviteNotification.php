<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\User;
use App\Notifications\CompanyInviteNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendInviteNotification implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $companyName,
        public string $invitedBy
    ) {}

    public function handle(): void
    {
        $this->user->notify(new CompanyInviteNotification(
            $this->companyName,
            $this->invitedBy
        ));
    }
}
