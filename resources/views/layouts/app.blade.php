@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head', $attributes)
    @livewireStyles
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">

    <x-layouts::sidebar.dynamic />
    <x-layouts::topbar.default />

    <flux:main>
        {{ $slot }}
    </flux:main>

    @livewireScripts
    @fluxScripts
</body>

</html>
