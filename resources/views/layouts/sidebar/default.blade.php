<flux:sidebar sticky collapsible="mobile"
    class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
>
    <flux:sidebar.header>
        <x-app-logo :sidebar="true" href="{{ route('customer.leads.index') }}" wire:navigate />
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <x-company-switcher />

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Core')" class="grid">
            <flux:sidebar.item
                icon="megaphone"
                :href="route('customer.leads.index')"
                :current="request()->routeIs('customer.leads.*')"
                wire:navigate
            >
                {{ __('Leads') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="shield-exclamation"
                :href="route('customer.breaches.index')"
                :current="request()->routeIs('customer.breaches.*')"
                wire:navigate
            >
                {{ __('Breaches') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        <flux:sidebar.group :heading="__('Management')" class="grid">
            <flux:sidebar.item
                icon="cube"
                :href="route('customer.products.index')"
                :current="request()->routeIs('customer.products.*')"
                wire:navigate
            >
                {{ __('Products') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="building-office"
                :href="route('customer.companies.index')"
                :current="request()->routeIs('customer.companies.*')"
                wire:navigate
            >
                {{ __('Companies') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="clock"
                :href="route('customer.activities.index')"
                :current="request()->routeIs('customer.activities.*')"
                wire:navigate
            >
                {{ __('Activities') }}
            </flux:sidebar.item>
        </flux:sidebar.group>
    </flux:sidebar.nav>

    <flux:spacer />

</flux:sidebar>
