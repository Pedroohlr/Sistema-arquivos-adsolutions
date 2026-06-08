<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redefinir Senha Admin - ADSolutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-[#171717]">
    <div class="flex min-h-screen flex-col md:flex-row">
        <div class="flex w-full items-center justify-center px-4 py-10 sm:px-6 md:w-1/2 md:px-8 md:py-12 lg:px-12">
            <div class="w-full max-w-md">
                <div class="flex justify-center mb-8">
                    <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-20 w-auto object-contain sm:h-24">
                </div>
                <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-white mb-2">
                    Redefinir Senha
                </h2>
                <p class="text-center text-sm text-gray-400 mb-10">
                    Crie uma nova senha para sua conta de administrador.
                </p>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-900/20 border border-red-800 p-4">
                        <p class="text-sm text-red-400">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('admin.reset-password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-white">E-mail</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                value="{{ old('email', $email) }}"
                                class="block w-full rounded-md border-0 bg-[#1e1e1e] py-2.5 px-3 text-white shadow-sm ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-[#f2c700] sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-white">Nova Senha</label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                class="block w-full rounded-md border-0 bg-[#1e1e1e] py-2.5 px-3 text-white shadow-sm ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-[#f2c700] sm:text-sm sm:leading-6"
                                placeholder="Mínimo 6 caracteres">
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium leading-6 text-white">Confirmar Nova Senha</label>
                        <div class="mt-2">
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                autocomplete="new-password" required
                                class="block w-full rounded-md border-0 bg-[#1e1e1e] py-2.5 px-3 text-white shadow-sm ring-1 ring-inset ring-gray-700 focus:ring-2 focus:ring-inset focus:ring-[#f2c700] sm:text-sm sm:leading-6"
                                placeholder="Repita a nova senha">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md bg-[#f2c700] px-3 py-2.5 text-sm font-semibold leading-6 text-black shadow-sm hover:bg-[#d9b300] transition-colors">
                            Redefinir Senha
                        </button>
                    </div>
                </form>

                <p class="mt-8 text-center text-sm text-gray-400">
                    <a href="{{ route('admin.login') }}" class="font-semibold text-[#f2c700] hover:text-[#d9b300]">
                        ← Voltar ao login
                    </a>
                </p>
            </div>
        </div>
        <div class="relative hidden min-h-70 md:block md:w-1/2">
            <img src="{{ asset('images/mulher-tablet.webp') }}" alt="Login" class="h-full w-full object-cover">
        </div>
    </div>
</body>
</html>
