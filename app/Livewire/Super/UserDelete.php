<?php

declare(strict_types=1);

namespace App\Livewire\Super;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class UserDelete extends Component
{
    #[On('user-delete')]
    public function delete(User $user)
    {
        if ($user->id === Auth::id()) {

            $this->dispatch('warning', 'You cannot delete your own account.');

            return;
        }

        $user->delete();

        $this->dispatch('success', 'User deleted successfully.');
    }

    public function render()
    {
        return '<div></div>';
    }
}
