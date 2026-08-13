@php
    $data = $arquivos->map(fn($a) => [
        'id'                => $a->id,
        'nome'              => $a->nome,
        'tamanho'           => $a->tamanho,
        'tamanho_formatado' => $a->tamanho_formatado,
        'data_ts'           => $a->created_at->timestamp,
        'data_formatada'    => $a->created_at->format('d/m/Y'),
        'download_url'      => route('admin.arquivos.arquivos.download', $a->id),
        'delete_url'        => route('admin.arquivos.arquivos.destroy', $a->id),
    ]);
@endphp

<div x-data="{
    files: {{ Js::from($data) }},
    search: '',
    sortBy: 'data_ts',
    sortDir: 'desc',
    selected: [],
    deleting: false,
    confirmMode: null,
    confirmNome: '',
    confirmId: null,
    confirmDeleteUrl: '',
    toast: null,
    _toastTimer: null,
    batchUrl: '{{ route('admin.arquivos.arquivos.destroy-batch') }}',

    get filtered() {
        const q = this.search.toLowerCase().trim();
        const list = q ? this.files.filter(f => f.nome.toLowerCase().includes(q)) : this.files;
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
    get allSelected() {
        return this.filtered.length > 0 && this.filtered.every(f => this.selected.includes(f.id));
    },
    get someSelected() { return this.selected.length > 0; },

    toggleSort(field) {
        if (this.sortBy === field) this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
        else { this.sortBy = field; this.sortDir = 'asc'; }
    },
    toggleAll() {
        const ids = this.filtered.map(f => f.id);
        if (this.allSelected) {
            this.selected = this.selected.filter(id => !ids.includes(id));
        } else {
            this.selected = [...new Set([...this.selected, ...ids])];
        }
    },
    toggleSelect(id) {
        const idx = this.selected.indexOf(id);
        if (idx === -1) this.selected.push(id);
        else this.selected.splice(idx, 1);
    },
    isSelected(id) { return this.selected.includes(id); },

    askDeleteSingle(id, nome, url) {
        this.confirmId = id;
        this.confirmNome = nome;
        this.confirmDeleteUrl = url;
        this.confirmMode = 'single';
    },
    askDeleteBatch() { this.confirmMode = 'batch'; },
    cancelConfirm() {
        this.confirmMode = null;
        this.confirmId = null;
        this.confirmNome = '';
        this.confirmDeleteUrl = '';
    },
    async confirmDelete() {
        const mode = this.confirmMode;
        const id = this.confirmId;
        const url = this.confirmDeleteUrl;
        const ids = [...this.selected];
        this.cancelConfirm();
        if (mode === 'single') await this.doDeleteSingle(id, url);
        else if (mode === 'batch') await this.doDeleteBatch(ids);
    },
    async doDeleteSingle(id, url) {
        this.deleting = true;
        try {
            const resp = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json',
                }
            });
            if (resp.ok) {
                this.files = this.files.filter(f => f.id !== id);
                this.selected = this.selected.filter(i => i !== id);
                this.showToast('Arquivo deletado com sucesso.', 'success');
            } else {
                const d = await resp.json().catch(() => ({}));
                this.showToast(d.message || 'Erro ao deletar o arquivo.', 'error');
            }
        } catch {
            this.showToast('Erro de conexão. Tente novamente.', 'error');
        }
        this.deleting = false;
    },
    async doDeleteBatch(ids) {
        this.deleting = true;
        try {
            const resp = await fetch(this.batchUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ ids })
            });
            const d = await resp.json();
            if (d.deletados > 0) {
                const dIds = d.deletados_ids;
                this.files = this.files.filter(f => !dIds.includes(f.id));
                this.selected = this.selected.filter(i => !dIds.includes(i));
                const msg = (d.erros && d.erros.length)
                    ? d.mensagem + ` (${d.erros.length} falha(s))`
                    : d.mensagem;
                this.showToast(msg, (d.erros && d.erros.length) ? 'warning' : 'success');
            } else {
                this.showToast(d.mensagem || 'Nenhum arquivo foi deletado.', 'error');
            }
        } catch {
            this.showToast('Erro de conexão. Tente novamente.', 'error');
        }
        this.deleting = false;
    },
    showToast(msg, type) {
        this.toast = { msg, type };
        clearTimeout(this._toastTimer);
        this._toastTimer = setTimeout(() => this.toast = null, 5000);
    }
}" class="space-y-3">

    {{-- Toast --}}
    <div x-show="toast" x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed top-4 right-4 z-[100] max-w-sm w-full">
        <div class="rounded-lg shadow-lg border p-4 flex items-start gap-3"
            :class="{
                'bg-green-900/90 border-green-700 text-green-100': toast && toast.type === 'success',
                'bg-red-900/90 border-red-700 text-red-100': toast && toast.type === 'error',
                'bg-yellow-900/90 border-yellow-700 text-yellow-100': toast && toast.type === 'warning'
            }">
            <svg x-show="toast && toast.type === 'success'" class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <svg x-show="toast && toast.type === 'error'" class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <svg x-show="toast && toast.type === 'warning'" class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="text-sm font-medium flex-1" x-text="toast && toast.msg"></p>
            <button @click="toast = null" class="shrink-0 opacity-70 hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Modal de confirmação --}}
    <div x-show="confirmMode !== null" x-cloak
        class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4"
        @click.self="cancelConfirm()"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md border border-gray-800"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-lg font-semibold text-white mb-3 flex items-center gap-2">
                <svg class="w-6 h-6 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span x-text="confirmMode === 'batch' ? 'Deletar arquivos selecionados' : 'Deletar arquivo'"></span>
            </h3>
            <div x-show="confirmMode === 'single'" class="mb-6">
                <p class="text-gray-300 text-sm">
                    Tem certeza que deseja deletar o arquivo
                    <strong class="text-white break-all" x-text="`&quot;${confirmNome}&quot;`"></strong>?
                    Esta ação não pode ser desfeita.
                </p>
            </div>
            <div x-show="confirmMode === 'batch'" class="mb-6">
                <p class="text-gray-300 text-sm">
                    Tem certeza que deseja deletar
                    <strong class="text-red-400" x-text="selected.length"></strong>
                    arquivo(s) selecionado(s)? Esta ação não pode ser desfeita.
                </p>
            </div>
            <div class="flex gap-3">
                <button @click="cancelConfirm()" :disabled="deleting"
                    class="flex-1 rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    Cancelar
                </button>
                <button @click="confirmDelete()" :disabled="deleting"
                    class="flex-1 rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <svg x-show="deleting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    <span x-text="deleting ? 'Deletando...' : 'Deletar'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Barra de ações em lote --}}
    <div x-show="someSelected" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="flex items-center justify-between rounded-lg border border-[#f2c700]/30 bg-[#f2c700]/5 px-4 py-2.5">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-[#f2c700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-sm text-white">
                <span x-text="selected.length" class="font-semibold text-[#f2c700]"></span>
                arquivo(s) selecionado(s)
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button @click="selected = []"
                class="text-xs text-gray-400 hover:text-white transition-colors px-2 py-1 rounded">
                Desmarcar tudo
            </button>
            <button @click="askDeleteBatch()"
                class="inline-flex items-center gap-1.5 rounded-md bg-red-600 hover:bg-red-700 px-3 py-1.5 text-xs font-semibold text-white transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Deletar selecionados
            </button>
        </div>
    </div>

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

        {{-- Cabeçalho --}}
        <div class="hidden sm:grid sm:grid-cols-[32px_1fr_130px_100px_96px] border-b border-gray-700 bg-[#1a1a1a] px-4 py-2 gap-4 items-center">
            <div class="flex items-center justify-center">
                <input type="checkbox"
                    :checked="allSelected"
                    @change="toggleAll()"
                    class="h-4 w-4 rounded border-gray-600 bg-gray-700 text-[#f2c700] focus:ring-[#f2c700] focus:ring-offset-0 cursor-pointer accent-[#f2c700]">
            </div>
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
                <div class="px-4 py-3 transition-colors duration-150"
                    :class="isSelected(arquivo.id) ? 'bg-[#1e2820]' : 'hover:bg-[#252525]'">
                    <div class="flex flex-col gap-3 sm:grid sm:grid-cols-[32px_1fr_130px_100px_96px] sm:items-center sm:gap-4">

                        {{-- Checkbox --}}
                        <div class="flex items-center sm:justify-center">
                            <input type="checkbox"
                                :checked="isSelected(arquivo.id)"
                                @change="toggleSelect(arquivo.id)"
                                class="h-4 w-4 rounded border-gray-600 bg-gray-700 focus:ring-offset-0 cursor-pointer accent-[#f2c700]">
                        </div>

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
                            <button @click="askDeleteSingle(arquivo.id, arquivo.nome, arquivo.delete_url)"
                                :disabled="deleting"
                                class="p-1.5 rounded bg-red-900 hover:bg-red-800 text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
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

            {{-- Estado vazio --}}
            <div x-show="filtered.length === 0" class="px-4 py-12 text-center">
                <template x-if="search">
                    <div>
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
                </template>
                <template x-if="!search">
                    <p class="text-sm text-gray-400">Nenhum arquivo disponível.</p>
                </template>
            </div>
        </div>
    </div>
</div>
