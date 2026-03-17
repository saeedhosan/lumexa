<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?User $user = null;

    public bool $showDeleteModal = false;

    #[On('delete-user')]
    public function showConfirm(int $userId): void
    {
        $this->user            = User::findOrFail($userId);
        $this->showDeleteModal = true;
    }

    public function deleteUser(): void
    {
        if ($this->user === null) {
            return;
        }

        DB::transaction(function () {
            $this->user->delete();
        });

        $this->showDeleteModal = false;
        $this->user            = null;

        $this->dispatch('user-deleted');
    }
}
?>

<div>
    <flux:modal wire:model="showDeleteModal" class="w-full max-w-md">
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <flux:heading size="lg">Delete User</flux:heading>
            </div>

            <flux:text>
                Are you sure you want to delete <strong>{{ $this->user?->name }}</strong>? This action cannot be undone.
            </flux:text>

            <div class="flex justify-end gap-3">
                <flux:button variant="ghost" wire:click="$set('showDeleteModal', false)">
                    Cancel
                </flux:button>
                <flux:button variant="danger" wire:click="deleteUser">
                    Delete
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
