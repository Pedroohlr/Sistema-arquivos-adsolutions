@props(['label', 'name', 'type' => 'text', 'required' => false, 'error' => null])

<div class="space-y-2">
    @if(isset($label))
        <label for="{{ $name }}" class="block text-sm font-medium text-white">
            {{ $label }}
            @if($required)
                <span class="text-red-400">*</span>
            @endif
        </label>
    @endif
    
    <input type="{{ $type }}" 
           id="{{ $name }}"
           name="{{ $name }}"
           {{ $attributes->merge(['class' => 'w-full rounded-md border-0 bg-[#171717] py-2.5 px-3 text-white ring-1 ring-gray-700 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-[#f2c700] sm:text-sm sm:leading-6 transition-all' . ($error ? ' ring-red-500' : '')]) }}
           @if($required) required @endif>
    
    @if($error)
        <p class="text-sm text-red-400">{{ $error }}</p>
    @endif
</div>
