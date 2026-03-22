<flux:sidebar sticky collapsible="mobile"
    class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
>
    <flux:sidebar.header>
        <x-app-logo :sidebar="true" href="{{ route('super.home') }}" wire:navigate />
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <livewire:company.switch />

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Overview')" class="grid">
            <flux:sidebar.item
                icon="home"
                :href="route('super.home')"
                :current="request()->routeIs('super.home')"
                wire:navigate
            >
                {{ __('Dashboard') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        <flux:sidebar.group :heading="__('System')" class="grid">
            <flux:sidebar.item
                icon="users"
                :href="route('super.users.index')"
                :current="request()->routeIs('super.users.*')"
                wire:navigate
            >
                {{ __('Users') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="building-office"
                :href="route('super.companies.index')"
                :current="request()->routeIs('super.companies.*')"
                wire:navigate
            >
                {{ __('Companies') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="cube"
                :href="route('super.services.index')"
                :current="request()->routeIs('super.services.*')"
                wire:navigate
            >
                {{ __('Services') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        <flux:sidebar.group :heading="__('Billing')" class="grid">
            <flux:sidebar.item
                icon="currency-dollar"
                :href="route('super.plans.index')"
                :current="request()->routeIs('super.plans.*')"
                wire:navigate
            >
                {{ __('Plans') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        <flux:sidebar.group :heading="__('Maintenance')" class="grid">
            <flux:sidebar.item
                icon="document-text"
                :href="route('super.logs.index')"
                :current="request()->routeIs('super.logs.*')"
                wire:navigate
            >
                {{ __('Logs') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="cog"
                :href="route('super.settings.index')"
                :current="request()->routeIs('super.settings.*')"
                wire:navigate
            >
                {{ __('Settings') }}
            </flux:sidebar.item>
        </flux:sidebar.group>
    </flux:sidebar.nav>

</flux:sidebar>
