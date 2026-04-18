<x-layouts::app title="User Details">

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading>User Details</flux:heading>
            <div class="flex items-center gap-2">
                @can('update', $user)
                    <flux:button :href="route('admin.users.edit', $user)" variant="primary">Edit
                    </flux:button>
                @endcan
                <flux:button :href="route('admin.users.index')" wire:navigate variant="ghost">Back
                </flux:button>
            </div>
        </div>

        <div
            class="bg-white dark:bg-zinc-900 rounded-lg shadow border border-zinc-200 dark:border-zinc-800">
            <div class="p-6 space-y-4">
                <div class="flex items-center gap-4">
                    <flux:avatar size="lg" :name="$user->name" />
                    <div>
                        <flux:heading size="lg">{{ $user->name }}</flux:heading>
                        <flux:text>{{ $user->email }}</flux:text>
                    </div>
                </div>

                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Status</dt>
                        <dd class="mt-1">
                            <flux:badge>{{ $user->status->value }}</flux:badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Type</dt>
                        <dd class="mt-1">
                            <flux:badge>{{ $user->type->value }}</flux:badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Company
                        </dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $user->currentCompany?->name ?? 'No company' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Email
                            Verified</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $user->email_verified_at?->format('M j, Y g:i A') ?? 'Not verified' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Created
                        </dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $user->created_at?->format('M j, Y g:i A') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

</x-layouts::app>
