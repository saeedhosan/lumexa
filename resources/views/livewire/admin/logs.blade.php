<div class="space-y-4">
    <div class="flex items-center justify-between">
        <flux:heading level="1">Activity Logs</flux:heading>
        <flux:badge>{{ $activities->total() }} total</flux:badge>
    </div>

    <flux:card>
        <div class="flex items-center gap-4">
            <flux:input
                wire:model.live.debounce.300ms="search"
                placeholder="Search descriptions..."
                icon="magnifying-glass"
                class="w-64"
            />

            <flux:select wire:model.live="filterLogName" class="w-40">
                <flux:select.option value="">All Logs</flux:select.option>
                @foreach ($logNames as $logName)
                    <flux:select.option value="{{ $logName }}">{{ $logName }}</flux:select.option>
                @endforeach
            </flux:select>

            @if ($search || $filterLogName)
                <flux:button size="sm" variant="ghost" wire:click="resetFilters">
                    Clear
                </flux:button>
            @endif

            <flux:spacer />

            <flux:button size="sm" variant="subtle" wire:click="refresh" icon="arrow-path" class="px-2" />
        </div>

        <flux:separator class="my-4" />

        @if ($activities->isEmpty())
            <div class="text-center py-12">
                <flux:icon.clock variant="outline" class="mx-auto h-12 w-12 text-zinc-300 dark:text-zinc-600" />
                <flux:heading level="3" size="sm" class="mt-4 text-zinc-500">No activity logs found</flux:heading>
            </div>
        @else
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="w-24">Log</flux:table.column>
                    <flux:table.column>Description</flux:table.column>
                    <flux:table.column>Subject</flux:table.column>
                    <flux:table.column>User</flux:table.column>
                    <flux:table.column>Date</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($activities as $activity)
                        <flux:table.row :key="$activity->id">
                            <flux:table.cell>
                                <flux:badge size="sm" variant="subtle">{{ $activity->log_name ?? 'System' }}</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:heading level="3" size="sm">{{ $activity->description }}</flux:heading>
                            </flux:table.cell>
                            <flux:table.cell>
                                @if ($activity->subject_type && $activity->subject_id)
                                    <flux:text size="sm">
                                        {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                                    </flux:text>
                                @else
                                    <flux:text size="sm" class="text-zinc-400">-</flux:text>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center gap-2">
                                    <flux:avatar :name="$activity->causer?->name ?? 'System'" size="sm" />
                                    <flux:text size="sm">{{ $activity->causer?->name ?? 'System' }}</flux:text>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:text size="sm" class="text-zinc-500">
                                    {{ $activity->created_at->format('M d, Y H:i') }}
                                </flux:text>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>

            <div class="mt-4">
                {{ $activities->links() }}
            </div>
        @endif
    </flux:card>
</div>