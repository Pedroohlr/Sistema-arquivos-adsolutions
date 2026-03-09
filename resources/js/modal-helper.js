// Helper global para modais e confirmações

window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) {
        console.warn('Modal não encontrado:', modalId);
        return;
    }
    
    // Remover hidden e mostrar o modal
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    
    // Se o modal usa Alpine.js, atualizar o estado
    if (window.Alpine && modal.hasAttribute('x-data')) {
        try {
            // Tentar acessar o estado do Alpine
            if (modal.__x) {
                modal.__x.$data.open = true;
            } else {
                // Se não tem __x, tentar inicializar
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
    
    // Prevenir scroll do body
    document.body.style.overflow = 'hidden';
};

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    
    // Esconder o modal
    modal.classList.add('hidden');
    modal.style.display = 'none';
    
    // Se o modal usa Alpine.js, atualizar o estado
    if (window.Alpine && modal.hasAttribute('x-data')) {
        try {
            if (modal.__x) {
                modal.__x.$data.open = false;
            }
        } catch (e) {
            // Ignorar erro
        }
    }
    
    // Restaurar scroll do body
    document.body.style.overflow = '';
};

// Fechar modal ao pressionar ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const openModals = document.querySelectorAll('[x-show="open"]:not(.hidden)');
        openModals.forEach(modal => {
            if (modal.__x && modal.__x.$data.open) {
                modal.__x.$data.open = false;
            } else {
                modal.classList.add('hidden');
            }
        });
        document.body.style.overflow = '';
    }
});

// Confirmação melhorada para deletar
window.confirmDelete = function(message, callback) {
    if (confirm(message)) {
        if (typeof callback === 'function') {
            callback();
        }
    }
};
