@extends('layouts.admin')

@section('title', 'Histórico de Downloads')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Histórico de Downloads</h1>
            <p class="mt-1 text-sm text-gray-400">Acompanhe todos os downloads realizados pelos clientes</p>
        </div>
        <a href="{{ route('admin.historico.export', request()->query()) }}" 
           class="rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 flex items-center gap-2 transform hover:scale-105 active:scale-95 shadow-lg shadow-[#f2c700]/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exportar CSV
        </a>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Total de Downloads</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ number_format($stats['total_downloads'], 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-[#f2c700]/20 rounded-lg">
                    <svg class="w-8 h-8 text-[#f2c700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Downloads Hoje</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ number_format($stats['downloads_hoje'], 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-green-500/20 rounded-lg">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Downloads Este Mês</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ number_format($stats['downloads_mes'], 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-blue-500/20 rounded-lg">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 p-6">
        <form method="GET" action="{{ route('admin.historico.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-white mb-2">Grupo</label>
                <select name="grupo_id" 
                        class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
                    <option value="">Todos os grupos</option>
                    @foreach($grupos as $grupo)
                        <option value="{{ $grupo->id }}" {{ request('grupo_id') == $grupo->id ? 'selected' : '' }}>
                            {{ $grupo->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-2">Usuário</label>
                <input type="text" name="usuario" value="{{ request('usuario') }}" 
                       placeholder="Nome do usuário..."
                       class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-2">Data Início</label>
                <input type="date" name="data_inicio" value="{{ request('data_inicio') }}" 
                       class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-2">Data Fim</label>
                <input type="date" name="data_fim" value="{{ request('data_fim') }}" 
                       class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
            </div>

            <div class="md:col-span-4 flex gap-3">
                <button type="submit" 
                        class="rounded-md bg-[#f2c700] px-6 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg shadow-[#f2c700]/20">
                    Aplicar Filtros
                </button>
                @if(request()->anyFilled(['grupo_id', 'usuario', 'data_inicio', 'data_fim']))
                    <a href="{{ route('admin.historico.index') }}" 
                       class="rounded-md bg-gray-700 px-6 py-2 text-sm font-semibold text-white hover:bg-gray-600">
                        Limpar Filtros
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabela de Downloads -->
    @if($downloads->count() > 0)
        <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-[#171717]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Data/Hora
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Arquivo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Usuário
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Grupo/Subpasta
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                IP Address
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($downloads as $download)
                            <tr class="hover:bg-[#171717] transition-all duration-200 cursor-pointer transform hover:scale-[1.01]">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-white">
                                        {{ $download->downloaded_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $download->downloaded_at->format('H:i:s') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-6 h-6 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                                        </svg>
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium text-white truncate">
                                                {{ $download->arquivo->nome }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $download->arquivo->tamanho_formatado }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-shrink-0 h-8 w-8 bg-[#f2c700] rounded-full flex items-center justify-center">
                                            <span class="text-xs font-semibold text-black">
                                                {{ strtoupper(substr($download->usuario, 0, 1)) }}
                                            </span>
                                        </div>
                                        <span class="text-sm text-white">{{ $download->usuario }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-white">{{ $download->arquivo->grupo->nome }}</div>
                                    @if($download->arquivo->subpasta)
                                        <div class="text-xs text-gray-400">{{ $download->arquivo->subpasta->nome }}</div>
                                    @else
                                        <div class="text-xs text-gray-400">Raiz do grupo</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-400">{{ $download->ip_address ?? 'N/A' }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginação -->
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-400">
                Mostrando {{ $downloads->firstItem() ?? 0 }} a {{ $downloads->lastItem() ?? 0 }} de {{ $downloads->total() }} downloads
            </div>
            <div>
                {{ $downloads->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-16 bg-[#1e1e1e] rounded-lg border border-gray-800">
            <div class="animate-pulse">
                <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="mt-2 text-lg font-semibold text-white">Nenhum download encontrado</h3>
            <p class="mt-2 text-sm text-gray-400 max-w-md mx-auto">
                @if(request()->anyFilled(['grupo_id', 'usuario', 'data_inicio', 'data_fim']))
                    Tente ajustar os filtros ou limpar a busca para ver mais resultados.
                @else
                    Ainda não há downloads registrados no sistema. Os downloads serão exibidos aqui quando os clientes começarem a baixar arquivos.
                @endif
            </p>
        </div>
    @endif
</div>
@endsection
