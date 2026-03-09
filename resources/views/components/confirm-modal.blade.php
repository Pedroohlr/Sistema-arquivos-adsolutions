@props(['id', 'title', 'message', 'confirmText' => 'Confirmar', 'cancelText' => 'Cancelar', 'type' => 'danger'])

<div id="{{ $id }}" 
     x-data="{ open: false }"
     x-show="open"
     x-cloak
     class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4"
     @keydown.escape.window="open = false">
    <div @click.away="open = false"
         class="bg-[#1e1e1e] rounded-lg p-6 w-full max-w-md border border-gray-800 transform transition-all"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
            @if($type === 'danger')
                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            @else
                <svg class="w-6 h-6 text-[#f2c700]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            @endif
            {{ $title }}
        </h3>
        <p class="text-white mb-6">{{ $message }}</p>
        <div class="flex gap-3">
            <button @click="open = false"
                    class="flex-1 rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-600 transition-colors">
                {{ $cancelText }}
            </button>
            <button @click="open = false; $dispatch('confirmed')"
                    class="flex-1 rounded-md px-4 py-2 text-sm font-semibold text-white transition-colors
                           @if($type === 'danger') bg-red-600 hover:bg-red-700
                           @else bg-[#f2c700] hover:bg-[#d9b300] text-black
                           @endif">
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
