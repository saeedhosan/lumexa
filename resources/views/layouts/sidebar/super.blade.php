<flux:sidebar sticky collapsible="mobile"
    class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
>
    <flux:sidebar.header>
        <x-app-logo :sidebar="true" href="{{ route('admin.home') }}" wire:navigate />
        <flux:sidebar.collapse class="lg:hidden" />
    </flux:sidebar.header>

    <livewire:company.switch />

    <flux:sidebar.nav>
        <flux:sidebar.group :heading="__('Overview')" class="grid">
            <flux:sidebar.item
                icon="home"
                :href="route('admin.home')"
                :current="request()->routeIs('admin.home')"
                wire:navigate
            >
                {{ __('Dashboard') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        <flux:sidebar.group :heading="__('System')" class="grid">
            <flux:sidebar.item
                icon="users"
                :href="route('admin.users.index')"
                :current="request()->routeIs('admin.users.*')"
                wire:navigate
            >
                {{ __('Users') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="building-office"
                :href="route('admin.all-companies.index')"
                :current="request()->routeIs('admin.all-companies.*')"
                wire:navigate
            >
                {{ __('Companies') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="cube"
                :href="route('admin.services.index')"
                :current="request()->routeIs('admin.services.*')"
                wire:navigate
            >
                {{ __('Services') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        <flux:sidebar.group :heading="__('Billing')" class="grid">
            <flux:sidebar.item
                icon="currency-dollar"
                :href="route('admin.plans.index')"
                :current="request()->routeIs('admin.plans.*')"
                wire:navigate
            >
                {{ __('Plans') }}
            </flux:sidebar.item>
        </flux:sidebar.group>

        <flux:sidebar.group :heading="__('Maintenance')" class="grid">
            <flux:sidebar.item
                icon="document-text"
                :href="route('admin.logs.index')"
                :current="request()->routeIs('admin.logs.*')"
                wire:navigate
            >
                {{ __('Logs') }}
            </flux:sidebar.item>
            <flux:sidebar.item
                icon="cog"
                :href="route('admin.settings.index')"
                :current="request()->routeIs('admin.settings.*')"
                wire:navigate
            >
                {{ __('Settings') }}
            </flux:sidebar.item>
        </flux:sidebar.group>
    </flux:sidebar.nav>

    <flux:spacer />

    <flux:sidebar.nav>
        <flux:sidebar.item icon="folder-git-2" href="https://github.com/saeedhosan/lumexa"
            target="_blank"
        >
            {{ __('Repository') }}
        </flux:sidebar.item>
    </flux:sidebar.nav>

</flux:sidebar>