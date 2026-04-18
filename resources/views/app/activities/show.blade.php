<x-layouts::app title="Activity Details">
    @if($activity)
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading>Activity Details</flux:heading>
            <flux:button variant="ghost" href="{{ route('app.activities.index') }}">Back</flux:button>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow border border-zinc-200 dark:border-zinc-800">
            <div class="p-6 space-y-4">
                <div>
                    <flux:heading size="lg">{{ $activity->description }}</flux:heading>
                </div>

                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Date</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $activity->created_at->format('Y-m-d H:i:s') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">User</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $activity->causer?->name ?? 'System' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Subject Type</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $activity->subject_type ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Subject ID</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $activity->subject_id ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Event</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $activity->event ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">IP Address</dt>
                        <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">
                            {{ $activity->getProperty('ip') ?? '-' }}
                        </dd>
                    </div>
                </dl>

                @if($activity->properties && $activity->properties->isNotEmpty())
                    <div>
                        <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Properties</dt>
                        <dd class="mt-2">
                            <pre class="text-sm bg-zinc-100 dark:bg-zinc-800 p-4 rounded-lg overflow-x-auto">{{ json_encode($activity->properties->toArray(), JSON_PRETTY_PRINT) }}</pre>
                        </dd>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading>Activity Not Found</flux:heading>
            <flux:button variant="ghost" href="{{ route('app.activities.index') }}">Back</flux:button>
        </div>
    </div>
    @endif
</x-layouts::app>