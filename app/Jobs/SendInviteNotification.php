<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\User;
use App\Notifications\CompanyInviteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendInviteNotification implements ShouldQueue
{
    use Queueable;

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
