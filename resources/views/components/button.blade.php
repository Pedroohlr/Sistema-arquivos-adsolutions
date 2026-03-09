@props(['type' => 'button', 'variant' => 'primary', 'loading' => false])

@php
    $classes = match($variant) {
        'primary' => 'bg-[#f2c700] text-black hover:bg-[#d9b300]',
        'secondary' => 'bg-gray-700 text-white hover:bg-gray-600',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        'success' => 'bg-green-600 text-white hover:bg-green-700',
        default => 'bg-[#f2c700] text-black hover:bg-[#d9b300]',
    };
@endphp

<button type="{{ $type }}" 
        {{ $attributes->merge(['class' => "inline-flex items-center justify-center gap-2 rounded-md px-4 py-2 text-sm font-semibold transition-all duration-300 transform hover:scale-105 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed {$classes}"]) }}
        @if($loading) disabled @endif>
    @if($loading)
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif
    {{ $slot }}
</button>
