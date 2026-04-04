<div>
    <div class="flex items-center justify-between mb-6">
        <flux:input
            type="search"
            wire:model="search"
            placeholder="Search leads..."
            icon="magnifying-glass"
            class="max-w-md"
        />
        <livewire:leads.lead-import />
    </div>

    <div class="overflow-x-auto">
        <flux:table :paginate="$this->leads">
            <flux:table.columns>
                <flux:table.column
                    sortable
                    :sorted="$sortBy === 'title'"
                    :direction="$sortDirection"
                    wire:click="sort('title')"
                >
                    Title
                </flux:table.column>
                <flux:table.column
                    sortable
                    :sorted="$sortBy === 'status'"
                    :direction="$sortDirection"
                    wire:click="sort('status')"
                >
                    Status
                </flux:table.column>
                <flux:table.column
                    sortable
                    :sorted="$sortBy === 'created_at'"
                    :direction="$sortDirection"
                    wire:click="sort('created_at')"
                >
                    Created at
                </flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($this->leads as $lead)
                    <flux:table.row :key="$lead->id">
                        <flux:table.cell>
                            <a href="{{ route('app.leads.show', $lead) }}" class="hover:underline">
                                {{ $lead->title }}
                            </a>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" :color="$lead->status->color()">
                                {{ $lead->status->label() }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            {{ $lead->created_at->format('M d, Y') }}
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex items-center gap-2">
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    :href="route('app.leads.show', $lead)"
                                    icon="eye"
                                />
                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    wire:confirm="{{ __('Are you sure want to delete?') }}"
                                    wire:click="delete('{{ $lead->getRouteKey() }}')"
                                />
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="4" class="text-center text-zinc-500">
                            No leads found.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>
</div>
