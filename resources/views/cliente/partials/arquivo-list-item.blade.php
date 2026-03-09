<div class="p-4 hover:bg-[#171717] transition-colors duration-200">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-4 flex-1 min-w-0">
            <!-- Ícone padronizado -->
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            
            <!-- Informações do arquivo -->
            <div class="min-w-0 flex-1">
                <h4 class="text-white font-medium text-base mb-1 truncate">{{ $arquivo->nome }}</h4>
                <div class="flex items-center gap-4 text-xs text-gray-400">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                        </svg>
                        {{ $arquivo->tamanho_formatado }}
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $arquivo->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Botão de Download -->
        <div class="flex-shrink-0" x-data="{ loading: false }">
            <a href="{{ route('cliente.download', $arquivo) }}" 
               @click="loading = true; setTimeout(() => loading = false, 2000)"
               class="inline-flex items-center gap-2 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-md shadow-[#f2c700]/20">
                <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="loading ? 'Baixando...' : 'Baixar'"></span>
            </a>
        </div>
    </div>
</div>
