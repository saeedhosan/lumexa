<div>
    <div class="flex items-center justify-between mb-6">
        <flux:input
            type="search"
            wire:model="search"
            placeholder="Search leads..."
            icon="magnifying-glass"
            class="max-w-md"
        />
        <flux:button :href="route('app.leads.index')" icon="arrow-left" wire:navigate>
            Back
        </flux:button>
    </div>

    <div class="overflow-x-auto">
        <flux:table :paginate="$this->leadLists">
            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                <flux:table.column>Phone</flux:table.column>
                <flux:table.column>Address</flux:table.column>
                <flux:table.column
                    sortable
                    :sorted="$sortBy === 'status'"
                    :direction="$sortDirection"
                    wire:click="sort('status')"
                >
                    Status
                </flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->leadLists as $leadList)
                    <flux:table.row :key="$leadList->id">
                        <flux:table.cell>
                            {{ $leadList->name }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $leadList->email }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $leadList->phone }}
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $leadList->full_address }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" :color="$leadList->status->color()">
                                {{ $leadList->status->label() }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    wire:click="delete('{{ $leadList->getRouteKey() }}')"
                                />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="6" class="text-center text-zinc-500">
                            No lead lists found.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>
</div>
