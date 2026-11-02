
@if ($paginator->hasPages())
    <div class="p-4  flex items-center justify-between">
        <!-- Pagination Info -->
        <p class="text-sm text-gray-600 dark:text-gray-400">
            @lang('general.showing') {{ $paginator->firstItem() }} @lang('general.to') {{ $paginator->lastItem() }} @lang('general.of') {{ $paginator->total() }} @lang('general.results')
        </p>

        <!-- Pagination Controls -->
        <div class="flex flex-wrap space-x-2">
            <!-- Previous Page Link -->
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 border rounded-lg text-sm font-medium text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 cursor-not-allowed">
                    @lang('general.previous')
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 border rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 border-gray-300 dark:border-gray-600">
                    @lang('general.previous')
                </a>
            @endif

            <!-- Page Numbers -->
            @foreach ($elements as $element)
                @if (is_string($element))
                    <!-- "Three Dots" Separator -->
                    <span class="px-3 py-1 text-sm text-gray-700 dark:text-gray-300">...</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1 border rounded-lg text-sm font-medium text-white bg-primary-600 dark:bg-primary-500">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1 border rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 border-gray-300 dark:border-gray-600">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            <!-- Next Page Link -->
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 border rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 border-gray-300 dark:border-gray-600">
                    @lang('general.next')
                </a>
            @else
                <span class="px-3 py-1 border rounded-lg text-sm font-medium text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 cursor-not-allowed">
                    @lang('general.next')
                </span>
            @endif
        </div>
    </div>
@endif
