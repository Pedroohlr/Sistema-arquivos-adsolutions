<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - ADSolutions</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#171717] text-white font-sans antialiased">
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
                    <a href="{{ route('admin.arquivos.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-300
                              {{ request()->routeIs('admin.arquivos.*') || request()->routeIs('admin.dashboard') 
                                 ? 'bg-[#f2c700] text-black shadow-lg shadow-[#f2c700]/20' 
                                 : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Arquivos
                    </a>
                    <a href="{{ route('admin.usuarios.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-300
                              {{ request()->routeIs('admin.usuarios.*') 
                                 ? 'bg-[#f2c700] text-black shadow-lg shadow-[#f2c700]/20' 
                                 : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Usuários
                    </a>
                    <a href="{{ route('admin.historico.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-300
                              {{ request()->routeIs('admin.historico.*') 
                                 ? 'bg-[#f2c700] text-black shadow-lg shadow-[#f2c700]/20' 
                                 : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Histórico
                    </a>
                </nav>
                
                <!-- User Info & Logout -->
                <div class="px-4 py-4 border-t border-gray-800">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm text-gray-300 truncate">{{ auth()->guard('admin')->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Mobile Navbar -->
        <nav class="lg:hidden bg-[#1e1e1e] border-b border-gray-800 w-full fixed top-0 z-50">
            <div class="px-4 sm:px-6">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-12 w-auto object-contain">
                        <button onclick="toggleMobileMenu()" 
                                class="ml-4 p-2 rounded-md text-gray-300 hover:bg-gray-700 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-300 mr-4 hidden sm:block">{{ auth()->guard('admin')->user()->name }}</span>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="rounded-md bg-gray-800 px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white transition-all duration-300">
                                Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 lg:pl-64">
            <div class="pt-16 lg:pt-0">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <!-- Toast Notifications -->
                    @if (session('success'))
                        <x-toast type="success" :message="session('success')" />
                    @endif

                    @if (session('error'))
                        <x-toast type="error" :message="session('error')" />
                    @endif

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <x-toast type="error" :message="$error" />
                        @endforeach
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
    </div>
    
    <!-- Menu Mobile -->
    <div id="mobileMenu" class="hidden lg:hidden fixed inset-0 z-50 bg-black bg-opacity-75" onclick="toggleMobileMenu()">
        <div class="bg-[#1e1e1e] w-64 h-full shadow-xl" onclick="event.stopPropagation()">
            <div class="p-4 border-b border-gray-800 flex items-center justify-between">
                <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-12 w-auto object-contain">
                <button onclick="toggleMobileMenu()" class="text-gray-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.arquivos.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors
                          {{ request()->routeIs('admin.arquivos.*') || request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    Arquivos
                </a>
                <a href="{{ route('admin.usuarios.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors
                          {{ request()->routeIs('admin.usuarios.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Usuários
                </a>
                <a href="{{ route('admin.historico.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors
                          {{ request()->routeIs('admin.historico.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Histórico
                </a>
                <div class="pt-4 border-t border-gray-800">
                    <p class="px-4 py-2 text-sm text-gray-400">{{ auth()->guard('admin')->user()->name }}</p>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Sair
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </div>
    
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
            document.body.style.overflow = menu.classList.contains('hidden') ? '' : 'hidden';
        }
    </script>
    @stack('scripts')
</body>
</html>
