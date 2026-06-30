@php
    $data = $arquivos->map(fn($a) => [
        'id'                => $a->id,
        'nome'              => $a->nome,
        'tamanho'           => $a->tamanho,
        'tamanho_formatado' => $a->tamanho_formatado,
        'data_ts'           => $a->created_at->timestamp,
        'data_formatada'    => $a->created_at->format('d/m/Y'),
        'url'               => route('cliente.download', $a),
    ]);
@endphp

<div x-data="{
    files: {{ Js::from($data) }},
    sortBy: 'data_ts',
    sortDir: 'desc',
    get sorted() {
        return [...this.files].sort((a, b) => {
            let va = a[this.sortBy], vb = b[this.sortBy];
            if (this.sortBy === 'nome') {
                return this.sortDir === 'asc'
                    ? va.localeCompare(vb, 'pt-BR', { sensitivity: 'base' })
                    : vb.localeCompare(va, 'pt-BR', { sensitivity: 'base' });
            }
            return this.sortDir === 'asc' ? va - vb : vb - va;
        });
    },
    toggleSort(field) {
        if (this.sortBy === field) {
            this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortBy = field;
            this.sortDir = 'asc';
        }
    }
}" class="rounded-lg border border-gray-800 bg-bg-secondary overflow-hidden">

    {{-- Cabeçalho das colunas (só desktop) --}}
    <div class="hidden sm:grid sm:grid-cols-[1fr_130px_100px_120px] border-b border-gray-700 bg-[#1a1a1a] px-4 py-2 gap-4">
        <button @click="toggleSort('nome')"
            class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-gray-400 hover:text-white text-left transition-colors">
            Nome
            <svg x-show="sortBy === 'nome' && sortDir === 'asc'" class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
            </svg>
            <svg x-show="sortBy === 'nome' && sortDir === 'desc'" class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
            <svg x-show="sortBy !== 'nome'" class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/>
            </svg>
        </button>
        <button @click="toggleSort('data_ts')"
            class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-gray-400 hover:text-white transition-colors">
            Data
            <svg x-show="sortBy === 'data_ts' && sortDir === 'asc'" class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
            </svg>
            <svg x-show="sortBy === 'data_ts' && sortDir === 'desc'" class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
            <svg x-show="sortBy !== 'data_ts'" class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/>
            </svg>
        </button>
        <button @click="toggleSort('tamanho')"
            class="flex items-center gap-1 text-xs font-semibold uppercase tracking-wider text-gray-400 hover:text-white transition-colors">
            Tamanho
            <svg x-show="sortBy === 'tamanho' && sortDir === 'asc'" class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
            </svg>
            <svg x-show="sortBy === 'tamanho' && sortDir === 'desc'" class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
            </svg>
            <svg x-show="sortBy !== 'tamanho'" class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/>
            </svg>
        </button>
        <div></div>
    </div>

    {{-- Linhas --}}
    <div class="divide-y divide-gray-800">
        <template x-for="arquivo in sorted" :key="arquivo.id">
            <div class="px-4 py-3 hover:bg-[#171717] transition-colors duration-150">
                <div class="flex flex-col gap-3 sm:grid sm:grid-cols-[1fr_130px_100px_120px] sm:items-center sm:gap-4">

                    {{-- Nome --}}
                    <div class="flex items-center gap-3 min-w-0">
                        <svg class="flex-shrink-0 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-white text-sm font-medium truncate" x-text="arquivo.nome"></span>
                    </div>

                    {{-- Data --}}
                    <div class="flex items-center gap-2 sm:justify-start">
                        <span class="text-xs text-gray-500 sm:hidden">Data:</span>
                        <span class="text-gray-400 text-sm" x-text="arquivo.data_formatada"></span>
                    </div>

                    {{-- Tamanho --}}
                    <div class="flex items-center gap-2 sm:justify-start">
                        <span class="text-xs text-gray-500 sm:hidden">Tamanho:</span>
                        <span class="text-gray-400 text-sm" x-text="arquivo.tamanho_formatado"></span>
                    </div>

                    {{-- Botão download --}}
                    <div class="flex justify-end" x-data="{ loading: false }">
                        <a :href="arquivo.url"
                            @click="loading = true; setTimeout(() => loading = false, 2000)"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-md shadow-[#f2c700]/20 sm:w-auto">
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="loading ? 'Baixando...' : 'Baixar'"></span>
                        </a>
                    </div>

                </div>
            </div>
        </template>
    </div>
</div>
