@extends('layouts.admin')

@section('title', 'Arquivos')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Gerenciar Arquivos</h1>
            <p class="mt-1 text-sm text-gray-400">Crie grupos e organize seus arquivos para os clientes</p>
        </div>
        <button onclick="openModal('createGrupoModal')" 
                class="rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 flex items-center gap-2 transform hover:scale-105 active:scale-95 shadow-lg shadow-[#f2c700]/20 hover:shadow-[#f2c700]/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Criar Grupo
        </button>
    </div>

    <!-- Grid de Grupos -->
    @if($grupos->count() > 0)
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($grupos as $index => $grupo)
                <div class="group relative bg-[#1e1e1e] rounded-lg border border-gray-800 hover:border-[#f2c700] transition-all duration-300 p-6 cursor-pointer transform hover:scale-[1.02] hover:shadow-lg hover:shadow-[#f2c700]/20 stagger-animation card-hover"
                     style="animation-delay: {{ $index * 0.1 }}s"
                     onclick="window.location='{{ route('admin.arquivos.grupo', $grupo) }}'"
                     role="button"
                     tabindex="0"
                     @keydown.enter="window.location='{{ route('admin.arquivos.grupo', $grupo) }}'">
                    <!-- Ícone de pasta -->
                    <div class="mb-4 transform group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-16 h-16 text-[#f2c700] group-hover:text-[#d9b300] transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
                        </svg>
                    </div>

                    <!-- Nome do Grupo -->
                    <h3 class="text-lg font-semibold text-white mb-2">{{ $grupo->nome }}</h3>
                    
                    @if($grupo->descricao)
                        <p class="text-sm text-gray-400 mb-4 line-clamp-2">{{ $grupo->descricao }}</p>
                    @endif

                    <!-- Estatísticas -->
                    <div class="flex items-center gap-4 text-sm text-gray-400">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                            <span>{{ $grupo->subpastas_count }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $grupo->todos_arquivos_count }}</span>
                        </div>
                    </div>

                    <!-- Botões de ação (aparecem no hover) -->
                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 flex gap-2 transform translate-y-2 group-hover:translate-y-0">
                        <button onclick="event.stopPropagation(); editGrupo({{ $grupo->id }}, '{{ addslashes($grupo->nome) }}', '{{ addslashes($grupo->descricao ?? '') }}')" 
                                class="p-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white transition-colors transform hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="event.stopPropagation(); deleteGrupo({{ $grupo->id }}, '{{ addslashes($grupo->nome) }}')" 
                                class="p-2 bg-red-900 hover:bg-red-800 rounded-lg text-white transition-colors transform hover:scale-110">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-[#1e1e1e] rounded-lg border border-gray-800">
            <div class="animate-pulse">
                <svg class="mx-auto h-20 w-20 text-gray-600 mb-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
                </svg>
            </div>
            <h3 class="mt-2 text-xl font-semibold text-white">Nenhum grupo criado</h3>
            <p class="mt-2 text-sm text-gray-400 max-w-md mx-auto">Comece criando um grupo para organizar seus arquivos e começar a trabalhar.</p>
            <div class="mt-8">
                <button onclick="openModal('createGrupoModal')" 
                        class="inline-flex items-center gap-2 rounded-md bg-[#f2c700] px-6 py-3 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 shadow-lg shadow-[#f2c700]/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Criar Primeiro Grupo
                </button>
            </div>
        </div>
    @endif
</div>

<!-- Modal: Criar Grupo -->
<div id="createGrupoModal" 
     x-data="{ open: false }"
     x-show="open"
     x-cloak
     @keydown.escape.window="open = false"
     class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div @click.away="open = false"
         class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md border border-gray-800 transform transition-all"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        <h3 class="text-lg font-semibold text-white mb-4">Criar Novo Grupo</h3>
        <form action="{{ route('admin.arquivos.grupos.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Nome do Grupo</label>
                    <input type="text" name="nome" required
                           class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Descrição (opcional)</label>
                    <textarea name="descricao" rows="3"
                              class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"></textarea>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" @click="open = false"
                        class="flex-1 rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 active:scale-95">
                    Criar Grupo
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Editar Grupo -->
<div id="editGrupoModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md border border-gray-800">
        <h3 class="text-lg font-semibold text-white mb-4">Editar Grupo</h3>
        <form id="editGrupoForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Nome do Grupo</label>
                    <input type="text" name="nome" id="edit_nome" required
                           class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Descrição (opcional)</label>
                    <textarea name="descricao" id="edit_descricao" rows="3"
                              class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"></textarea>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" id="btnCloseEditGrupo"
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

<!-- Modal: Deletar Grupo -->
<div id="deleteGrupoModal" 
     x-data="{ open: false }"
     x-show="open"
     x-cloak
     @keydown.escape.window="open = false"
     class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4"
     style="display: none;"
     onclick="if(event.target === this) { closeDeleteGrupoModal(); }">
    <div class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md border border-red-800/50 transform transition-all"
         onclick="event.stopPropagation();"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-red-900/30 rounded-lg">
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-red-400">Deletar Grupo</h3>
        </div>
        <p class="text-white mb-2">Tem certeza que deseja deletar o grupo <strong class="text-red-400" id="delete_grupo_nome"></strong>?</p>
        <p class="text-sm text-gray-400 mb-6 flex items-start gap-2">
            <svg class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span>Todos os arquivos, subpastas e usuários serão deletados permanentemente!</span>
        </p>
        <form id="deleteGrupoForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" id="btnCloseDeleteGrupo"
                        class="flex-1 rounded-md bg-gray-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-600 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 rounded-md bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition-colors transform hover:scale-105 active:scale-95">
                    Deletar Permanentemente
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Usar a função global openModal se disponível, senão definir localmente
if (typeof window.openModal === 'undefined') {
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            // Remover hidden e mostrar o modal
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            
            // Se o modal usa Alpine.js, atualizar o estado
            if (window.Alpine && modal.hasAttribute('x-data')) {
                try {
                    const data = window.Alpine.$data(modal);
                    if (data && typeof data.open !== 'undefined') {
                        data.open = true;
                    }
                } catch (e) {
                    // Se Alpine não está pronto, tentar inicializar
                    if (window.Alpine.initTree) {
                        window.Alpine.initTree(modal);
                        setTimeout(() => {
                            try {
                                const data = window.Alpine.$data(modal);
                                if (data && typeof data.open !== 'undefined') {
                                    data.open = true;
                                }
                            } catch (e2) {
                                console.warn('Alpine.js não conseguiu inicializar o modal:', e2);
                            }
                        }, 50);
                    }
                }
            }
            
            // Prevenir scroll do body
            document.body.style.overflow = 'hidden';
        }
    };
}

