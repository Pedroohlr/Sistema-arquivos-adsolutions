@extends('layouts.admin')

@section('title', $grupo->nome)

@section('content')
    <div class="space-y-6">
        <!-- Header com Breadcrumb -->
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <nav class="mb-2 flex flex-wrap items-center gap-2 text-sm text-gray-400">
                    <a href="{{ route('admin.arquivos.index') }}" class="hover:text-[#f2c700]">Arquivos</a>
                    <span>/</span>
                    <span class="text-white">{{ $grupo->nome }}</span>
                </nav>
                <h1 class="text-3xl font-bold text-white">{{ $grupo->nome }}</h1>
                @if($grupo->descricao)
                    <p class="mt-1 text-sm text-gray-400">{{ $grupo->descricao }}</p>
                @endif
            </div>
            <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
                <button onclick="openModal('uploadModal')"
                    class="flex items-center justify-center gap-2 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg shadow-[#f2c700]/20 hover:bg-[#d9b300]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Upload Arquivo
                </button>
                <button onclick="openModal('createSubpastaModal')"
                    class="flex items-center justify-center gap-2 rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white transition-all duration-300 transform hover:scale-105 active:scale-95 hover:bg-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nova Subpasta
                </button>
            </div>
        </div>

        <!-- Arquivos na Raiz do Grupo -->
        <div>
            <h2 class="text-lg font-semibold text-white mb-3 flex items-center gap-2">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Arquivos do Grupo (visíveis para todos os clientes)
            </h2>
            @if($grupo->arquivos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($grupo->arquivos as $arquivo)
                        @include('admin.arquivos.partials.arquivo-card', ['arquivo' => $arquivo])
                    @endforeach
                </div>
            @else
                <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 border-dashed p-12 text-center animate-pulse">
                    <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-gray-400 text-lg mb-2">Nenhum arquivo na raiz do grupo</p>
                    <p class="text-gray-500 text-sm">Clique em "Upload Arquivo" para adicionar arquivos</p>
                </div>
            @endif
        </div>

        <!-- Subpastas -->
        <div>
            <h2 class="text-lg font-semibold text-white mb-3 flex items-center gap-2">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
                Subpastas (acesso restrito por usuário)
            </h2>
            @if($grupo->subpastas->count() > 0)
                <div class="space-y-2">
                    @foreach($grupo->subpastas as $subpasta)
                        <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 overflow-hidden transition-all duration-200"
                            id="accordion-{{ $subpasta->id }}">

                            {{-- Cabeçalho clicável --}}
                            <div class="flex cursor-pointer select-none flex-col gap-3 px-4 py-3 transition-colors hover:bg-[#252525] sm:flex-row sm:items-center sm:justify-between"
                                onclick="toggleAccordion({{ $subpasta->id }})">
                                <div class="flex min-w-0 items-start gap-3 sm:items-center">
                                    {{-- Chevron --}}
                                    <svg id="chevron-{{ $subpasta->id }}"
                                        class="w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                    <div class="min-w-0">
                                        <span class="font-semibold text-white">{{ $subpasta->nome }}</span>
                                        <span class="ml-2 text-xs text-gray-500">{{ $subpasta->arquivos->count() }}
                                            arquivo(s)</span>
                                    </div>
                                    {{-- Badges de usuários (inline, truncados) --}}
                                    <div class="hidden sm:flex flex-wrap gap-1 ml-2">
                                        @foreach($subpasta->clientes as $c)
                                            <span
                                                class="px-2 py-0.5 rounded text-xs bg-gray-800 text-[#f2c700]">{{ $c->email }}</span>
                                        @endforeach
                                        @if($subpasta->clientes->isEmpty())
                                            <span class="text-xs text-gray-600 italic">Sem usuário</span>
                                        @endif
                                    </div>
                                </div>
                                {{-- Botões de ação (param propagação para não abrir/fechar) --}}
                                <div class="flex w-full flex-wrap gap-2 sm:w-auto sm:flex-nowrap" onclick="event.stopPropagation()">
                                    <button onclick="uploadToSubpasta({{ $subpasta->id }})"
                                        class="p-1.5 bg-[#f2c700] hover:bg-[#d9b300] rounded text-black transition-colors"
                                        title="Upload">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                    </button>
                                    <button onclick="editSubpasta({{ $subpasta->id }}, '{{ addslashes($subpasta->nome) }}')"
                                        class="p-1.5 bg-gray-700 hover:bg-gray-600 rounded text-white transition-colors"
                                        title="Editar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button onclick="deleteSubpasta({{ $subpasta->id }}, '{{ addslashes($subpasta->nome) }}')"
                                        class="p-1.5 bg-red-900 hover:bg-red-800 rounded text-white transition-colors"
                                        title="Deletar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Corpo colapsável --}}
                            <div id="body-{{ $subpasta->id }}" class="hidden border-t border-gray-800 px-4 py-4">
                                {{-- Gestão de usuários --}}
                                <div class="flex flex-wrap gap-1 mb-4">
                                    @forelse($subpasta->clientes as $c)
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs bg-gray-800 text-[#f2c700]">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                            </svg>
                                            {{ $c->nome }} <span class="text-gray-500">({{ $c->email }})</span>
                                            <form method="POST"
                                                action="{{ route('admin.arquivos.subpastas.clientes.remove', [$subpasta, $c]) }}"
                                                style="display:inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="ml-1 text-gray-500 hover:text-red-400"
                                                    title="Remover acesso">&times;</button>
                                            </form>
                                        </span>
                                    @empty
                                        <span class="text-xs text-gray-500 italic">Nenhum usuário vinculado</span>
                                    @endforelse
                                    <button onclick="openAddClienteModal({{ $subpasta->id }})"
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs bg-gray-700 hover:bg-gray-600 text-white transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Adicionar usuário
                                    </button>
                                </div>

                                {{-- Arquivos --}}
                                @if($subpasta->arquivos->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($subpasta->arquivos as $arquivo)
                                            @include('admin.arquivos.partials.arquivo-card', ['arquivo' => $arquivo])
                                        @endforeach
                                    </div>
                                @else
                                    <div class="border border-gray-700 border-dashed rounded-lg p-8 text-center">
                                        <svg class="mx-auto h-10 w-10 text-gray-600 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                        </svg>
                                        <p class="text-sm text-gray-400">Nenhum arquivo nesta pasta</p>
                                        <button onclick="uploadToSubpasta({{ $subpasta->id }})"
                                            class="mt-2 text-xs text-[#f2c700] hover:text-[#d9b300] transition-colors">
                                            Clique para adicionar arquivos
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 border-dashed p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    <p class="text-gray-400 text-lg mb-2">Nenhuma subpasta criada</p>
                    <p class="text-gray-500 text-sm mb-4">Crie uma subpasta para organizar arquivos por cliente</p>
                    <button onclick="openModal('createSubpastaModal')"
                        class="inline-flex items-center gap-2 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Criar Primeira Subpasta
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal: Upload Arquivo -->
    <div id="uploadModal" x-data="{ open: false, uploading: false }" x-show="open" x-cloak
        @keydown.escape.window="open = false"
        class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" style="display: none;"
        onclick="if(event.target === this) { closeUploadModal(); }">
        <div class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md border border-gray-800 transform transition-all"
            onclick="event.stopPropagation();" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-lg font-semibold text-white mb-4">Upload de Arquivos</h3>
            <form action="{{ route('admin.arquivos.upload') }}" method="POST" enctype="multipart/form-data"
                @submit="uploading = true">
                @csrf
                <input type="hidden" name="grupo_id" value="{{ $grupo->id }}">
                <input type="hidden" name="subpasta_id" id="upload_subpasta_id" value="">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Local</label>
                        <select id="upload_local"
                            class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700] transition-all"
                            onchange="updateUploadLocation()">
                            <option value="">Raiz de {{ $grupo->nome }} (visível para todos)</option>
                            @foreach($grupo->subpastas as $subpasta)
                                <option value="{{ $subpasta->id }}">{{ $subpasta->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Arquivo</label>
                        <div class="mt-2 flex items-center justify-center w-full">
                            <label id="dropZone"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-700 border-dashed rounded-lg cursor-pointer bg-[#171717] hover:bg-[#1e1e1e] hover:border-[#f2c700] transition-all duration-300">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-400"><span class="font-semibold text-[#f2c700]">Clique
                                            para upload</span> ou arraste e solte</p>
                                    <p class="text-xs text-gray-500">Múltiplos arquivos • Máx. 500MB cada</p>
                                </div>
                                <input id="fileInput" type="file" name="arquivos[]" required multiple class="hidden"
                                    onchange="updateFileName(this)">
                                <span id="fileName" class="text-sm text-gray-400 mt-2 block text-center pb-2"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" id="btnCloseUploadModal"
                        class="flex-1 rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" :disabled="uploading"
                        class="flex-1 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <svg x-show="uploading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span x-text="uploading ? 'Enviando...' : 'Upload'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Criar Subpasta -->
    <div id="createSubpastaModal" x-data="{ open: false }" x-show="open" x-cloak @keydown.escape.window="open = false"
        class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" style="display: none;"
        onclick="if(event.target === this) { closeCreateSubpastaModal(); }">
        <div class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md border border-gray-800 transform transition-all"
            onclick="event.stopPropagation();" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <h3 class="text-lg font-semibold text-white mb-4">Nova Pasta</h3>
            <form action="{{ route('admin.arquivos.subpastas.store', $grupo) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Nome da Pasta</label>
                        <input type="text" name="nome" required
                            class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                            placeholder="Ex: Documentos Financeiros">
                        <p class="mt-1 text-xs text-gray-400">Após criar, você pode adicionar usuários à pasta</p>
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" id="btnCloseCreateSubpasta"
                        class="flex-1 rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 active:scale-95">
                        Criar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                // Remover hidden e mostrar o modal
                modal.classList.remove('hidden');
                modal.style.display = 'flex';

                // Prevenir scroll do body
                document.body.style.overflow = 'hidden';

                // Se o modal usa Alpine.js, atualizar o estado
                if (window.Alpine && modal.hasAttribute('x-data')) {
                    try {
                        if (modal.__x) {
                            modal.__x.$data.open = true;
                        } else {
                            window.Alpine.initTree(modal);
                            setTimeout(() => {
                                if (modal.__x) {
                                    modal.__x.$data.open = true;
                                }
                            }, 50);
                        }
                    } catch (e) {
                        console.warn('Erro ao abrir modal com Alpine.js:', e);
                    }
                }
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';

                // Se o modal usa Alpine.js, atualizar o estado
                if (window.Alpine && modal.hasAttribute('x-data')) {
                    try {
                        const data = window.Alpine.$data(modal);
                        if (data && typeof data.open !== 'undefined') {
                            data.open = false;
                        }
                    } catch (e) {
                        // Ignorar erro
                    }
                }
            }
        }

        // Função closeModal já está definida acima

        function arquivosSubpastaUpdateUrl(id) {
            return '{{ route('admin.arquivos.subpastas.update', '__ID__') }}'.replace('__ID__', id);
        }

        function arquivosSubpastaDestroyUrl(id) {
            return '{{ route('admin.arquivos.subpastas.destroy', '__ID__') }}'.replace('__ID__', id);
        }

        function arquivosSubpastaClientesAddUrl(id) {
            return '{{ route('admin.arquivos.subpastas.clientes.add', '__ID__') }}'.replace('__ID__', id);
        }

        function arquivosSubpastaClientesCreateUrl(id) {
            return '{{ route('admin.arquivos.subpastas.clientes.create', '__ID__') }}'.replace('__ID__', id);
        }

        function arquivosSubpastaClientesAddAdminUrl(id) {
            return '{{ route('admin.arquivos.subpastas.clientes.add-admin', '__ID__') }}'.replace('__ID__', id);
        }

        function usuariosSearchUrl() {
            return '{{ route('admin.usuarios.search') }}';
        }

        function uploadToSubpasta(subpastaId) {
            // Atualizar o select
            const select = document.getElementById('upload_local');
            if (select) {
                select.value = subpastaId;
                updateUploadLocation();
            }

            // Abrir o modal
            openModal('uploadModal');
        }

        function updateUploadLocation() {
            const select = document.getElementById('upload_local');
            document.getElementById('upload_subpasta_id').value = select.value;
        }

        function updateFileName(input) {
            const fileNameSpan = document.getElementById('fileName');
            if (!fileNameSpan) return;
            const count = input.files.length;
            if (count === 0) {
                fileNameSpan.textContent = '';
            } else if (count === 1) {
                fileNameSpan.textContent = `✓ ${input.files[0].name}`;
            } else {
                fileNameSpan.textContent = `✓ ${count} arquivos selecionados`;
            }
        }

        function editSubpasta(id, nome) {
            document.getElementById('editSubpastaId').value = id;
            document.getElementById('editSubpastaNome').value = nome;
            document.getElementById('formEditSubpasta').action = arquivosSubpastaUpdateUrl(id);
            const modal = document.getElementById('editSubpastaModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(() => document.getElementById('editSubpastaNome').focus(), 50);
        }

        function closeEditSubpastaModal() {
            const modal = document.getElementById('editSubpastaModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        function closeUploadModal() {
            const modal = document.getElementById('uploadModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                // Se usar Alpine.js, atualizar o estado
                if (window.Alpine && modal.hasAttribute('x-data')) {
                    try {
                        if (modal.__x) {
                            modal.__x.$data.open = false;
                            modal.__x.$data.uploading = false; // Resetar estado de upload
                        }
                    } catch (e) {
                        // Ignorar erro
                    }
                }
                // Limpar o formulário
                const form = modal.querySelector('form');
                if (form) {
                    form.reset();
                    document.getElementById('upload_subpasta_id').value = '';
                    document.getElementById('upload_local').value = '';
                    const fileNameSpan = document.getElementById('fileName');
                    if (fileNameSpan) {
                        fileNameSpan.textContent = '';
                    }
                }
                document.body.style.overflow = '';
            }
        }

        function closeCreateSubpastaModal() {
            const modal = document.getElementById('createSubpastaModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                // Se usar Alpine.js, atualizar o estado
                if (window.Alpine && modal.hasAttribute('x-data')) {
                    try {
                        if (modal.__x) {
                            modal.__x.$data.open = false;
                        }
                    } catch (e) {
                        // Ignorar erro
                    }
                }
                document.body.style.overflow = '';
            }
        }

        // Event listener para o botão de cancelar do modal de upload
        (function () {
            function setupUploadCloseButton() {
                const closeBtn = document.getElementById('btnCloseUploadModal');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        closeUploadModal();
                    });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setupUploadCloseButton);
            } else {
                setupUploadCloseButton();
            }

            setTimeout(setupUploadCloseButton, 100);
        })();

        // Event listener para o botão de cancelar do modal de criar subpasta
        (function () {
            function setupCloseButton() {
                const closeBtn = document.getElementById('btnCloseCreateSubpasta');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        closeCreateSubpastaModal();
                    });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setupCloseButton);
            } else {
                setupCloseButton();
            }

            setTimeout(setupCloseButton, 100);
        })();

        function deleteSubpasta(id, nome) {
            if (confirm(`⚠️ Tem certeza que deseja deletar a pasta "${nome}"?\n\nTodos os arquivos serão removidos permanentemente!\n\nEsta ação não pode ser desfeita.`)) {
                const button = event.target.closest('button');
                if (button) {
                    button.disabled = true;
                    button.classList.add('opacity-50', 'cursor-not-allowed');
                }

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = arquivosSubpastaDestroyUrl(id);

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

        function toggleAccordion(id) {
            const body = document.getElementById('body-' + id);
            const chevron = document.getElementById('chevron-' + id);
            const isOpen = !body.classList.contains('hidden');
            if (isOpen) {
                body.classList.add('hidden');
                chevron.style.transform = 'rotate(0deg)';
            } else {
                body.classList.remove('hidden');
                chevron.style.transform = 'rotate(90deg)';
            }
        }

        let _addClienteSubpastaId = null;

        function openAddClienteModal(subpastaId) {
            _addClienteSubpastaId = subpastaId;
            document.getElementById('formAddExistente').action = arquivosSubpastaClientesAddUrl(subpastaId);
            document.getElementById('formAddAdmin').action = arquivosSubpastaClientesAddAdminUrl(subpastaId);
            document.getElementById('formCriarCliente').action = arquivosSubpastaClientesCreateUrl(subpastaId);
            document.getElementById('searchClienteInput').value = '';
            document.getElementById('searchHint').textContent = 'Digite pelo menos 4 caracteres para buscar.';
            document.getElementById('searchResultados').classList.add('hidden');
            document.getElementById('searchResultados').innerHTML = '';
            document.getElementById('clienteSelecionadoId').value = '';
            document.getElementById('clienteSelecionadoSource').value = 'cliente';
            document.getElementById('clienteSelecionado').classList.add('hidden');
            document.getElementById('formAddExistente').classList.remove('hidden');
            document.getElementById('formAddAdmin').classList.add('hidden');
            switchAddClienteTab('existente');
            const modal = document.getElementById('addClienteModal');
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeAddClienteModal() {
            const modal = document.getElementById('addClienteModal');
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        function switchAddClienteTab(tab) {
            const contEx = document.getElementById('tabContentExistente');
            const contNovo = document.getElementById('tabContentNovo');
            const btnEx = document.getElementById('tabBtnExistente');
            const btnNovo = document.getElementById('tabBtnNovo');

            if (tab === 'existente') {
                contEx.classList.remove('hidden');
                contNovo.classList.add('hidden');
                btnEx.classList.add('text-[#f2c700]', 'border-[#f2c700]');
                btnEx.classList.remove('text-gray-400', 'border-transparent');
                btnNovo.classList.remove('text-[#f2c700]', 'border-[#f2c700]');
                btnNovo.classList.add('text-gray-400', 'border-transparent');
            } else {
                contNovo.classList.remove('hidden');
                contEx.classList.add('hidden');
                btnNovo.classList.add('text-[#f2c700]', 'border-[#f2c700]');
                btnNovo.classList.remove('text-gray-400', 'border-transparent');
                btnEx.classList.remove('text-[#f2c700]', 'border-[#f2c700]');
                btnEx.classList.add('text-gray-400', 'border-transparent');
            }
        }

        let _searchDebounce = null;
        function searchClientes(q) {
            clearTimeout(_searchDebounce);
            const hint = document.getElementById('searchHint');

            if (!q || q.length < 4) {
                if (hint) {
                    hint.textContent = 'Digite pelo menos 4 caracteres para buscar.';
                }
                document.getElementById('searchResultados').classList.add('hidden');
                document.getElementById('searchResultados').innerHTML = '';
                return;
            }

            if (hint) {
                hint.textContent = 'Buscando usuários...';
            }

            _searchDebounce = setTimeout(() => {
                fetch(`${usuariosSearchUrl()}?q=${encodeURIComponent(q)}`)
                    .then(r => r.json())
                    .then(data => {
                        const cont = document.getElementById('searchResultados');
                        if (!data.length) {
                            cont.innerHTML = '<div class="px-3 py-2 text-sm text-gray-500">Nenhum usuário encontrado</div>';
                        } else {
                            cont.innerHTML = data.map(c =>
                                `<div class="px-3 py-2 text-sm text-white hover:bg-gray-700 cursor-pointer flex items-center justify-between" onclick="selecionarCliente(${c.id}, '${c.nome.replace(/'/g, "\\'")} (${c.email})', '${c.source}')">
                                    <span>${c.nome} <span class="text-gray-400">${c.email}</span></span>
                                    ${c.source === 'admin' ? '<span class="ml-2 text-xs bg-[#f2c700] text-black px-1.5 py-0.5 rounded">Admin</span>' : ''}
                                </div>`
                            ).join('');
                        }
                        if (hint) {
                            hint.textContent = `${data.length} usuário(s) encontrado(s).`;
                        }
                        cont.classList.remove('hidden');
                    })
                    .catch(() => {
                        const cont = document.getElementById('searchResultados');
                        if (hint) {
                            hint.textContent = 'Não foi possível buscar os usuários agora.';
                        }
                        cont.innerHTML = '<div class="px-3 py-2 text-sm text-red-300">Erro ao buscar usuários</div>';
                        cont.classList.remove('hidden');
                    });
            }, 300);
        }

        function selecionarCliente(id, texto, source) {
            document.getElementById('clienteSelecionadoId').value = id;
            document.getElementById('clienteSelecionadoSource').value = source || 'cliente';
            document.getElementById('clienteSelecionadoTexto').textContent = '✓ ' + texto;
            document.getElementById('clienteSelecionado').classList.remove('hidden');
            document.getElementById('searchResultados').classList.add('hidden');
            document.getElementById('searchClienteInput').value = texto;
            document.getElementById('searchHint').textContent = 'Usuário selecionado.';

            // Alterna o form de submit conforme o tipo
            const isAdmin = source === 'admin';
            document.getElementById('formAddExistente').classList.toggle('hidden', isAdmin);
            document.getElementById('formAddAdmin').classList.toggle('hidden', !isAdmin);
            if (isAdmin) {
                document.getElementById('adminSelecionadoId').value = id;
            }
        }
    </script>

    <!-- Modal: Editar Subpasta -->
    <div id="editSubpastaModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 items-center justify-center p-4 hidden"
        style="display:none;" onclick="if(event.target === this) closeEditSubpastaModal();">
        <div class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md border border-gray-800" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Editar Pasta</h3>
                <button onclick="closeEditSubpastaModal()" class="text-gray-400 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <form id="formEditSubpasta" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" id="editSubpastaId">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Nome da Pasta</label>
                        <input type="text" name="nome" id="editSubpastaNome" required
                            class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                            placeholder="Nome da pasta">
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeEditSubpastaModal()"
                        class="flex-1 rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 active:scale-95">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Adicionar Cliente à Pasta -->
    <div id="addClienteModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4 hidden"
        style="display:none;" onclick="if(event.target === this) closeAddClienteModal();">
        <div class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md border border-gray-800" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Adicionar Usuário à Pasta</h3>
                <button onclick="closeAddClienteModal()"
                    class="text-gray-400 hover:text-white text-2xl leading-none">&times;</button>
            </div>

            <!-- Tabs -->
            <div class="flex gap-0 mb-4 border-b border-gray-700">
                <button id="tabBtnExistente" onclick="switchAddClienteTab('existente')"
                    class="px-4 py-2 text-sm font-medium text-[#f2c700] border-b-2 border-[#f2c700] transition-colors">
                    Usuário Existente
                </button>
                <button id="tabBtnNovo" onclick="switchAddClienteTab('novo')"
                    class="px-4 py-2 text-sm font-medium text-gray-400 border-b-2 border-transparent hover:text-white transition-colors">
                    Novo Usuário
                </button>
            </div>

            <!-- Tab: Existente -->
            <div id="tabContentExistente">
                {{-- Campo de busca compartilhado entre os dois forms --}}
                <div class="space-y-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Buscar Usuário</label>
                        <input type="text" id="searchClienteInput" oninput="searchClientes(this.value)"
                            autocomplete="off"
                            class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                            placeholder="Nome, e-mail ou usuário...">
                        <p id="searchHint" class="mt-1 text-xs text-gray-400">Digite pelo menos 4 caracteres para buscar.</p>
                        <div id="searchResultados"
                            class="mt-1 bg-[#171717] rounded-md ring-1 ring-gray-700 hidden max-h-40 overflow-y-auto">
                        </div>
                        <input type="hidden" id="clienteSelecionadoSource" value="cliente">
                        <div id="clienteSelecionado" class="mt-2 hidden">
                            <span class="text-sm text-[#f2c700]" id="clienteSelecionadoTexto"></span>
                        </div>
                    </div>
                </div>

                {{-- Form para cliente existente --}}
                <form id="formAddExistente" method="POST" action="">
                    @csrf
                    <input type="hidden" name="cliente_id" id="clienteSelecionadoId">
                    <div class="mt-4 flex gap-3">
                        <button type="button" onclick="closeAddClienteModal()"
                            class="flex-1 rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="flex-1 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 active:scale-95">
                            Adicionar
                        </button>
                    </div>
                </form>

                {{-- Form para admin (oculto até selecionar um admin) --}}
                <form id="formAddAdmin" method="POST" action="" class="hidden">
                    @csrf
                    <input type="hidden" name="admin_id" id="adminSelecionadoId">
                    <div class="mt-4 flex gap-3">
                        <button type="button" onclick="closeAddClienteModal()"
                            class="flex-1 rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="flex-1 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 active:scale-95">
                            Adicionar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tab: Novo -->
            <div id="tabContentNovo" class="hidden">
                <form id="formCriarCliente" method="POST" action="">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Nome</label>
                            <input type="text" name="nome" required
                                class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                                placeholder="Nome completo">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">E-mail</label>
                            <input type="email" name="email" required
                                class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                                placeholder="email@exemplo.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-white mb-2">Senha</label>
                            <input type="password" name="password" required minlength="6"
                                class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                                placeholder="Mínimo 6 caracteres">
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="button" onclick="closeAddClienteModal()"
                            class="flex-1 rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="flex-1 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 active:scale-95">
                            Criar e Adicionar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        if (!dropZone || !fileInput) return;

        ['dragenter', 'dragover'].forEach(event => {
            dropZone.addEventListener(event, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.add('border-[#f2c700]', 'bg-[#1e1e1e]');
            });
        });

        ['dragleave', 'dragend'].forEach(event => {
            dropZone.addEventListener(event, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropZone.classList.remove('border-[#f2c700]', 'bg-[#1e1e1e]');
            });
        });

        dropZone.addEventListener('drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-[#f2c700]', 'bg-[#1e1e1e]');

            const files = e.dataTransfer.files;
            if (!files || files.length === 0) return;

            // Transfere os arquivos para o input nativo
            const dt = new DataTransfer();
            Array.from(files).forEach(f => dt.items.add(f));
            fileInput.files = dt.files;

            updateFileName(fileInput);
        });
    })();
</script>
@endpush