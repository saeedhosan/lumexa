<x-layouts::app title="Reports">

    <div class="max-w-4xl">
        <flux:heading size="xl">Reports</flux:heading>
        <flux:subheading class="mb-6">Overview of your application.</flux:subheading>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <flux:card>
                <flux:heading level="2" size="lg">Total Users</flux:heading>
                <flux:text class="text-4xl font-bold mt-2">{{ $stats['total_users'] }}</flux:text>
            </flux:card>

            <flux:card>
                <flux:heading level="2" size="lg">Total Companies</flux:heading>
                <flux:text class="text-4xl font-bold mt-2">{{ $stats['total_companies'] }}</flux:text>
            </flux:card>

            <flux:card>
                <flux:heading level="2" size="lg">Users by Type</flux:heading>
                <div class="mt-4 space-y-2">
                    @foreach($stats['users_by_type'] as $type => $count)
                        <div class="flex justify-between">
                            <flux:text size="sm">{{ ucfirst($type) }}</flux:text>
                            <flux:text size="sm" class="font-medium">{{ $count }}</flux:text>
                        </div>
                    @endforeach
                </div>
            </flux:card>

            <flux:card>
                <flux:heading level="2" size="lg">Users per Company</flux:heading>
                <div class="mt-4 space-y-2">
                    @forelse($stats['users_per_company'] as $company => $count)
                        <div class="flex justify-between">
                            <flux:text size="sm">{{ $company }}</flux:text>
                            <flux:text size="sm" class="font-medium">{{ $count }}</flux:text>
                        </div>
                    @empty
                        <flux:text size="sm" class="text-zinc-500">No companies</flux:text>
                    @endforelse
                </div>
            </flux:card>
        </div>
    </div>

</x-layouts::app>