// Usar a função global closeModal se disponível, senão definir localmente
if (typeof window.closeModal === 'undefined') {
    window.closeModal = function(modalId) {
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
            
            // Restaurar scroll do body
            document.body.style.overflow = '';
        }
    };
}

// Função closeModal já está definida acima como window.closeModal

function editGrupo(id, nome, descricao) {
    document.getElementById('editGrupoForm').action = `/admin/arquivos/grupos/${id}`;
    document.getElementById('edit_nome').value = nome;
    document.getElementById('edit_descricao').value = descricao || '';
    openModal('editGrupoModal');
}

// Event listener para fechar o modal de editar grupo
(function() {
    function setupCloseButton() {
        const closeBtn = document.getElementById('btnCloseEditGrupo');
        const modal = document.getElementById('editGrupoModal');
        
        if (closeBtn && modal) {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = '';
            });
        }
    }
    
    // Tentar configurar quando o DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupCloseButton);
    } else {
        setupCloseButton();
    }
    
    // Também tentar após um pequeno delay para garantir que o elemento existe
    setTimeout(setupCloseButton, 100);
})();

function deleteGrupo(id, nome) {
    document.getElementById('deleteGrupoForm').action = `/admin/arquivos/grupos/${id}`;
    document.getElementById('delete_grupo_nome').textContent = nome;
    const modal = document.getElementById('deleteGrupoModal');
    if (modal) {
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
        // Se usar Alpine.js, atualizar o estado
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
        document.body.style.overflow = 'hidden';
    }
}

function closeDeleteGrupoModal() {
    const modal = document.getElementById('deleteGrupoModal');
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

// Event listener para o botão de cancelar do modal de deletar grupo
(function() {
    function setupCloseButton() {
        const closeBtn = document.getElementById('btnCloseDeleteGrupo');
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeDeleteGrupoModal();
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

// Fechar modal ao clicar fora
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            if (modal.id === 'deleteGrupoModal') {
                closeDeleteGrupoModal();
            } else {
                modal.classList.add('hidden');
                modal.style.display = 'none';
            }
        }
    });
});
</script>
@endsection
