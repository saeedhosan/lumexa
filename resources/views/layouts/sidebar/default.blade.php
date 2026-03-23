<flux:sidebar sticky collapsible="mobile"
    class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
>
    <flux:sidebar.header>
        <x-app-logo :sidebar="true" href="{{ route('app.leads.index') }}" wire:navigate />
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <livewire:company.switch />

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Core')" class="grid">
            <flux:sidebar.item
                icon="megaphone"
                :href="route('app.leads.index')"
                :current="request()->routeIs('app.leads.*')"
                wire:navigate
            >
                {{ __('Leads') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="shield-exclamation"
                :href="route('app.campaigns.index')"
                :current="request()->routeIs('app.campaigns.*')"
                wire:navigate
            >
                {{ __('Campaigns') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        <flux:sidebar.group :heading="__('Management')" class="grid">
            <flux:sidebar.item
                icon="cube"
                :href="route('app.services.index')"
                :current="request()->routeIs('app.services.*')"
                wire:navigate
            >
                {{ __('Services') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="building-office"
                :href="route('app.companies.index')"
                :current="request()->routeIs('app.companies.*')"
                wire:navigate
            >
                {{ __('Companies') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="clock"
                :href="route('app.activities.index')"
                :current="request()->routeIs('app.activities.*')"
                wire:navigate
            >
                {{ __('Activities') }}
            </flux:sidebar.item>
        </flux:sidebar.group>
    </flux:sidebar.nav>

    <flux:spacer />

</flux:sidebar>
