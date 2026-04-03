<x-layouts::app title="Lead Details">
    @livewire(\App\Livewire\App\Leads\LeadListDelete::class)

    <div class="flex items-center justify-between mb-6">
        <form
            method="GET"
            action="{{ route('app.leads.show', $lead) }}"
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
        <flux:button :href="route('app.leads.index')" icon="arrow-left">
            Back
        </flux:button>
    </div>

    <div class="overflow-x-auto">
        <flux:table class="min-w-full">
            <flux:table.columns>
                <flux:table.column>Name</flux:table.column>
                <flux:table.column>Email</flux:table.column>
                <flux:table.column>Phone</flux:table.column>
                <flux:table.column>Address</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Actions</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse ($leadLists as $leadList)
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
                                    onclick="confirm('Are you sure you want to delete?') && Livewire.dispatch('lead-list-delete', { leadList: {{ $leadList->id }} })"
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

</x-layouts::app>
