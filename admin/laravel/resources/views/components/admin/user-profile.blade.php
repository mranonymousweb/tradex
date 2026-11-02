<a href="{{ $editUrl }}"
   class="group flex items-center max-w-fit">

    <div class="relative flex-shrink-0">
        <div
            class="h-10 w-10 overflow-hidden rounded-full border-2 border-transparent shadow-sm transition-all duration-300 group-hover:border-primary-500">

            @if($user?->getAvatar())
                <img
                    src="{{ $user?->getAvatar() }}"
                    alt="{{ $user?->getFullName() }}"
                    loading="lazy"
                    class="h-full w-full object-cover"
                >
            @else
                <div
                    class="flex h-full w-full items-center justify-center bg-primary-100 text-xs font-bold text-primary-600 dark:bg-primary-900/30 dark:text-primary-400">
                    {{ mb_substr($user?->getFullName(), 0, 1) }}
                </div>
            @endif

        </div>
    </div>

    <div class="ms-3 leading-start">
        <div
            class="text-sm font-bold text-gray-900 transition-colors duration-200 group-hover:text-primary-600 dark:text-white">
            {{ $user?->getFullName() }}
        </div>

        <div class="mt-0.5 flex items-center text-[11px] text-gray-500 dark:text-gray-400">
            <span class="rounded bg-gray-100 px-1.5 py-0.5 font-mono dark:bg-gray-700">
                @lang('general.id'): {{ $user?->id }}
            </span>
        </div>
    </div>
</a>
