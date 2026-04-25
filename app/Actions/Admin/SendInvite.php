<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Company;
use App\Models\Invite;
use App\Notifications\InviteNotification;
use Illuminate\Support\Facades\URL;

class SendInvite
{
    public function handle(Company $company, string $email, string $role, int $invitedBy): Invite
    {
        $invite = Invite::create([
            'company_id' => $company->id,
            'invited_by' => $invitedBy,
            'email'      => $email,
            'role'       => $role,
        ]);

        $signedUrl = URL::temporarySignedRoute(
            'invite.accept',
            now()->addDays(7),
            ['invite' => $invite->id]
        );

        $invite->notify(new InviteNotification($invite, $signedUrl));

        return $invite;
    }
}
