<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading level="1">Dashboard</flux:heading>

        <div class="flex rounded-md shadow-sm" role="group">
            <flux:button :variant="$daysRange === 7 ? 'primary' : 'ghost'" size="sm" wire:click="$set('daysRange', 7)">7 Days</flux:button>
            <flux:button :variant="$daysRange === 30 ? 'primary' : 'ghost'" size="sm" wire:click="$set('daysRange', 30)">30 Days</flux:button>
            <flux:button :variant="$daysRange === 90 ? 'primary' : 'ghost'" size="sm" wire:click="$set('daysRange', 90)">90 Days</flux:button>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <flux:card class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent"></div>
            <div class="relative">
                <flux:heading level="2" size="lg">{{ number_format($totalLeads) }}</flux:heading>
                <flux:text>Total Leads</flux:text>
                <flux:badge class="mt-2">{{ $avgLeadsPerDay }}/day avg</flux:badge>
            </div>
        </flux:card>

        <flux:card class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/5 to-transparent"></div>
            <div class="relative">
                <flux:heading level="2" size="lg" class="text-yellow-600">{{ number_format($pendingLeads) }}</flux:heading>
                <flux:text>Pending</flux:text>
                @if ($thisWeekLeads > 0 || $lastWeekLeads > 0)
                    @php
                        $trend = $lastWeekLeads > 0 ? round((($thisWeekLeads - $lastWeekLeads) / $lastWeekLeads) * 100) : 0;
                        $trendColor = $trend >= 0 ? 'green' : 'red';
                    @endphp
                    <flux:badge :color="$trendColor" class="mt-2">
                        {{ $trend >= 0 ? '+' : '' }}{{ $trend }}% this week
                    </flux:badge>
                @endif
            </div>
        </flux:card>

        <flux:card class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-transparent"></div>
            <div class="relative">
                <flux:heading level="2" size="lg" class="text-green-600">{{ number_format($approvedLeads) }}</flux:heading>
                <flux:text>Approved</flux:text>
                <flux:badge color="green" class="mt-2">{{ $conversionRate }}% rate</flux:badge>
            </div>
        </flux:card>

        <flux:card class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-transparent"></div>
            <div class="relative">
                <flux:heading level="2" size="lg" class="text-red-600">{{ number_format($rejectedLeads) }}</flux:heading>
                <flux:text>Rejected</flux:text>
                <flux:badge color="red" class="mt-2">
                    {{ $totalLeads > 0 ? round(($rejectedLeads / $totalLeads) * 100) : 0 }}% of total
                </flux:badge>
            </div>
        </flux:card>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <flux:card class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <flux:heading level="2" size="sm">Recent Leads</flux:heading>
                <flux:button size="sm" variant="outline" tag="a" :href="route('app.leads.create')">
                    Add Lead
                </flux:button>
            </div>

            @if ($recentLeads->isEmpty())
                <div class="text-center py-8">
                    <flux:icon.plus variant="outline" class="mx-auto h-12 w-12 text-zinc-300 dark:text-zinc-600" />
                    <flux:heading level="3" size="sm" class="mt-4 text-zinc-500">No leads yet</flux:heading>
                    <flux:text size="sm" class="text-zinc-400 dark:text-zinc-500">Create your first lead to get started</flux:text>
                    <flux:button size="sm" variant="primary" tag="a" :href="route('app.leads.create')" class="mt-4">
                        Create Lead
                    </flux:button>
                </div>
            @else
                <div class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @foreach ($recentLeads as $lead)
                        <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                            <div class="flex items-center gap-3">
                                <flux:avatar :name="$lead->title" size="sm" />
                                <div>
                                    <flux:heading level="3" size="sm">{{ $lead->title }}</flux:heading>
                                    <flux:text size="xs" class="text-zinc-500 dark:text-zinc-400">
                                        {{ $lead->company?->name ?? 'No company' }} · {{ $lead->created_at->diffForHumans() }}
                                    </flux:text>
                                </div>
                            </div>
                            <flux:badge :color="$lead->status->color()">{{ $lead->status->label() }}</flux:badge>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-700 text-center">
                    <flux:button size="sm" variant="ghost" tag="a" :href="route('app.leads.index')">
                        View all leads
                        <flux:icon.chevron-right variant="micro" class="ml-1" />
                    </flux:button>
                </div>
            @endif
        </flux:card>

        <flux:card>
            <div class="flex items-center justify-between mb-4">
                <flux:heading level="2" size="sm">Quick Actions</flux:heading>
            </div>

            <div class="space-y-2">
                <flux:button variant="outline" class="w-full justify-start" tag="a" :href="route('app.leads.create')">
                    <flux:icon.plus variant="micro" class="mr-2" />
                    New Lead
                </flux:button>
                <flux:button variant="outline" class="w-full justify-start" tag="a" :href="route('app.campaigns.index')">
                    <flux:icon.megaphone variant="micro" class="mr-2" />
                    View Campaigns
                </flux:button>
                <flux:button variant="outline" class="w-full justify-start" tag="a" :href="route('app.activities.index')">
                    <flux:icon.clock variant="micro" class="mr-2" />
                    Activity Log
                </flux:button>
            </div>
        </flux:card>
    </div>

    <flux:card>
        <flux:heading level="2" size="sm" class="mb-4">Recent Activity</flux:heading>

        @if ($recentActivities->isEmpty())
            <div class="text-center py-8">
                <flux:icon.clock variant="outline" class="mx-auto h-12 w-12 text-zinc-300 dark:text-zinc-600" />
                <flux:heading level="3" size="sm" class="mt-4 text-zinc-500">No activity yet</flux:heading>
                <flux:text size="sm" class="text-zinc-400 dark:text-zinc-500">Activity will appear here as you use the app</flux:text>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($recentActivities as $activity)
                    @php
                        $subjectClass = $activity->subject_type ? class_basename($activity->subject_type) : null;
                        $properties = $activity->properties ?? [];
                    @endphp
                    <div class="flex items-start gap-3">
                        <div class="mt-1">
                            <flux:avatar :name="$activity->causer?->name ?? ($activity->log_name ?? 'System')" size="sm" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <flux:badge size="sm" variant="subtle">{{ $activity->log_name ?? 'Activity' }}</flux:badge>
                                <flux:heading level="3" size="sm">
                                    {{ $activity->description }}
                                </flux:heading>
                            </div>
                            <flux:text size="xs" class="text-zinc-500 dark:text-zinc-400">
                                @if ($subjectClass)
                                    <span>{{ $subjectClass }}</span>
                                    @if ($activity->subject_id)
                                        <span>#{{ $activity->subject_id }}</span>
                                    @endif
                                    <span class="mx-1">·</span>
                                @endif
                                {{ $activity->created_at->diffForHumans() }}
                            </flux:text>
                            @if ($properties && count($properties) > 0)
                                <flux:text size="xs" class="text-zinc-400 dark:text-zinc-500 mt-0.5 italic">
                                    @if (isset($properties['attributes']))
                                        Updated: {{ implode(', ', array_map(fn($v, $k) => "$k: $v", $properties['attributes'], array_keys($properties['attributes']))) }}
                                    @elseif (isset($properties['old']))
                                        Changed: {{ implode(', ', array_keys($properties['old'] ?? [])) }}
                                    @endif
                                </flux:text>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </flux:card>
</div>