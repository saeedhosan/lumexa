<x-layouts::app title="Invite Users">

    <div class="max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="xl">Invite Users</flux:heading>
            <flux:button wire:navigate :href="route('admin.invites.create')">
                Send Invitation
            </flux:button>
        </div>

        <flux:card>
            <flux:heading level="2" size="sm" class="mb-4">Pending Invitations</flux:heading>

            @if ($invites->isEmpty())
                <div class="text-center py-8">
                    <flux:icon.envelope variant="outline" class="mx-auto h-12 w-12 text-zinc-300 dark:text-zinc-600" />
                    <flux:heading level="3" size="sm" class="mt-4 text-zinc-500">No invitations</flux:heading>
                    <flux:text size="sm" class="text-zinc-400">Send your first invitation to get started.</flux:text>
                </div>
            @else
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Email</flux:table.column>
                        <flux:table.column>Company</flux:table.column>
                        <flux:table.column>Role</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                        <flux:table.column>Sent</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($invites as $invite)
                            <flux:table.row :key="$invite->id">
                                <flux:table.cell>
                                    <flux:text size="sm">{{ $invite->email }}</flux:text>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:text size="sm">{{ $invite->company->name }}</flux:text>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge size="sm" variant="subtle">{{ ucfirst($invite->role) }}</flux:badge>
                                </flux:table.cell>
                                <flux:table.cell>
                                    @if ($invite->accepted_at)
                                        <flux:badge color="green" size="sm">Accepted</flux:badge>
                                    @else
                                        <flux:badge color="yellow" size="sm">Pending</flux:badge>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:text size="sm" class="text-zinc-500">
                                        {{ $invite->created_at->format('M d, Y') }}
                                    </flux:text>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>

                <div class="mt-4">
                    {{ $invites->links() }}
                </div>
            @endif
        </flux:card>
    </div>

</x-layouts::app>