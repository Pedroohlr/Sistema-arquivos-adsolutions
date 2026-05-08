@if ($paginator->hasPages())
    <nav class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm text-gray-500 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @else
                     <a href="{{ $paginator->previousPageUrl() }}" 
                         class="px-3 py-2 text-sm text-gray-300 hover:text-primary transition-colors rounded-md hover:bg-gray-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-2 text-sm text-gray-500">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 text-sm font-semibold bg-primary text-black rounded-md">
                                {{ $page }}
                            </span>
                        @else
                                     <a href="{{ $url }}" 
                                         class="px-4 py-2 text-sm text-gray-300 hover:text-primary transition-colors rounded-md hover:bg-gray-800">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                     <a href="{{ $paginator->nextPageUrl() }}" 
                         class="px-3 py-2 text-sm text-gray-300 hover:text-primary transition-colors rounded-md hover:bg-gray-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="px-3 py-2 text-sm text-gray-500 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
