<x-layouts::app title="Company Details">

    <div class="max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="xl">{{ $company->name }}</flux:heading>
            <flux:badge :color="$company->is_active ? 'green' : 'red'">
                {{ $company->is_active ? 'Active' : 'Inactive' }}
            </flux:badge>
        </div>

        <flux:card>
            <div class="space-y-4">
                @if ($company->title)
                    <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-zinc-700">
                        <flux:heading level="3" size="sm" class="text-zinc-500">Title</flux:heading>
                        <flux:text>{{ $company->title }}</flux:text>
                    </div>
                @endif

                @if ($company->description)
                    <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-zinc-700">
                        <flux:heading level="3" size="sm" class="text-zinc-500">Description</flux:heading>
                        <flux:text class="max-w-xs">{{ $company->description }}</flux:text>
                    </div>
                @endif

                @if ($company->logo)
                    <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-zinc-700">
                        <flux:heading level="3" size="sm" class="text-zinc-500">Logo</flux:heading>
                        <img src="{{ $company->logo }}" alt="{{ $company->name }}" class="h-12 w-12 object-contain" />
                    </div>
                @endif

                @if ($company->country)
                    <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-zinc-700">
                        <flux:heading level="3" size="sm" class="text-zinc-500">Country</flux:heading>
                        <flux:text>{{ $company->country }}</flux:text>
                    </div>
                @endif

                @if ($company->language)
                    <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-zinc-700">
                        <flux:heading level="3" size="sm" class="text-zinc-500">Language</flux:heading>
                        <flux:text>{{ $company->language }}</flux:text>
                    </div>
                @endif

                @if ($company->timezone)
                    <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-zinc-700">
                        <flux:heading level="3" size="sm" class="text-zinc-500">Timezone</flux:heading>
                        <flux:text>{{ $company->timezone }}</flux:text>
                    </div>
                @endif

                @if ($company->currency)
                    <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-zinc-700">
                        <flux:heading level="3" size="sm" class="text-zinc-500">Currency</flux:heading>
                        <flux:text>{{ $company->currency }}</flux:text>
                    </div>
                @endif

                <div class="flex justify-between py-2 border-b border-zinc-100 dark:border-zinc-700">
                    <flux:heading level="3" size="sm" class="text-zinc-500">Created</flux:heading>
                    <flux:text>{{ $company->created_at->format('M d, Y') }}</flux:text>
                </div>
            </div>
        </flux:card>

        <div class="mt-6 flex gap-3">
            <flux:button :href="route('admin.companies.edit', $company)" variant="primary">
                Edit Company
            </flux:button>
            <flux:button :href="route('admin.companies.members.index', $company)" variant="outline">
                Manage Members
            </flux:button>
            <flux:button :href="route('admin.companies.index')" variant="ghost" wire:navigate>
                Back to List
            </flux:button>
        </div>
    </div>

</x-layouts::app>