@extends('layouts.cliente')

@section('title', $grupo->nome)

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-gray-400">
            <a href="{{ route('cliente.dashboard') }}" class="hover:text-[#f2c700] transition-colors">Dashboard</a>
            <span>/</span>
            <span class="text-white">{{ $grupo->nome }}</span>
        </nav>

        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-white">{{ $grupo->nome }}</h1>
            @if($grupo->descricao)
                <p class="mt-1 text-sm text-gray-400">{{ $grupo->descricao }}</p>
            @endif
        </div>

        <!-- Arquivos do Grupo (Raiz) -->
        <div>
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-[#f2c700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
                Arquivos do Grupo
            </h2>

            @if($arquivosRaiz->count() > 0)
                <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 overflow-hidden">
                    <div class="divide-y divide-gray-800">
                        @foreach($arquivosRaiz as $arquivo)
                            @include('cliente.partials.arquivo-list-item', ['arquivo' => $arquivo])
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 border-dashed p-12 text-center">
                    <div class="animate-pulse">
                        <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </div>
                    <p class="text-gray-400 text-lg mb-2">Nenhum arquivo disponível no grupo</p>
                    <p class="text-gray-500 text-sm">Os arquivos aparecerão aqui quando forem adicionados pelo administrador.
                    </p>
                </div>
            @endif
        </div>

        <!-- Minhas Pastas -->
        @foreach($minhasSubpastas as $subpasta)
            <div>
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-[#f2c700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    Minha Pasta: <span class="text-[#f2c700]">{{ $subpasta->nome }}</span>
                </h2>

                @if($subpasta->arquivos->count() > 0)
                    <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 overflow-hidden">
                        <div class="divide-y divide-gray-800">
                            @foreach($subpasta->arquivos as $arquivo)
                                @include('cliente.partials.arquivo-list-item', ['arquivo' => $arquivo])
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 border-dashed p-12 text-center">
                        <div class="animate-pulse">
                            <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </div>
                        <p class="text-gray-400 text-lg mb-2">Nenhum arquivo na pasta "{{ $subpasta->nome }}"</p>
                        <p class="text-gray-500 text-sm">Os arquivos pessoais aparecerão aqui quando forem adicionados pelo
                            administrador.</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endsection