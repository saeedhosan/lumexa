<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="bg-gray-50 dark:bg-zinc-900 flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col antialiased">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 flex items-center justify-between">
        <div class="text-lg font-bold text-gray-900 dark:text-white">Lumexa</div>
        @if (Route::has('login'))
            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-block px-5 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-zinc-600 hover:border-gray-900 dark:hover:border-white rounded-md transition-colors"
                    >
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-block px-5 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white rounded-md transition-colors"
                    >
                        Sign In
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-block px-5 py-1.5 text-sm font-medium bg-gray-900 dark:bg-white text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100 rounded-md transition-colors"
                        >
                            Get Started
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <div class="flex items-center justify-center w-full lg:grow">
        <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
            <div class="flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-zinc-800 rounded-lg dark:rounded-none lg:rounded-l-lg">
                <div class="text-xs font-semibold text-green-600 uppercase tracking-wider mb-2">Welcome to</div>
                <h1 class="mb-1 text-2xl font-bold text-gray-900 dark:text-white">Lumexa</h1>
                <p class="mb-6 text-gray-600 dark:text-gray-400 text-sm">Multi-company SaaS platform with secure authentication, role-based access control, and lead management.</p>

                <div class="grid grid-cols-1 gap-3 mb-6">
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-zinc-700/50">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-11a2 2 0 012-2h2a2 2 0 012 2v11" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-sm text-gray-900 dark:text-white">Multi-Company Workspace</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Separate workspace for each company with isolated data</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-zinc-700/50">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-6a2 2 0 012-2h4v4l4-4zm-4-4h6v2h-6V4z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-sm text-gray-900 dark:text-white">Lead Management</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Create, track, and manage your sales leads efficiently</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-gray-50 dark:bg-zinc-700/50">
                        <div class="shrink-0 w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-sm text-gray-900 dark:text-white">Role-Based Access Control</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Super Admin, Admin, and User roles with permissions</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>