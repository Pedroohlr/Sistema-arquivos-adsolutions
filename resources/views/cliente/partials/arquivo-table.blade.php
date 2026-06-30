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
    search: '',
    sortBy: 'data_ts',
    sortDir: 'desc',
    get filtered() {
        const q = this.search.toLowerCase().trim();
        const list = q
            ? this.files.filter(f => f.nome.toLowerCase().includes(q))
            : this.files;
        return [...list].sort((a, b) => {
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
}" class="space-y-3">

    {{-- Barra de busca --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="relative flex-1 sm:max-w-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z"/>
                </svg>
            </div>
            <input
                type="text"
                x-model="search"
                placeholder="Buscar arquivos..."
                class="w-full rounded-lg border border-gray-700 bg-[#1a1a1a] py-2.5 pl-10 pr-9 text-sm text-white placeholder-gray-500 transition-colors focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary"
            >
            <button
                x-show="search"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                @click="search = ''"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-white transition-colors"
                title="Limpar busca">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <p class="text-xs text-gray-500 shrink-0">
            <span x-text="filtered.length"></span>
            <span x-text="filtered.length === files.length ? ' arquivo(s)' : ` de ${files.length} arquivo(s)`"></span>
        </p>
    </div>

    {{-- Tabela --}}
    <div class="overflow-hidden rounded-lg border border-gray-800 bg-bg-secondary">

        {{-- Cabeçalho das colunas --}}
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
            <template x-for="arquivo in filtered" :key="arquivo.id">
                <div class="px-4 py-3 hover:bg-bg-primary transition-colors duration-150">
                    <div class="flex flex-col gap-3 sm:grid sm:grid-cols-[1fr_130px_100px_120px] sm:items-center sm:gap-4">

                        {{-- Nome --}}
                        <div class="flex items-center gap-3 min-w-0">
                            <svg class="shrink-0 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-md shadow-primary/20 sm:w-auto">
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

            {{-- Estado vazio da busca --}}
            <div x-show="filtered.length === 0" class="px-4 py-12 text-center">
                <svg class="mx-auto h-10 w-10 text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M21 21l-4.35-4.35M17 11A6 6 0 1 0 5 11a6 6 0 0 0 12 0z"/>
                </svg>
                <p class="text-sm text-gray-400 mb-1">Nenhum arquivo encontrado para</p>
                <p class="text-sm font-medium text-white mb-4" x-text="`"${search}"`"></p>
                <button @click="search = ''"
                    class="text-xs text-primary hover:text-primary/80 underline transition-colors">
                    Limpar busca
                </button>
            </div>
        </div>
    </div>
</div>
