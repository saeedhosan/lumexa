<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Invite $invite,
        public readonly ?string $signedUrl = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $acceptUrl = $this->signedUrl ?? url("/invite/{$this->invite->id}/accept");

        return (new MailMessage)
            ->subject("You've been invited to join {$this->invite->company->name}")
            ->greeting('Hello!')
            ->line("{$this->invite->inviter->name} has invited you to join **{$this->invite->company->name}** as an **{$this->invite->role}**.")
            ->action('Accept Invitation', $acceptUrl)
            ->line('This invitation expires in 7 days.')
            ->salutation('Best regards, '.config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'invite_id' => $this->invite->id,
            'company'   => $this->invite->company->name,
        ];
    }
}
