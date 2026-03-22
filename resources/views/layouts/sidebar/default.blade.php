<flux:sidebar sticky collapsible="mobile"
    class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
>
    <flux:sidebar.header>
        <x-app-logo :sidebar="true" href="{{ route('dashboard.leads.index') }}" wire:navigate />
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <x-company-switcher />

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Core')" class="grid">
            <flux:sidebar.item
                icon="megaphone"
                :href="route('dashboard.leads.index')"
                :current="request()->routeIs('dashboard.leads.*')"
                wire:navigate
            >
                {{ __('Leads') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="shield-exclamation"
                :href="route('dashboard.breaches.index')"
                :current="request()->routeIs('dashboard.breaches.*')"
                wire:navigate
            >
                {{ __('Breaches') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        <flux:sidebar.group :heading="__('Management')" class="grid">
            <flux:sidebar.item
                icon="cube"
                :href="route('dashboard.products.index')"
                :current="request()->routeIs('dashboard.products.*')"
                wire:navigate
            >
                {{ __('Products') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="building-office"
                :href="route('dashboard.companies.index')"
                :current="request()->routeIs('dashboard.companies.*')"
                wire:navigate
            >
                {{ __('Companies') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="clock"
                :href="route('dashboard.activities.index')"
                :current="request()->routeIs('dashboard.activities.*')"
                wire:navigate
            >
                {{ __('Activities') }}
            </flux:sidebar.item>
        </flux:sidebar.group>
    </flux:sidebar.nav>

    <flux:spacer />

</flux:sidebar>
