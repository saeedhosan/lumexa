<x-layouts::app title="Leads">

    @livewire(\App\Livewire\App\Leads\LeadDelete::class)

    <div class="flex items-center justify-between mb-6">
        <form
            method="GET"
            action="{{ route('app.leads.index') }}"
            class="flex-1 max-w-md"
            x-data="{ search: '{{ $search ?? '' }}' }"
            @submit.prevent
        >
            <flux:input
                type="search"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Search leads..."
                icon="magnifying-glass"
                x-model="search"
                @change="$el.closest('form').submit()"
            />
        </form>
        <div>
            <flux:button variant="primary" icon="arrow-down-tray">
                Import leads
            </flux:button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <flux:table class="min-w-full">
            <flux:table.columns>
                <flux:table.column column="title" :sortable="true"
                    :direction="$sort === 'title' ? $direction : null"
                >
                    Title
                </flux:table.column>
                <flux:table.column column="status" :sortable="true"
                    :direction="$sort === 'status' ? $direction : null"
                >
                    Status
                </flux:table.column>
                <flux:table.column column="created_at" :sortable="true"
                    :direction="$sort === 'created_at' ? $direction : null"
                >
                    Created at
                </flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($leads as $lead)
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
                                    onclick="confirm('Are you sure want to delete?') && Livewire.dispatch('lead-delete', '{{ $lead->getRouteKey() }}')"
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

    <div class="mt-4">
        {{ $leads->links() }}
    </div>

</x-layouts::app>
