<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
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

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name"
                                :initials="auth()->user()->initials()"
                            />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}
                                </flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer"
                        data-test="logout-button"
                    >
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <!-- Desktop Header -->
    <flux:header container
        class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
    >
        <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

        <x-app-logo href="{{ route('customer.leads.index') }}" wire:navigate />

        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item
                icon="megaphone"
                :href="route('customer.leads.index')"
                :current="request()->routeIs('customer.leads.*')"
                wire:navigate
            >
                {{ __('Leads') }}
            </flux:navbar.item>
        </flux:navbar>

        <flux:spacer />

        <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
            <flux:tooltip :content="__('Search')" position="bottom">
                <flux:navbar.item
                    class="!h-10 [&>div>svg]:size-5"
                    icon="magnifying-glass"
                    href="#"
                    :label="__('Search')"
                />
            </flux:tooltip>
        </flux:navbar>

        <x-desktop-user-menu />
    </flux:header>

    <flux:main>
        {{ $slot }}
    </flux:main>

    @fluxScripts
</body>

</html>
