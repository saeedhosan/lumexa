<div>
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <flux:input
                type="search"
                wire:model.live="search"
                placeholder="Search users..."
                icon="magnifying-glass"
                class="w-64"
            />
            <flux:select wire:model.live="companyId" class="w-48">
                <flux:select.option value="">All Companies</flux:select.option>
                @foreach ($this->companies as $company)
                    <flux:select.option value="{{ $company->id }}">{{ $company->name }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
        <flux:button variant="primary" wire:navigate :href="route('admin.users.create')">
            Add new user
        </flux:button>
    </div>

    <div class="overflow-x-auto">
        <flux:table class="min-w-full">
            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                <flux:table.column>Companies</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Type</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @foreach ($this->users as $user)
                    <flux:table.row :key="$user->id">
                        <flux:table.cell>
                            <flux:avatar size="sm" :name="$user->name" />
                            <span class="ml-3">{{ $user->name }}</span>
                        </flux:table.cell>
                        <flux:table.cell>{{ $user->email }}</flux:table.cell>
                        <flux:table.cell>
                            @forelse($user->companies as $company)
                                <flux:badge size="sm" variant="subtle" class="mr-1">{{ $company->name }}</flux:badge>
                            @empty
                                <span class="text-zinc-400">-</span>
                            @endforelse
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" :color="$user->status->color()">
                                {{ $user->status->label() }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm">{{ ucfirst($user->type->value) }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    :href="route('admin.users.show', $user)"
                                    icon="eye"
                                    wire:navigate
                                />
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    :href="route('admin.users.edit', $user)"
                                    icon="pencil"
                                    wire:navigate
                                />
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    wire:click="delete({{ $user->id }})"
                                    wire:confirm="Are you sure want to delete?"
                                />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            </flux:table.rows>
        </flux:table>
    </div>

    <div class="mt-4">
        {{ $this->users->links() }}
    </div>
</div>