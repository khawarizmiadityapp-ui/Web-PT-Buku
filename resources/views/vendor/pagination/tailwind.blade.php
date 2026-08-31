@if ($paginator->hasPages() || $paginator->total() > 0)
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between w-full">
        <!-- Numbered Page Links & Controls -->
        <div class="flex items-center gap-1.5 flex-wrap">
            <!-- Previous Page Link -->
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed select-none">
                    <i class="fas fa-chevron-left mr-1.5 text-[10px]"></i> Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition shadow-xs">
                    <i class="fas fa-chevron-left mr-1.5 text-[10px]"></i> Sebelumnya
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
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition shadow-xs">
                    Berikutnya <i class="fas fa-chevron-right ml-1.5 text-[10px]"></i>
                </a>
            @else
                <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed select-none">
                    Berikutnya <i class="fas fa-chevron-right ml-1.5 text-[10px]"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
