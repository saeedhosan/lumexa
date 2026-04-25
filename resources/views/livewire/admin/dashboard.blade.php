<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading level="1">Admin Dashboard</flux:heading>

        <flux:badge color="red" variant="subtle">Admin</flux:badge>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <flux:card class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent"></div>
            <div class="relative">
                <flux:heading level="2" size="lg">{{ number_format($totalUsers) }}</flux:heading>
                <flux:text>Total Users</flux:text>
                <flux:badge class="mt-2">{{ $activeUsers }} active</flux:badge>
            </div>
        </flux:card>

        <flux:card class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-transparent"></div>
            <div class="relative">
                <flux:heading level="2" size="lg" class="text-green-600">{{ number_format($totalCompanies) }}</flux:heading>
                <flux:text>Companies</flux:text>
                <flux:badge color="green" class="mt-2">{{ $activeCompanies }} active</flux:badge>
            </div>
        </flux:card>

        <flux:card class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent"></div>
            <div class="relative">
                <flux:heading level="2" size="lg" class="text-purple-600">{{ number_format($totalServices) }}</flux:heading>
                <flux:text>Services</flux:text>
            </div>
        </flux:card>

        <flux:card class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-500/5 to-transparent"></div>
            <div class="relative">
                <flux:heading level="2" size="lg" class="text-orange-600">{{ number_format($totalPlans) }}</flux:heading>
                <flux:text>Plans</flux:text>
                <flux:badge color="orange" class="mt-2">{{ $activePlans }} active</flux:badge>
            </div>
        </flux:card>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <flux:card class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <flux:heading level="2" size="sm">Recent Registrations</flux:heading>
                <flux:button size="sm" variant="ghost" tag="a" :href="route('admin.users.index')">
                    View All
                    <flux:icon.chevron-right variant="micro" class="ml-1" />
                </flux:button>
            </div>

            @if ($recentRegistrations->isEmpty())
                <div class="text-center py-8">
                    <flux:icon.users variant="outline" class="mx-auto h-12 w-12 text-zinc-300 dark:text-zinc-600" />
                    <flux:heading level="3" size="sm" class="mt-4 text-zinc-500">No registrations yet</flux:heading>
                </div>
            @else
                <div class="divide-y divide-zinc-100 dark:divide-zinc-700">
                    @foreach ($recentRegistrations as $user)
                        <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                            <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                            <div class="flex items-center gap-3">
                                <flux:avatar :name="$user->name" size="sm" />
                                <div>
                                    <flux:heading level="3" size="sm">{{ $user->name }}</flux:heading>
                                    <flux:text size="xs" class="text-zinc-500 dark:text-zinc-400">
                                        {{ $user->companies->first()?->name ?? 'No company' }} · {{ $user->created_at->diffForHumans() }}
                                    </flux:text>
                                </div>
                            </div>
                            <flux:badge :color="$user->is_active ? 'green' : 'zinc'" size="sm">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </flux:badge>
                        </div>
                    @endforeach
                </div>
            @endif
        </flux:card>

        <flux:card>
            <div class="flex items-center justify-between mb-4">
                <flux:heading level="2" size="sm">Quick Stats</flux:heading>
            </div>

            <div class="space-y-4">
                @foreach ($quickStats as $stat)
                    <div class="flex items-center justify-between">
                        <flux:text>{{ $stat['label'] }}</flux:text>
                        <flux:heading level="3" size="sm">{{ number_format($stat['count']) }}</flux:heading>
                    </div>
                @endforeach
            </div>

            <flux:separator class="my-4" />

            <div class="space-y-2">
                <flux:heading level="3" size="xs" class="text-zinc-500 dark:text-zinc-400 mb-2">Quick Actions</flux:heading>
                <flux:button variant="outline" class="w-full justify-start" tag="a" :href="route('admin.users.create')">
                    <flux:icon.user-plus variant="micro" class="mr-2" />
                    Add User
                </flux:button>
                <flux:button variant="outline" class="w-full justify-start" tag="a" :href="route('admin.companies.create')">
                    <flux:icon.building-office variant="micro" class="mr-2" />
                    Add Company
                </flux:button>
                <flux:button variant="outline" class="w-full justify-start" tag="a" :href="route('admin.reports.index')">
                    <flux:icon.chart-bar variant="micro" class="mr-2" />
                    View Reports
                </flux:button>
                <flux:button variant="outline" class="w-full justify-start" tag="a" :href="route('admin.settings.index')">
                    <flux:icon.cog variant="micro" class="mr-2" />
                    Settings
                </flux:button>
            </div>
        </flux:card>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <flux:card>
            <div class="flex items-center justify-between mb-4">
                <flux:heading level="2" size="sm">New Users This Week</flux:heading>
                <flux:badge color="blue">{{ $newUsersThisWeek }} new</flux:badge>
            </div>

            <div class="text-center py-4">
                <flux:heading level="1" size="3xl" class="text-blue-600">{{ $newUsersThisWeek }}</flux:heading>
                <flux:text>registrations this week</flux:text>
            </div>
        </flux:card>

        <flux:card>
            <div class="flex items-center justify-between mb-4">
                <flux:heading level="2" size="sm">User Activity</flux:heading>
            </div>

            <div class="text-center py-4">
                <flux:heading level="1" size="3xl" class="text-green-600">{{ round(($activeUsers / max($totalUsers, 1)) * 100) }}%</flux:heading>
                <flux:text>of users are active</flux:text>
            </div>
        </flux:card>
    </div>

    <flux:card>
        <div class="flex items-center justify-between mb-4">
            <flux:heading level="2" size="sm">System Activity</flux:heading>
            <flux:button size="sm" variant="ghost" tag="a" :href="route('admin.logs.index')">
                View All Logs
                <flux:icon.chevron-right variant="micro" class="ml-1" />
            </flux:button>
        </div>

        @if ($recentActivities->isEmpty())
            <div class="text-center py-8">
                <flux:icon.clock variant="outline" class="mx-auto h-12 w-12 text-zinc-300 dark:text-zinc-600" />
                <flux:heading level="3" size="sm" class="mt-4 text-zinc-500">No activity yet</flux:heading>
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
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </flux:card>
</div>