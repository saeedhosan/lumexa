<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyInviteNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $companyName,
        public string $invitedBy
    ) {}

    public function via(User $user): array
    {
        return ['mail'];
    }

    public function toMail(User $user): MailMessage
    {
        return (new MailMessage)
            ->subject("You've been invited to {$this->companyName}")
            ->greeting("Hello {$user->name}!")
            ->line("{$this->invitedBy} has invited you to join {$this->companyName} on Lumexa.")
            ->action('View Invitation', url('/app/companies'))
            ->line('Thank you for using Lumexa!');
    }
}