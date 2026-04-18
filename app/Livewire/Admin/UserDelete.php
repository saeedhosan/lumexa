<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class UserDelete extends Component
{
    #[On('user-delete')]
    public function delete(User $user): void
    {

        if ($user->id === Auth::id()) {

            Flux::toast(__('You cannot delete your own account.'), variant: 'warning');

            return;
        }

        $this->authorize('delete', $user);

        $user->delete();

        Flux::toast(__('User deleted successfully.'), variant: 'success');
    }

    public function render(): string
    {
        return '<div></div>';
    }
}
