<a href="{{ route('admin.currency.edit', $currency) }}"
   class="group flex items-center max-w-fit">

    <div class="relative flex-shrink-0">
        <div
            class="h-10 w-10 overflow-hidden rounded-full border-2 border-transparent shadow-sm transition-all duration-300 group-hover:border-primary-500">

            @if($currency?->icon)
                <img
                    src="{{ $currency?->icon }}"
                    alt="{{ $currency?->name }}"
                    loading="lazy"
                    class="h-full w-full object-cover"
                >
            @else
                <div
                    class="flex h-full w-full items-center justify-center bg-primary-100 text-xs font-bold text-primary-600 dark:bg-primary-900/30 dark:text-primary-400">
                    {{ mb_substr($currency?->name ?? '', 0, 1) }}
                </div>
            @endif

        </div>
    </div>

    <div class="mr-3 leading-tight">
        <div
            class="text-[12px] font-bold text-gray-900 transition-colors duration-200 group-hover:text-primary-600 dark:text-white">
            {{ $currency?->name ?? '' }}
        </div>

        <div class="mt-0.5 flex items-center text-[10px] text-gray-500 dark:text-gray-400">
            <span class="rounded bg-gray-100 px-1.5 py-0.5 font-mono dark:bg-gray-700">
                {{ $currency?->symbol }}
            </span>
        </div>
    </div>
</a>
