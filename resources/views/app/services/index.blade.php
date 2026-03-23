<x-layouts::app title="Services">
    <div class="space-y-6">
        <flux:heading level="1" size="xl">Your available services</flux:heading>

        @if($services->isEmpty())
            <flux:card>
                <flux:text>No services available for your company.</flux:text>
            </flux:card>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                    <flux:card class="space-y-4">
                        <div class="flex items-start gap-4">
                            @if($service->logo)
                                <flux:avatar :src="$service->logo" :name="$service->name" size="lg" />
                            @elseif($service->icon)
                                <flux:icon :name="$service->icon" class="size-10 text-zinc-500" />
                            @else
                                <flux:avatar :name="$service->name" size="lg" />
                            @endif

                            <div class="flex-1 min-w-0">
                                <flux:heading level="3" size="md">{{ $service->name }}</flux:heading>
                                <flux:badge :color="$service->is_active ? 'green' : 'zinc'" size="sm">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </flux:badge>
                            </div>
                        </div>

                        @if($service->about)
                            <flux:text class="text-sm text-zinc-500 dark:text-zinc-400">
                                {{ $service->about }}
                            </flux:text>
                        @endif

                        <div class="flex items-center gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                            <flux:icon name="code-bracket" class="size-4" />
                            <span>v{{ $service->version }}</span>
                        </div>

                        @if($service->features && is_array($service->features) && count($service->features) > 0)
                            <div class="space-y-2">
                                <flux:heading level="4" size="xs">Features</flux:heading>
                                <div class="grid grid-cols-1 gap-1">
                                    @foreach($service->features as $feature)
                                        <div class="flex items-center gap-2 text-sm">
                                            <flux:icon name="check-circle" class="size-4 text-green-500 shrink-0" />
                                            <span class="text-zinc-600 dark:text-zinc-300">{{ $feature }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </flux:card>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts::app>
