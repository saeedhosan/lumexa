<x-layouts::app title="Company Users">

    <div class="max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="xl">Company Users</flux:heading>
            <flux:button wire:navigate :href="route('admin.invites.create')">
                Add User
            </flux:button>
        </div>

        <flux:card>
            <flux:heading level="2" size="sm" class="mb-4">All Users</flux:heading>

            @if ($users->isEmpty())
                <div class="text-center py-8">
                    <flux:icon.users variant="outline" class="mx-auto h-12 w-12 text-zinc-300 dark:text-zinc-600" />
                    <flux:heading level="3" size="sm" class="mt-4 text-zinc-500">No users</flux:heading>
                    <flux:text size="sm" class="text-zinc-400">Add your first user to get started.</flux:text>
                </div>
            @else
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Name</flux:table.column>
                        <flux:table.column>Email</flux:table.column>
                        <flux:table.column>Role</flux:table.column>
                        <flux:table.column>Joined</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($users as $user)
                            <flux:table.row :key="$user->id">
                                <flux:table.cell>
                                    <flux:text size="sm">{{ $user->name }}</flux:text>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:text size="sm">{{ $user->email }}</flux:text>
                                </flux:table.cell>
                                <flux:table.cell>
                                    @foreach($user->companies as $company)
                                        <flux:badge size="sm" variant="subtle">{{ $company->pivot->role }}</flux:badge>
                                    @endforeach
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:text size="sm" class="text-zinc-500">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </flux:text>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            @endif
        </flux:card>
    </div>

</x-layouts::app>