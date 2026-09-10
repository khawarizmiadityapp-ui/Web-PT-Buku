@if (isset($paginator))
    <div class="px-6 py-4 bg-white border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Left: Records Info -->
        <div class="text-xs text-gray-500 font-medium">
            @if($paginator->total() > 0)
                Menampilkan <span class="font-bold text-gray-800">{{ $paginator->firstItem() ?? 1 }}</span> sampai <span class="font-bold text-gray-800">{{ $paginator->lastItem() ?? $paginator->total() }}</span> dari <span class="font-bold text-gray-800">{{ $paginator->total() }}</span> total data
            @else
                Menampilkan <span class="font-bold text-gray-800">0</span> data
            @endif
        </div>

        <!-- Right: Page Navigation -->
        <div class="flex items-center gap-1.5 flex-wrap">
            <!-- Previous Page Link -->
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed select-none">
                    <x-icon name="chevron-left" class="mr-1.5 w-3.5 h-3.5" /> Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition shadow-xs">
                    <x-icon name="chevron-left" class="mr-1.5 w-3.5 h-3.5" /> Sebelumnya
                </a>
            @endif

            <!-- Numbered Page Links -->
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = max(1, $paginator->lastPage());
                $start = max(1, $currentPage - 2);
                $end = min($lastPage, $currentPage + 2);
            @endphp

            @if($start > 1)
                <a href="{{ $paginator->url(1) }}" class="px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition">1</a>
                @if($start > 2)
                    <span class="px-1.5 py-1 text-xs text-gray-400">...</span>
                @endif
            @endif

            @for($page = $start; $page <= $end; $page++)
                @if($page == $currentPage)
                    <span class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 rounded-lg shadow-sm border border-blue-600 select-none">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->url($page) }}" class="px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            @if($end < $lastPage)
                @if($end < $lastPage - 1)
                    <span class="px-1.5 py-1 text-xs text-gray-400">...</span>
                @endif
                <a href="{{ $paginator->url($lastPage) }}" class="px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition">{{ $lastPage }}</a>
            @endif

            <!-- Next Page Link -->
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition shadow-xs">
                    Berikutnya <x-icon name="chevron-right" class="ml-1.5 w-3.5 h-3.5" />
                </a>
            @else
                <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed select-none">
                    Berikutnya <x-icon name="chevron-right" class="ml-1.5 w-3.5 h-3.5" />
                </span>
            @endif
        </div>
    </div>
@endif
