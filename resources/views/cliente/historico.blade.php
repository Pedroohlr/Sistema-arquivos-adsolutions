@extends('layouts.cliente')

@section('title', 'Meu Histórico')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-white">Meu Histórico de Downloads</h1>
        <p class="mt-1 text-sm text-gray-400">Veja todos os arquivos que você já baixou</p>
    </div>

    <!-- Tabela de Downloads -->
    @if($downloads->count() > 0)
        <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 overflow-hidden">
            <div class="divide-y divide-gray-800 md:hidden">
                @foreach($downloads as $download)
                    <div class="space-y-4 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-medium text-white">{{ $download->downloaded_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $download->downloaded_at->format('H:i:s') }}</div>
                            </div>
                            <div class="rounded-full bg-[#f2c700]/15 px-2.5 py-1 text-xs font-medium text-[#f2c700]">
                                {{ $download->arquivo->tamanho_formatado }}
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="h-6 w-6 flex-shrink-0 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                            </svg>
                            <div class="min-w-0 flex-1">
                                <div class="truncate text-sm font-medium text-white">{{ $download->arquivo->nome }}</div>
                            </div>
                        </div>
                        <a href="{{ route('cliente.download', $download->arquivo) }}" 
                           class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black transition-colors hover:bg-[#d9b300]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Baixar Novamente
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="hidden overflow-x-auto md:block">
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
                                Tamanho
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Ação
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($downloads as $download)
                            <tr class="hover:bg-[#171717] transition-all duration-200">
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
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ $download->arquivo->tamanho_formatado }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('cliente.download', $download->arquivo) }}" 
                                       class="inline-flex items-center gap-2 text-sm text-[#f2c700] hover:text-[#d9b300] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Baixar Novamente
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginação -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
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
            <h3 class="mt-2 text-lg font-semibold text-white">Nenhum download ainda</h3>
            <p class="mt-2 text-sm text-gray-400 max-w-md mx-auto">Você ainda não baixou nenhum arquivo. Comece explorando seus arquivos disponíveis!</p>
            <div class="mt-8">
                <a href="{{ route('cliente.dashboard') }}" 
                   class="inline-flex items-center gap-2 rounded-md bg-[#f2c700] px-6 py-3 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 shadow-lg shadow-[#f2c700]/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    Ver Meus Arquivos
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
