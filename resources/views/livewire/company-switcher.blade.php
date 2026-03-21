@php
    $companies = Auth::user()->companies;
    $currentCompany = Auth::user()->currentCompany;
@endphp

@if ($companies->count() > 0)
    <flux:dropdown position="bottom" align="start">
        <flux:sidebar.profile :name="$currentCompany?->name ?? __('Select Company')"
            icon:trailing="chevrons-up-down"
        />

        <flux:menu class="w-72">
            <div class="px-2 py-1.5 text-xs font-medium text-zinc-500">
                {{ __('Switch Company') }}
            </div>

            <flux:menu.separator />

            @forelse($companies as $company)
                <flux:menu.item wire:click="switchCompany({{ $company->id }})" as="button"
                    class="w-full cursor-pointer"
                >
                    <div class="flex items-center gap-3">
                        <flux:avatar :name="$company->name" />
                        <div class="grid flex-1 text-start">
                            <flux:heading level="5" size="sm">{{ $company->name }}
                            </flux:heading>
                            <flux:text size="xs">{{ $company->pivot->role ?? 'Member' }}
                            </flux:text>
                        </div>
                        @if ($company->id === $currentCompany?->id)
                            <flux:icon name="check" class="size-4" />
                        @endif
                    </div>
                </flux:menu.item>
            @empty
                <flux:menu.item disabled>{{ __('No companies available') }}</flux:menu.item>
            @endforelse
        </flux:menu>
    </flux:dropdown>
@endif
