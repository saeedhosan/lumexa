<x-layouts::app title="Companies">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <flux:heading level="1" size="xl">Companies</flux:heading>
            <flux:button variant="primary" :href="route('admin.companies.create')">
                Add Company
            </flux:button>
        </div>

        @if($companies->isEmpty())
            <flux:card>
                <flux:text>No companies available.</flux:text>
            </flux:card>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($companies as $company)
                    <flux:card class="space-y-4">
                        <div class="flex items-start gap-4">
                            @if($company->logo)
                                <flux:avatar :src="$company->logo" :name="$company->name" size="lg" />
                            @else
                                <flux:avatar :name="$company->name" size="lg" />
                            @endif

                            <div class="flex-1 min-w-0">
                                <flux:heading level="3" size="md">{{ $company->name }}</flux:heading>
                                <flux:badge :color="$company->is_active ? 'green' : 'zinc'" size="sm">
                                    {{ $company->is_active ? 'Active' : 'Inactive' }}
                                </flux:badge>
                            </div>
                        </div>

                        @if($company->description)
                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $company->description }}
                            </flux:text>
                        @endif

                        @if($company->country)
                            <div class="flex items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                                <flux:icon name="map-pin" class="size-4" />
                                <span>{{ $company->country }}</span>
                            </div>
                        @endif

                        @if($company->plan)
                            <div class="flex items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                                <flux:icon name="credit-card" class="size-4" />
                                <span>{{ $company->plan->name }}</span>
                            </div>
                        @endif

                        @if($company->users->count() > 0)
                            <div class="flex items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                                <flux:icon name="users" class="size-4" />
                                <span>{{ $company->users->count() }} {{ Str::plural('member', $company->users->count()) }}</span>
                            </div>
                        @endif

                        <div class="flex gap-2 pt-2">
                            <flux:button size="sm" :href="route('admin.companies.show', $company)" variant="ghost">
                                View
                            </flux:button>
                            <flux:button size="sm" :href="route('admin.companies.edit', $company)" variant="ghost">
                                Edit
                            </flux:button>
                        </div>
                    </flux:card>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts::app>