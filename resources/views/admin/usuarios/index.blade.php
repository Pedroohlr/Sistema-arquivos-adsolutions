@extends('layouts.admin')

@section('title', 'Usuários')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Gerenciar Usuários</h1>
            <p class="mt-1 text-sm text-gray-400">Liste e edite as credenciais dos clientes</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 p-6">
        <form method="GET" action="{{ route('admin.usuarios.index') }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-white mb-2">Buscar por usuário</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Digite o nome do usuário..."
                       class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-white mb-2">Filtrar por grupo</label>
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
                <button type="submit" 
                        class="rounded-md bg-[#f2c700] px-6 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg shadow-[#f2c700]/20">
                    Filtrar
                </button>
            </div>
            @if(request('search') || request('grupo_id'))
                <div>
                    <a href="{{ route('admin.usuarios.index') }}" 
                       class="rounded-md bg-gray-700 px-6 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors inline-block">
                        Limpar
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Tabela de Usuários -->
    @if($usuarios->count() > 0)
        <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-[#171717]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Usuário
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Grupo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Subpasta
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Criado em
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($usuarios as $usuario)
                            <tr class="hover:bg-[#171717] transition-all duration-200 cursor-pointer">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-[#f2c700] rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">{{ $usuario->usuario }}</div>
                                            <div class="text-sm text-gray-400">ID: {{ $usuario->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-white">{{ $usuario->grupo->nome }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-white">{{ $usuario->nome }}</div>
                                    <div class="text-xs text-gray-400">{{ $usuario->arquivos->count() }} arquivo(s)</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ $usuario->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="editUsuario({{ $usuario->id }}, '{{ $usuario->usuario }}')" 
                                            class="text-[#f2c700] hover:text-[#d9b300] transition-colors">
                                        Editar Credenciais
                                    </button>
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
                Mostrando {{ $usuarios->firstItem() ?? 0 }} a {{ $usuarios->lastItem() ?? 0 }} de {{ $usuarios->total() }} usuários
            </div>
            <div>
                {{ $usuarios->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-16 bg-[#1e1e1e] rounded-lg border border-gray-800">
            <div class="animate-pulse">
                <svg class="mx-auto h-16 w-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h3 class="mt-2 text-lg font-semibold text-white">Nenhum usuário encontrado</h3>
            <p class="mt-2 text-sm text-gray-400 max-w-md mx-auto">
                @if(request('search') || request('grupo_id'))
                    Tente ajustar os filtros ou limpar a busca para ver mais resultados.
                @else
                    Crie subpastas nos grupos para adicionar usuários automaticamente.
                @endif
            </p>
            @if(!request('search') && !request('grupo_id'))
                <a href="{{ route('admin.arquivos.index') }}" 
                   class="mt-6 inline-flex items-center gap-2 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    Ir para Arquivos
                </a>
            @endif
        </div>
    @endif
</div>

<!-- Modal: Editar Credenciais -->
<div id="editUsuarioModal" 
     x-data="{ open: false }"
     x-show="open"
     x-cloak
     @keydown.escape.window="open = false"
     class="fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4"
     style="display: none;"
     onclick="if(event.target === this) { closeEditUsuarioModal(); }">
    <div class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md border border-gray-800 transform transition-all"
         onclick="event.stopPropagation();"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        <h3 class="text-lg font-semibold text-white mb-4">Editar Credenciais</h3>
        <form id="editUsuarioForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Nome de Usuário</label>
                    <input type="text" name="usuario" id="edit_usuario" required
                           class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
                    <p class="mt-1 text-xs text-gray-400">Deve ser único no sistema</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-white mb-2">Nova Senha (opcional)</label>
                    <input type="password" name="password" id="edit_password" minlength="4"
                           placeholder="Deixe em branco para manter a atual"
                           class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
                    <p class="mt-1 text-xs text-gray-400">Mínimo 4 caracteres. Deixe vazio para não alterar.</p>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" id="btnCloseEditUsuario"
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

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function editUsuario(id, usuario) {
    document.getElementById('editUsuarioForm').action = `/admin/usuarios/${id}`;
    document.getElementById('edit_usuario').value = usuario;
    document.getElementById('edit_password').value = '';
    const modal = document.getElementById('editUsuarioModal');
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

function closeEditUsuarioModal() {
    const modal = document.getElementById('editUsuarioModal');
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

// Event listener para o botão de cancelar
(function() {
    function setupCloseButton() {
        const closeBtn = document.getElementById('btnCloseEditUsuario');
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeEditUsuarioModal();
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
</script>
@endsection
