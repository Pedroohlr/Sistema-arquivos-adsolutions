@extends('layouts.admin')

@section('title', 'Usuários')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Gerenciar Usuários</h1>
            <p class="mt-1 text-sm text-gray-400">Crie e gerencie os usuários do sistema</p>
        </div>
        <button onclick="openModal('createUsuarioModal')"
                class="flex items-center gap-2 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg shadow-[#f2c700]/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Novo Usuário
        </button>
    </div>

    @if(session('success'))
        <div class="rounded-lg bg-green-900/40 border border-green-700 p-4 text-green-300 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="rounded-lg bg-red-900/40 border border-red-700 p-4 text-red-300 text-sm">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <!-- Filtros -->
    <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 p-4">
        <form method="GET" action="{{ route('admin.usuarios.index') }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-white mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nome ou usuário..."
                       class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
            </div>
            <button type="submit" class="rounded-md bg-[#f2c700] px-6 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-colors">Filtrar</button>
            @if(request('search'))
                <a href="{{ route('admin.usuarios.index') }}" class="rounded-md bg-gray-700 px-6 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors">Limpar</a>
            @endif
        </form>
    </div>

    <!-- Tabela de Usuários -->
    @if($clientes->count() > 0)
        <div class="bg-[#1e1e1e] rounded-lg border border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-[#171717]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Usuário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Pastas com Acesso</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Criado em</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($clientes as $cliente)
                            <tr class="hover:bg-[#171717] transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 bg-[#f2c700] rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-white">{{ $cliente->nome }}</div>
                                            <div class="text-xs text-gray-400">@{{ $cliente->usuario }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($cliente->subpastas->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($cliente->subpastas as $subpasta)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs bg-gray-800 text-gray-300">
                                                    <span class="text-[#f2c700]">{{ $subpasta->grupo->nome ?? '—' }}</span>
                                                    <span>/</span>
                                                    {{ $subpasta->nome }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500 italic">Sem pastas vinculadas</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ $cliente->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="editUsuario({{ $cliente->id }}, '{{ addslashes($cliente->nome) }}', '{{ addslashes($cliente->usuario) }}')"
                                                class="text-xs text-[#f2c700] hover:text-[#d9b300] px-2 py-1 rounded hover:bg-gray-800 transition-colors">
                                            Editar
                                        </button>
                                        <button onclick="deleteUsuario({{ $cliente->id }}, '{{ addslashes($cliente->nome) }}')"
                                                class="text-xs text-red-400 hover:text-red-300 px-2 py-1 rounded hover:bg-gray-800 transition-colors">
                                            Remover
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex items-center justify-between text-sm text-gray-400">
            <span>Mostrando {{ $clientes->firstItem() ?? 0 }}–{{ $clientes->lastItem() ?? 0 }} de {{ $clientes->total() }}</span>
            {{ $clientes->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-[#1e1e1e] rounded-lg border border-gray-800">
            <svg class="mx-auto h-14 w-14 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <h3 class="text-lg font-semibold text-white">Nenhum usuário encontrado</h3>
            <p class="mt-1 text-sm text-gray-400">Crie o primeiro usuário clicando em "Novo Usuário".</p>
        </div>
    @endif
</div>

<!-- Modal: Criar Usuário -->
<div id="createUsuarioModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" onclick="closeModal('createUsuarioModal')">
    <div class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md border border-gray-800" onclick="event.stopPropagation()">
        <h3 class="text-lg font-semibold text-white mb-4">Novo Usuário</h3>
        <form method="POST" action="{{ route('admin.usuarios.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-white mb-1">Nome</label>
                    <input type="text" name="nome" required
                           class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                           placeholder="Nome completo">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white mb-1">Usuário</label>
                    <input type="text" name="usuario" required
                           class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                           placeholder="login único">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white mb-1">Senha</label>
                    <input type="password" name="password" required minlength="4"
                           class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                           placeholder="Mínimo 4 caracteres">
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" onclick="closeModal('createUsuarioModal')"
                        class="flex-1 rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-colors">
                    Criar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Editar Usuário -->
<div id="editUsuarioModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" onclick="closeModal('editUsuarioModal')">
    <div class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md border border-gray-800" onclick="event.stopPropagation()">
        <h3 class="text-lg font-semibold text-white mb-4">Editar Usuário</h3>
        <form id="editUsuarioForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-white mb-1">Nome</label>
                    <input type="text" name="nome" id="edit_nome" required
                           class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white mb-1">Usuário</label>
                    <input type="text" name="usuario" id="edit_usuario" required
                           class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white mb-1">Nova Senha <span class="text-gray-400 font-normal">(opcional)</span></label>
                    <input type="password" name="password" id="edit_password" minlength="4"
                           placeholder="Deixe em branco para não alterar"
                           class="w-full rounded-md border-0 bg-[#171717] py-2 px-3 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <button type="button" onclick="closeModal('editUsuarioModal')"
                        class="flex-1 rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black hover:bg-[#d9b300] transition-colors">
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.style.overflow = '';
}
function editUsuario(id, nome, usuario) {
    document.getElementById('editUsuarioForm').action = `/admin/usuarios/${id}`;
    document.getElementById('edit_nome').value = nome;
    document.getElementById('edit_usuario').value = usuario;
    document.getElementById('edit_password').value = '';
    openModal('editUsuarioModal');
}
function deleteUsuario(id, nome) {
    if (!confirm(`Remover o usuário "${nome}"?\n\nEsta ação não pode ser desfeita.`)) return;
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/usuarios/${id}`;
    const csrf = document.createElement('input');
    csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
    const method = document.createElement('input');
    method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
    form.appendChild(csrf); form.appendChild(method);
    document.body.appendChild(form); form.submit();
}
</script>
@endpush
@endsection
