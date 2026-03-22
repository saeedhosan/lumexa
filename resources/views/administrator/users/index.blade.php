<x-layouts::app title="All users">

    <div class="flex items-center justify-between mb-6">
        <form
            method="GET"
            action="{{ route('super.users.index') }}"
            class="flex-1 max-w-md"
            x-data="{ search: '{{ $search ?? '' }}' }"
            @submit.prevent
        >
            <flux:input
                type="search"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Search users..."
                icon="magnifying-glass"
                x-model="search"
                @change="$el.closest('form').submit()"
            />
        </form>
        <div>
            <flux:button variant="primary" :href="route('super.users.create')">
                Add new user
            </flux:button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <flux:table class="min-w-full">
            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                <flux:table.column>Company</flux:table.column>
                <flux:table.column>Type</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($users as $user)
                    <flux:table.row :key="$user->id">
                        <flux:table.cell>
                            <flux:avatar size="sm" :name="$user->name" />
                            <span class="ml-3">{{ $user->name }}</span>
                        </flux:table.cell>
                        <flux:table.cell>{{ $user->email }}</flux:table.cell>
                        <flux:table.cell>{{ $user->currentCompany?->name }}</flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm">
                                {{ $user->type->value }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    :href="route('super.users.show', $user)"
                                    icon="eye"
                                />
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    :href="route('super.users.edit', $user)"
                                    icon="pencil"
                                />
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    onclick="confirm('Are you sure want to delete?') && Livewire.dispatch('user-delete', { user: {{ $user->id }} })"
                                />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <livewire:administrator.user-delete />

</x-layouts::app>
