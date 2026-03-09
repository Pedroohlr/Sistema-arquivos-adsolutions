import './bootstrap';
import 'flowbite';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import './modal-helper';

// Registrar plugins do Alpine.js
Alpine.plugin(focus);

// Inicializar Alpine.js
window.Alpine = Alpine;
Alpine.start();
