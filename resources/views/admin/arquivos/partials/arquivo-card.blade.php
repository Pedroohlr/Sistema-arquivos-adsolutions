<div class="bg-[#171717] border border-gray-800 rounded-lg p-4 hover:border-[#f2c700] transition-all duration-300 group transform hover:scale-[1.02] hover:shadow-lg hover:shadow-[#f2c700]/10">
    <div class="flex items-start justify-between mb-3">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <!-- Ícone baseado no tipo de arquivo -->
            <div class="flex-shrink-0 transform group-hover:scale-110 transition-transform duration-300">
                @php
                    $extension = pathinfo($arquivo->nome, PATHINFO_EXTENSION);
                    $iconColor = 'text-gray-400';
                    if (in_array($extension, ['pdf'])) $iconColor = 'text-red-400';
                    elseif (in_array($extension, ['zip', 'rar', '7z'])) $iconColor = 'text-yellow-400';
                    elseif (in_array($extension, ['exe', 'msi'])) $iconColor = 'text-blue-400';
                    elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) $iconColor = 'text-green-400';
                    elseif (in_array($extension, ['doc', 'docx'])) $iconColor = 'text-blue-500';
                    elseif (in_array($extension, ['xls', 'xlsx'])) $iconColor = 'text-green-500';
                @endphp
                <svg class="w-10 h-10 {{ $iconColor }} group-hover:drop-shadow-lg transition-all" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <h4 class="text-white font-medium text-sm truncate group-hover:text-[#f2c700] transition-colors">{{ $arquivo->nome }}</h4>
                <p class="text-xs text-gray-400 mt-0.5">{{ $arquivo->tamanho_formatado }}</p>
            </div>
        </div>
        
        <!-- Botões de ação -->
        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
            <button onclick="deleteArquivo({{ $arquivo->id }}, '{{ addslashes($arquivo->nome) }}')"
                    class="p-1.5 bg-red-900 hover:bg-red-800 rounded text-white transition-colors transform hover:scale-110"
                    title="Deletar arquivo">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    </div>
    
    <!-- Info adicional -->
    <div class="text-xs text-gray-500 flex items-center gap-1">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span>Enviado em {{ $arquivo->created_at->format('d/m/Y') }}</span>
    </div>
</div>

@once
@push('scripts')
<script>
function deleteArquivo(id, nome) {
    if (confirm(`⚠️ Tem certeza que deseja deletar o arquivo "${nome}"?\n\nEsta ação não pode ser desfeita.`)) {
        // Adicionar loading
        const button = event.target.closest('button');
        if (button) {
            button.disabled = true;
            button.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        }
        
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
