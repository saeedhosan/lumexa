<x-layouts::app title="Activities">
    <div class="space-y-6">
        <div class="flex items-center justify-between mb-6">
            <form method="GET" action="{{ route('app.activities.index') }}" class="flex gap-4">
                <flux:input
                    name="search"
                    placeholder="Search activities..."
                    value="{{ $search ?? '' }}"
                    icon="magnifying-glass"
                    class="max-w-md"
                />
                <flux:button type="submit">Search</flux:button>
                @if ($search)
                    <flux:button variant="ghost" href="{{ route('app.activities.index') }}">Clear
                    </flux:button>
                @endif
            </form>
            <form method="POST" action="{{ route('app.activities.destroy', 'all') }}"
                onsubmit="return confirm('Are you sure you want to clear all your activity logs?');"
            >
                @csrf
                @method('DELETE')
                <flux:button type="submit" icon="trash">
                    Clear Logs
                </flux:button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <flux:table :paginate="$activities">
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Message</flux:table.column>
                    <flux:table.column>Event</flux:table.column>
                    <flux:table.column>Subject</flux:table.column>
                    <flux:table.column>Date</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($activities as $activity)
                        <flux:table.row :key="$activity->id">
                            <flux:table.cell>
                                <div class="flex items-center gap-2">
                                    <flux:avatar size="sm"
                                        :name="$activity->causer?->name ?? 'System'"
                                    />
                                    <span>{{ $activity->causer?->name ?? 'System' }}</span>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $activity->description }}
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge size="sm">
                                    {{ $activity->event ?? '-' }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                @if ($activity->subject)
                                    <span class="text-zinc-600 dark:text-zinc-400">
                                        {{ class_basename($activity->subject_type) }}
                                        #{{ $activity->subject_id }}
                                    </span>
                                @else
                                    -
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $activity->created_at->diffForHumans() }}
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center py-8 text-zinc-500">
                                No activities found.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
</x-layouts::app>
