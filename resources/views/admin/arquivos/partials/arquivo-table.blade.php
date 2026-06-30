@php
    $data = $arquivos->map(fn($a) => [
        'id'                => $a->id,
        'nome'              => $a->nome,
        'tamanho'           => $a->tamanho,
        'tamanho_formatado' => $a->tamanho_formatado,
        'data_ts'           => $a->created_at->timestamp,
        'data_formatada'    => $a->created_at->format('d/m/Y'),
        'download_url'      => route('admin.arquivos.arquivos.download', $a->id),
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

        <p class="shrink-0 text-xs text-gray-500">
            <span x-text="filtered.length"></span>
            <span x-text="filtered.length === files.length ? ' arquivo(s)' : ` de ${files.length} arquivo(s)`"></span>
        </p>
    </div>

    {{-- Tabela --}}
    <div class="overflow-hidden rounded-lg border border-gray-800 bg-bg-primary">

        {{-- Cabeçalho das colunas --}}
        <div class="hidden sm:grid sm:grid-cols-[1fr_130px_100px_96px] border-b border-gray-700 bg-[#1a1a1a] px-4 py-2 gap-4">
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
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 text-center">Ações</div>
        </div>

        {{-- Linhas --}}
        <div class="divide-y divide-gray-800">
            <template x-for="arquivo in filtered" :key="arquivo.id">
                <div class="px-4 py-3 hover:bg-[#252525] transition-colors duration-150">
                    <div class="flex flex-col gap-3 sm:grid sm:grid-cols-[1fr_130px_100px_96px] sm:items-center sm:gap-4">

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

                        {{-- Ações --}}
                        <div class="flex items-center justify-end gap-1 sm:justify-center">
                            <a :href="arquivo.download_url"
                                class="p-1.5 rounded bg-gray-700 hover:bg-gray-600 text-white transition-colors"
                                title="Baixar arquivo">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                            <button @click="deleteArquivo(arquivo.id, arquivo.nome)"
                                class="p-1.5 rounded bg-red-900 hover:bg-red-800 text-white transition-colors"
                                title="Deletar arquivo">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
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
                <p class="text-sm font-medium text-white mb-4" x-text="`&quot;${search}&quot;`"></p>
                <button @click="search = ''"
                    class="text-xs text-primary hover:text-primary/80 underline transition-colors">
                    Limpar busca
                </button>
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            function deleteArquivo(id, nome) {
                if (confirm(`⚠️ Tem certeza que deseja deletar o arquivo "${nome}"?\n\nEsta ação não pode ser desfeita.`)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/arquivos/arquivos/${id}`;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    @endpush
@endonce
