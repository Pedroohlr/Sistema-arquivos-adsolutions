@extends('layouts.admin')

@section('title', $grupo->nome)

@section('content')
<div class="space-y-6">
    <!-- Header com Breadcrumb -->
    <div class="flex items-center justify-between">
        <div>
            <nav class="flex items-center gap-2 text-sm text-gray-400 mb-2">
                <a href="{{ route('admin.arquivos.index') }}" class="hover:text-[#f2c700]">Arquivos</a>
                <span>/</span>
                <span class="text-white">{{ $grupo->nome }}</span>
            </nav>
            <h1 class="text-3xl font-bold text-white">{{ $grupo->nome }}</h1>
            @if($grupo->descricao)
                <p class="mt-1 text-sm text-gray-400">{{ $grupo->descricao }}</p>
            @endif
        </div>
        <div class="flex gap-3">
            <button onclick="openModal('uploadModal')" 
                    class="rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 flex items-center gap-2 transform hover:scale-105 active:scale-95 shadow-lg shadow-[#f2c700]/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Upload Arquivo
            </button>
            <button onclick="openModal('createSubpastaModal')" 
                    class="rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-all duration-300 flex items-center gap-2 transform hover:scale-105 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nova Subpasta
            </button>
        </div>
    </div>

    <!-- Arquivos na Raiz do Grupo -->
    <div>
        <h2 class="text-lg font-semibold text-white mb-3 flex items-center gap-2">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            Subpastas (acesso restrito por usuário)
        </h2>
        @if($grupo->subpastas->count() > 0)
            <div class="space-y-4">
                @foreach($grupo->subpastas as $subpasta)
                    <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 p-6 hover:border-[#f2c700]/50 transition-all duration-300 transform hover:scale-[1.01]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-white">{{ $subpasta->nome }}</h3>
                                    <p class="text-sm text-gray-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Usuário: <span class="text-[#f2c700]">{{ $subpasta->usuario }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="uploadToSubpasta({{ $subpasta->id }})" 
                                        class="p-2 bg-[#f2c700] hover:bg-[#d9b300] rounded-lg text-black transition-all duration-300 transform hover:scale-110"
                                        title="Upload para esta subpasta">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </button>
                                <button onclick="editSubpasta({{ $subpasta->id }}, '{{ addslashes($subpasta->nome) }}')"
                                        class="p-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white transition-all duration-300 transform hover:scale-110"
                                        title="Editar subpasta">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button onclick="deleteSubpasta({{ $subpasta->id }}, '{{ addslashes($subpasta->nome) }}')"
                                        class="p-2 bg-red-900 hover:bg-red-800 rounded-lg text-white transition-all duration-300 transform hover:scale-110"
                                        title="Deletar subpasta">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Arquivos da Subpasta -->
                        @if($subpasta->arquivos->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($subpasta->arquivos as $arquivo)
                                    @include('admin.arquivos.partials.arquivo-card', ['arquivo' => $arquivo])
                                @endforeach
                            </div>
                        @else
                            <div class="border border-gray-700 border-dashed rounded-lg p-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                                <p class="text-sm text-gray-400">Nenhum arquivo nesta subpasta</p>
                                <button onclick="uploadToSubpasta({{ $subpasta->id }})" 
                                        class="mt-3 text-xs text-[#f2c700] hover:text-[#d9b300] transition-colors">
                                    Clique para adicionar arquivos
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 border-dashed p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                <p class="text-gray-400 text-lg mb-2">Nenhuma subpasta criada</p>
                <p class="text-gray-500 text-sm mb-4">Crie uma subpasta para organizar arquivos por cliente</p>
                <button onclick="openModal('createSubpastaModal')" 
                        class="inline-flex items-center gap-2 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Criar Primeira Subpasta
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Modal: Upload Arquivo -->
<div id="uploadModal" 
     x-data="{ open: false, uploading: false }"
     x-show="open"
     x-cloak
     @keydown.escape.window="open = false"
     class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4"
     style="display: none;"
     onclick="if(event.target === this) { closeUploadModal(); }">
    <div class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md border border-gray-800 transform transition-all"
         onclick="event.stopPropagation();"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        <h3 class="text-lg font-semibold text-white mb-4">Upload de Arquivo</h3>
        <form action="{{ route('admin.arquivos.upload') }}" method="POST" enctype="multipart/form-data"
              @submit="uploading = true">
            @csrf
            <input type="hidden" name="grupo_id" value="{{ $grupo->id }}">
            <input type="hidden" name="subpasta_id" id="upload_subpasta_id" value="">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Local</label>
                    <select id="upload_local" class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700] transition-all" onchange="updateUploadLocation()">
                        <option value="">Raiz do Grupo (visível para todos)</option>
                        @foreach($grupo->subpastas as $subpasta)
                            <option value="{{ $subpasta->id }}">{{ $subpasta->nome }} (apenas {{ $subpasta->usuario }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Arquivo</label>
                    <div class="mt-2 flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-700 border-dashed rounded-lg cursor-pointer bg-[#171717] hover:bg-[#1e1e1e] hover:border-[#f2c700] transition-all duration-300">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-400"><span class="font-semibold text-[#f2c700]">Clique para upload</span> ou arraste e solte</p>
                                <p class="text-xs text-gray-500">Tamanho máximo: 100MB</p>
                            </div>
                            <input type="file" name="arquivo" required class="hidden" onchange="updateFileName(this)">
                            <span id="fileName" class="text-sm text-gray-400 mt-2"></span>
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
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="uploading ? 'Enviando...' : 'Upload'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Criar Subpasta -->
<div id="createSubpastaModal" 
     x-data="{ open: false }"
     x-show="open"
     x-cloak
     @keydown.escape.window="open = false"
     class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4"
     style="display: none;"
     onclick="if(event.target === this) { closeCreateSubpastaModal(); }">
    <div class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md border border-gray-800 transform transition-all"
         onclick="event.stopPropagation();"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        <h3 class="text-lg font-semibold text-white mb-4">Nova Subpasta</h3>
        <form action="{{ route('admin.arquivos.subpastas.store', $grupo) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Nome da Subpasta</label>
                    <input type="text" name="nome" required
                           class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Usuário (único)</label>
                    <input type="text" name="usuario" required
                           class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
                    <p class="mt-1 text-xs text-gray-400">Este usuário terá acesso apenas a esta subpasta</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Senha</label>
                    <input type="password" name="password" required minlength="4"
                           class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
                    <p class="mt-1 text-xs text-gray-400">Mínimo 4 caracteres</p>
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
    const fileName = input.files[0]?.name || '';
    const fileNameSpan = document.getElementById('fileName');
    if (fileNameSpan) {
        fileNameSpan.textContent = fileName ? `Arquivo selecionado: ${fileName}` : '';
    }
}

function editSubpasta(id, nome) {
    // Implementar modal de edição
    alert('Editar subpasta: ' + nome + ' (ID: ' + id + ')');
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
(function() {
    function setupUploadCloseButton() {
        const closeBtn = document.getElementById('btnCloseUploadModal');
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
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
(function() {
    function setupCloseButton() {
        const closeBtn = document.getElementById('btnCloseCreateSubpasta');
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
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
    if (confirm(`⚠️ Tem certeza que deseja deletar a subpasta "${nome}"?\n\nTodos os arquivos e o usuário serão removidos permanentemente!\n\nEsta ação não pode ser desfeita.`)) {
        // Adicionar loading visual
        const button = event.target.closest('button');
        if (button) {
            button.disabled = true;
            button.classList.add('opacity-50', 'cursor-not-allowed');
        }
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/arquivos/subpastas/${id}`;
        
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
@endsection
