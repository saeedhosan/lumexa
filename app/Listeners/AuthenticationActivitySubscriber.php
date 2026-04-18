<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;

class AuthenticationActivitySubscriber
{
    public function handleLogin(Login $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        activity('authentication')->causedBy($event->user)->log('Logged in');
    }

    public function handleLogout(Logout $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        activity('authentication')->causedBy($event->user)->log('Logged out');
    }

    public function handleRegistered(Registered $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        activity('authentication')->causedBy($event->user)->log('Registered');
    }

    public function subscribe(): array
    {
        return [
            Login::class      => 'handleLogin',
            Logout::class     => 'handleLogout',
            Registered::class => 'handleRegistered',
        ];
    }
}
