@extends('layouts.cliente')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Logo no topo (apenas mobile) -->
        <div class="lg:hidden flex justify-center mb-4">
            <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-20 w-auto object-contain">
        </div>

        <!-- Boas-vindas -->
        <div class="bg-gradient-to-r from-[#f2c700]/20 to-[#f2c700]/10 rounded-lg border border-[#f2c700]/30 p-6">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-6 h-6 text-[#f2c700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <h1 class="text-2xl font-bold text-white">
                    Olá, <span class="text-[#f2c700]">{{ $cliente->usuario }}</span>!
                </h1>
            </div>
            <p class="text-gray-300">Bem-vindo ao seu portal de arquivos. Aqui você pode acessar e baixar todos os arquivos
                disponíveis para você.</p>
        </div>

        <!-- Cards dos Grupos -->
        @if($grupos->isNotEmpty())
            @foreach($grupos as $grupo)
                @php
                    $minhasSubpastasNoGrupo = $subpastas->where('grupo_id', $grupo->id);
                    $totalMeusArquivos = $minhasSubpastasNoGrupo->sum(fn($s) => $s->arquivos->count());
                @endphp
                <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 p-8 hover:border-[#f2c700] transition-all">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="p-4 bg-[#f2c700]/20 rounded-lg">
                                <svg class="w-12 h-12 text-[#f2c700]" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">{{ $grupo->nome }}</h2>
                                @if($grupo->descricao)
                                    <p class="text-gray-400 mt-1">{{ $grupo->descricao }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Estatísticas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-[#171717] rounded-lg p-4 border border-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-700/50 rounded-lg">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Arquivos do Grupo</p>
                                    <p class="text-lg font-semibold text-white">{{ $grupo->arquivos->count() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-[#171717] rounded-lg p-4 border border-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-700/50 rounded-lg">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Minhas Pastas ({{ $minhasSubpastasNoGrupo->count() }})</p>
                                    <p class="text-lg font-semibold text-white">{{ $totalMeusArquivos }} arquivo(s)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botão Ver Arquivos -->
                    <a href="{{ route('cliente.grupos.show', $grupo) }}"
                        class="inline-flex items-center justify-center w-full rounded-md bg-[#f2c700] px-6 py-3 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 gap-2 transform hover:scale-105 active:scale-95 shadow-lg shadow-[#f2c700]/20 hover:shadow-[#f2c700]/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        Ver Meus Arquivos
                    </a>
                </div>
            @endforeach
        @else
            <div class="text-center py-16 bg-[#1e1e1e] rounded-lg border border-gray-800">
                <div class="animate-pulse">
                    <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="mt-2 text-lg font-semibold text-white">Nenhum grupo disponível</h3>
                <p class="mt-2 text-sm text-gray-400 max-w-md mx-auto">Você ainda não tem acesso a nenhum grupo de arquivos.
                    Entre em contato com o administrador para obter acesso.</p>
            </div>
        @endif
        {{-- End @foreach($grupos) is not needed here since @if/@else/@endif handles it --}}

        <!-- Instruções -->
        <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#f2c700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Como usar
            </h3>
            <ul class="space-y-3 text-sm text-gray-300">
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#f2c700] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Clique em "Ver Meus Arquivos" para acessar todos os arquivos disponíveis para você.</span>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#f2c700] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Você verá arquivos do grupo e arquivos da sua pasta pessoal.</span>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#f2c700] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Clique no botão "Baixar" em qualquer arquivo para fazer o download.</span>
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-[#f2c700] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Você pode ver seu histórico de downloads na aba "Histórico".</span>
                </li>
            </ul>
        </div>
    </div>
@endsection