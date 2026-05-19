@extends('layouts.admin')

@section('title', 'Administradores')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Administradores</h1>
                <p class="mt-1 text-sm text-gray-400">Gerencie acessos administrativos sem depender de um único login.</p>
            </div>
            <button onclick="openModal('createAdminModal')"
                class="flex w-full items-center justify-center gap-2 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg shadow-[#f2c700]/20 hover:bg-[#d9b300] sm:w-auto">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Administrador
            </button>
        </div>

        <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_320px]">
            <div class="space-y-4">
                <div class="rounded-lg border border-gray-800 bg-[#1e1e1e] p-4">
                    <form method="GET" action="{{ route('admin.admins.index') }}"
                        class="flex flex-col gap-4 md:flex-row md:items-end">
                        <div class="flex-1">
                            <label class="mb-2 block text-sm font-medium text-white">Buscar</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nome ou email do admin..."
                                class="w-full rounded-md border-0 bg-[#171717] px-3 py-2 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <button type="submit"
                                class="rounded-md bg-[#f2c700] px-6 py-2 text-sm font-semibold text-black transition-colors hover:bg-[#d9b300]">
                                Filtrar
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.admins.index') }}"
                                    class="rounded-md bg-gray-700 px-6 py-2 text-center text-sm font-semibold text-white transition-colors hover:bg-gray-600">
                                    Limpar
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                @if($admins->count() > 0)
                    <div class="overflow-hidden rounded-lg border border-gray-800 bg-[#1e1e1e]">
                        <div class="divide-y divide-gray-800 md:hidden">
                            @foreach($admins as $admin)
                                @php
                                    $isCurrentAdmin = $currentAdminId === $admin->id;
                                    $isLastAdmin = $totalAdmins === 1;
                                    $isProtectedAdmin = $isCurrentAdmin || $isLastAdmin;
                                @endphp
                                <div class="space-y-4 p-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-[#f2c700] text-black">
                                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <div class="text-sm font-medium text-white">{{ $admin->name }}</div>
                                                @if($isCurrentAdmin)
                                                    <span class="rounded-full bg-[#f2c700]/15 px-2 py-0.5 text-xs font-medium text-[#f2c700]">Seu acesso</span>
                                                @endif
                                                @if($isLastAdmin)
                                                    <span class="rounded-full bg-red-900/40 px-2 py-0.5 text-xs font-medium text-red-300">Último admin</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-400">{{ $admin->email }}</div>
                                            <div class="mt-2 text-xs text-gray-500">Criado em {{ $admin->created_at->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2 sm:flex-row">
                                        <button
                                            onclick="editAdmin({{ $admin->id }}, @js($admin->name), @js($admin->email))"
                                            class="rounded-md bg-gray-800 px-3 py-2 text-sm text-[#f2c700] transition-colors hover:bg-gray-700 hover:text-[#d9b300]">
                                            Editar
                                        </button>
                                        <button type="button"
                                            onclick="deleteAdmin({{ $admin->id }}, @js($admin->name))"
                                            @disabled($isProtectedAdmin)
                                            class="rounded-md px-3 py-2 text-sm transition-colors {{ $isProtectedAdmin ? 'cursor-not-allowed bg-gray-800 text-gray-500' : 'bg-red-900/40 text-red-300 hover:bg-red-900/70 hover:text-red-200' }}">
                                            {{ $isProtectedAdmin ? 'Protegido' : 'Remover' }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="hidden overflow-x-auto md:block">
                            <table class="min-w-full divide-y divide-gray-800">
                                <thead class="bg-[#171717]">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Administrador</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-400">Criado em</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-400">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800">
                                    @foreach($admins as $admin)
                                        @php
                                            $isCurrentAdmin = $currentAdminId === $admin->id;
                                            $isLastAdmin = $totalAdmins === 1;
                                            $isProtectedAdmin = $isCurrentAdmin || $isLastAdmin;
                                        @endphp
                                        <tr class="transition-colors hover:bg-[#171717]">
                                            <td class="whitespace-nowrap px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-[#f2c700] text-black">
                                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <path
                                                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-white">{{ $admin->name }}</div>
                                                        <div class="text-xs text-gray-400">{{ $admin->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap gap-2">
                                                    @if($isCurrentAdmin)
                                                        <span class="rounded-full bg-[#f2c700]/15 px-2 py-0.5 text-xs font-medium text-[#f2c700]">Seu acesso</span>
                                                    @endif
                                                    @if($isLastAdmin)
                                                        <span class="rounded-full bg-red-900/40 px-2 py-0.5 text-xs font-medium text-red-300">Último administrador</span>
                                                    @else
                                                        <span class="rounded-full bg-gray-800 px-2 py-0.5 text-xs font-medium text-gray-300">Pode ser gerenciado</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-400">
                                                {{ $admin->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <button
                                                        onclick="editAdmin({{ $admin->id }}, @js($admin->name), @js($admin->email))"
                                                        class="rounded px-2 py-1 text-xs text-[#f2c700] transition-colors hover:bg-gray-800 hover:text-[#d9b300]">
                                                        Editar
                                                    </button>
                                                    <button type="button"
                                                        onclick="deleteAdmin({{ $admin->id }}, @js($admin->name))"
                                                        @disabled($isProtectedAdmin)
                                                        class="rounded px-2 py-1 text-xs transition-colors {{ $isProtectedAdmin ? 'cursor-not-allowed text-gray-500' : 'text-red-400 hover:bg-gray-800 hover:text-red-300' }}">
                                                        {{ $isProtectedAdmin ? 'Protegido' : 'Remover' }}
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 text-sm text-gray-400 sm:flex-row sm:items-center sm:justify-between">
                        <span>Mostrando {{ $admins->firstItem() ?? 0 }}–{{ $admins->lastItem() ?? 0 }} de {{ $admins->total() }}</span>
                        {{ $admins->links() }}
                    </div>
                @else
                    <div class="rounded-lg border border-gray-800 bg-[#1e1e1e] py-16 text-center">
                        <svg class="mx-auto mb-4 h-14 w-14 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-white">Nenhum administrador encontrado</h3>
                        <p class="mt-1 text-sm text-gray-400">Crie um novo administrador para compartilhar a operação do painel.</p>
                    </div>
                @endif
            </div>

            <aside class="rounded-lg border border-gray-800 bg-[#1e1e1e] p-5">
                <h2 class="text-lg font-semibold text-white">Regras de acesso</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-300">
                    <p class="rounded-lg bg-[#171717] p-3">O sistema aceita múltiplos administradores com email único.</p>
                    <p class="rounded-lg bg-[#171717] p-3">Nenhum admin pode remover o próprio acesso enquanto estiver logado.</p>
                    <p class="rounded-lg bg-[#171717] p-3">O último administrador do sistema fica protegido contra remoção.</p>
                    <p class="rounded-lg bg-[#171717] p-3">A senha só é alterada quando preenchida na edição.</p>
                </div>
                <div class="mt-5 rounded-lg border border-gray-800 bg-[#171717] p-4">
                    <div class="text-xs uppercase tracking-wide text-gray-500">Resumo</div>
                    <div class="mt-2 text-3xl font-bold text-white">{{ $totalAdmins }}</div>
                    <div class="text-sm text-gray-400">administrador(es) cadastrado(s)</div>
                </div>
            </aside>
        </div>
    </div>

    <div id="createAdminModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4"
        onclick="closeModal('createAdminModal')">
        <div class="w-full max-w-md rounded-lg border border-gray-800 bg-[#1e1e1e] p-6" onclick="event.stopPropagation()">
            <h3 class="mb-4 text-lg font-semibold text-white">Novo Administrador</h3>
            <form method="POST" action="{{ route('admin.admins.store') }}" autocomplete="off">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-white">Nome</label>
                        <input type="text" name="name" required
                            class="w-full rounded-md border-0 bg-[#171717] px-3 py-2 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                            placeholder="Nome completo">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-white">Email</label>
                        <input type="email" name="email" required
                            class="w-full rounded-md border-0 bg-[#171717] px-3 py-2 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                            placeholder="admin@empresa.com">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-white">Senha</label>
                        <input type="password" name="password" required minlength="8" autocomplete="new-password"
                            class="w-full rounded-md border-0 bg-[#171717] px-3 py-2 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                            placeholder="Mínimo 8 caracteres">
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeModal('createAdminModal')"
                        class="flex-1 rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black transition-colors hover:bg-[#d9b300]">
                        Criar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="editAdminModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4"
        onclick="closeModal('editAdminModal')">
        <div class="w-full max-w-md rounded-lg border border-gray-800 bg-[#1e1e1e] p-6" onclick="event.stopPropagation()">
            <h3 class="mb-4 text-lg font-semibold text-white">Editar Administrador</h3>
            <form id="editAdminForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-white">Nome</label>
                        <input type="text" name="name" id="edit_admin_name" required
                            class="w-full rounded-md border-0 bg-[#171717] px-3 py-2 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-white">Email</label>
                        <input type="email" name="email" id="edit_admin_email" required
                            class="w-full rounded-md border-0 bg-[#171717] px-3 py-2 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-white">Nova Senha <span class="font-normal text-gray-400">(opcional)</span></label>
                        <input type="password" name="password" id="edit_admin_password" minlength="8" autocomplete="new-password"
                            class="w-full rounded-md border-0 bg-[#171717] px-3 py-2 text-white ring-1 ring-gray-700 focus:ring-2 focus:ring-[#f2c700]"
                            placeholder="Preencha apenas se quiser trocar">
                    </div>
                </div>
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="closeModal('editAdminModal')"
                        class="flex-1 rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 rounded-md bg-[#f2c700] px-4 py-2 text-sm font-semibold text-black transition-colors hover:bg-[#d9b300]">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteAdminModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4"
        onclick="closeModal('deleteAdminModal')">
        <div class="w-full max-w-md rounded-lg border border-red-800/50 bg-[#1e1e1e] p-6" onclick="event.stopPropagation()">
            <div class="mb-4 flex items-center gap-3">
                <div class="rounded-lg bg-red-900/30 p-2">
                    <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-red-400">Remover Administrador</h3>
            </div>
            <p class="mb-2 text-white">Tem certeza que deseja remover <strong class="text-red-400" id="delete_admin_name"></strong>?</p>
            <p class="mb-6 text-sm text-gray-400">O acesso administrativo será revogado imediatamente.</p>
            <form id="deleteAdminForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('deleteAdminModal')"
                        class="flex-1 rounded-md bg-gray-700 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 rounded-md bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-red-700">
                        Remover
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const adminUpdateUrlTemplate = @json(route('admin.admins.update', ['admin' => '__ID__']));
        const adminDeleteUrlTemplate = @json(route('admin.admins.destroy', ['admin' => '__ID__']));

        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        }

        function adminUpdateUrl(id) {
            return adminUpdateUrlTemplate.replace('__ID__', id);
        }

        function adminDeleteUrl(id) {
            return adminDeleteUrlTemplate.replace('__ID__', id);
        }

        function editAdmin(id, name, email) {
            document.getElementById('editAdminForm').action = adminUpdateUrl(id);
            document.getElementById('edit_admin_name').value = name;
            document.getElementById('edit_admin_email').value = email;
            document.getElementById('edit_admin_password').value = '';
            openModal('editAdminModal');
        }

        function deleteAdmin(id, name) {
            document.getElementById('deleteAdminForm').action = adminDeleteUrl(id);
            document.getElementById('delete_admin_name').textContent = name;
            openModal('deleteAdminModal');
        }
    </script>
@endpush