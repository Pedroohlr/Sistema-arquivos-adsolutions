<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - ADSolutions</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-[#171717]">
    <div class="flex min-h-screen flex-col md:flex-row">
        <!-- Lado Esquerdo - Card de Login -->
        <div class="flex w-full items-center justify-center px-4 py-10 sm:px-6 md:w-1/2 md:px-8 md:py-12 lg:px-12">
            <div class="w-full max-w-md">
                <div class="flex justify-center mb-8">
                    <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-20 w-auto object-contain sm:h-24">
                </div>
                <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-white mb-10">
                    Painel Administrativo
                </h2>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-900/20 border border-red-800 p-4">
                        <p class="text-sm text-red-400">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('admin.login.post') }}" method="POST">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-white">Email</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                value="{{ old('email') }}"
                                class="block w-full rounded-md border-0 bg-[#1e1e1e] py-2.5 px-3 text-white shadow-sm ring-1 ring-inset ring-gray-700 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-[#f2c700] sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-white">Senha</label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required
                                class="block w-full rounded-md border-0 bg-[#1e1e1e] py-2.5 px-3 text-white shadow-sm ring-1 ring-inset ring-gray-700 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-[#f2c700] sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 rounded border-gray-700 bg-[#1e1e1e] text-[#f2c700] focus:ring-[#f2c700]">
                        <label for="remember" class="ml-2 block text-sm text-gray-300">
                            Lembrar-me
                        </label>
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md bg-[#f2c700] px-3 py-2.5 text-sm font-semibold leading-6 text-black shadow-sm hover:bg-[#d9b300] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#f2c700] transition-colors">
                            Entrar
                        </button>
                    </div>
                </form>

                <p class="mt-10 text-center text-sm text-gray-400">
                    <a href="{{ route('cliente.login') }}" class="font-semibold text-[#f2c700] hover:text-[#d9b300]">
                        Acessar como cliente
                    </a>
                </p>
            </div>
        </div>

        <!-- Lado Direito - Imagem -->
        <div class="relative hidden min-h-70 md:block md:w-1/2">
            <img src="{{ asset('images/mulher-tablet.webp') }}" alt="Login" class="h-full w-full object-cover">
        </div>
    </div>
</body>

</html>