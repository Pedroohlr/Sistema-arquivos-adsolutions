@extends('layouts.cliente')

@section('title', 'Alterar Senha')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <div class="rounded-lg border border-[#f2c700]/30 bg-gradient-to-r from-[#f2c700]/20 to-[#f2c700]/10 p-5 sm:p-6">
            <div class="mb-2 flex items-center gap-3">
                <svg class="h-6 w-6 text-[#f2c700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 11c1.104 0 2-.896 2-2V7a2 2 0 10-4 0v2c0 1.104.896 2 2 2zm-7 9h14a2 2 0 002-2v-5a2 2 0 00-2-2H5a2 2 0 00-2 2v5a2 2 0 002 2z" />
                </svg>
                <h1 class="text-2xl font-bold text-white">Alterar Senha</h1>
            </div>
            <p class="text-gray-300">Atualize a sua senha de acesso ao portal do cliente.</p>
        </div>

        <div class="rounded-lg border border-gray-800 bg-[#1e1e1e] p-6">
            <form method="POST" action="{{ route('cliente.password.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="mb-2 block text-sm font-medium text-white">Senha Atual</label>
                    <input id="current_password" name="current_password" type="password" required autocomplete="current-password"
                        class="w-full rounded-md border-0 bg-[#171717] px-3 py-2 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                        placeholder="Digite sua senha atual">
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-white">Nova Senha</label>
                    <input id="password" name="password" type="password" required minlength="4" autocomplete="new-password"
                        class="w-full rounded-md border-0 bg-[#171717] px-3 py-2 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                        placeholder="Digite a nova senha">
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-medium text-white">Confirmar Nova Senha</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="4" autocomplete="new-password"
                        class="w-full rounded-md border-0 bg-[#171717] px-3 py-2 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                        placeholder="Repita a nova senha">
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('cliente.dashboard') }}"
                        class="inline-flex items-center justify-center rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-gray-600">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black transition-colors hover:bg-[#d9b300]">
                        Salvar Nova Senha
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection