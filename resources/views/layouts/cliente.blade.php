<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal do Cliente') - ADSolutions</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full overflow-x-hidden bg-[#171717] text-white font-sans antialiased">
    <div class="min-h-full flex">
        <!-- Sidebar Desktop -->
        <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 lg:z-50">
            <div class="flex flex-col flex-grow bg-[#1e1e1e] border-r border-gray-800">
                <!-- Logo -->
                <div class="flex items-center justify-center h-20 px-4 border-b border-gray-800">
                    <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-16 w-auto object-contain">
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('cliente.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-300
                              {{ request()->routeIs('cliente.dashboard')
    ? 'bg-[#f2c700] text-black shadow-lg shadow-[#f2c700]/20'
    : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('cliente.historico') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-300
                              {{ request()->routeIs('cliente.historico')
    ? 'bg-[#f2c700] text-black shadow-lg shadow-[#f2c700]/20'
    : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Histórico
                    </a>
                </nav>

                <!-- User Info & Logout -->
                <div class="px-4 py-4 border-t border-gray-800">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm text-gray-300 truncate">Olá,
                            {{ auth()->guard('cliente')->user()->usuario }}</span>
                    </div>
                    <form method="POST" action="{{ route('cliente.logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Mobile Navbar -->
        <nav class="lg:hidden fixed top-0 z-50 w-full border-b border-gray-800 bg-[#1e1e1e]/95 backdrop-blur">
            <div class="px-4 sm:px-6">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex min-w-0 items-center gap-3">
                        <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-12 w-auto object-contain">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-white">Portal do Cliente</p>
                            <p class="truncate text-xs text-gray-400">{{ auth()->guard('cliente')->user()->usuario }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('cliente.logout') }}" class="hidden sm:block">
                            @csrf
                            <button type="submit"
                                class="rounded-md bg-gray-800 px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                                Sair
                            </button>
                        </form>
                        <button onclick="toggleMobileMenu()"
                            class="rounded-md p-2 text-gray-300 hover:bg-gray-700 hover:text-white"
                            aria-label="Abrir menu">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 lg:pl-64">
            <div class="pt-16 lg:pt-0">
                <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8 lg:py-6">
                    <!-- Toast Notifications -->
                    @if (session('success'))
                        <x-toast type="success" :message="session('success')" />
                    @endif

                    @if (session('error'))
                        <x-toast type="error" :message="session('error')" />
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
    </div>
    <div id="mobileMenu" class="hidden lg:hidden fixed inset-0 z-50 bg-black bg-opacity-75"
        onclick="toggleMobileMenu()">
        <div class="ml-auto flex h-full w-full max-w-xs flex-col bg-[#1e1e1e] shadow-xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-gray-800 p-4">
                <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-12 w-auto object-contain">
                <button onclick="toggleMobileMenu()" class="text-gray-400 hover:text-white" aria-label="Fechar menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="flex flex-1 flex-col p-4">
                <div class="space-y-2">
                    <a href="{{ route('cliente.dashboard') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-gray-300 transition-colors hover:bg-gray-700 hover:text-white
                              {{ request()->routeIs('cliente.dashboard') ? 'bg-gray-700 text-white' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('cliente.historico') }}" class="flex items-center gap-3 rounded-lg px-4 py-3 text-gray-300 transition-colors hover:bg-gray-700 hover:text-white
                              {{ request()->routeIs('cliente.historico') ? 'bg-gray-700 text-white' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Histórico
                    </a>
                </div>
                <div class="mt-auto border-t border-gray-800 pt-4">
                    <p class="px-4 py-2 text-sm text-gray-400">Olá, {{ auth()->guard('cliente')->user()->usuario }}</p>
                    <form method="POST" action="{{ route('cliente.logout') }}">
                        @csrf
                        <button type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-lg px-4 py-3 text-gray-300 transition-colors hover:bg-gray-700 hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Sair
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </div>
    @stack('scripts')
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
            document.body.style.overflow = menu.classList.contains('hidden') ? '' : 'hidden';
        }
    </script>
</body>

</html>