<!-- Filters Card -->
<form method="GET" class="mb-8 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

    @isset($hidden)
        @foreach( $hidden as $key=>$value )
            <input type="hidden" name="{{$key}}" value="{{$value}}">
        @endforeach
    @endisset

    <!-- Filter Header (Optional but recommended for clarity) -->
    <div class="px-6 py-3 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center gap-2">
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">@lang('general.filters')</span>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-4">
            @foreach($filters as $filter)
                <div class="flex flex-col">
                    <label for="{{ $filter['name'] }}" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 ml-1">
                        {{ $filter['label'] }}
                    </label>

                    <div class="relative">
                        @switch($filter['type'])
                            @case('text')
                            @case('number')
                                <input type="{{ $filter['type'] }}" name="{{ $filter['name'] }}" id="{{ $filter['name'] }}"
                                       value="{{ request($filter['name']) }}"
                                       placeholder="{{$filter['placeholder'] ?? '...'}}"
                                       class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none"/>
                                @break

                            @case('decimal')
                                <input type="text" name="{{ $filter['name'] }}" id="{{ $filter['name'] }}"
                                       value="{{ toCurrency(request($filter['name'])) }}"
                                       placeholder="{{$filter['placeholder'] ?? '0.00'}}"
                                       class="separator w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500/20 outline-none font-mono"/>
                                @break

                            @case('date')
                                <div class="relative">
                                    <input @if(app()->getLocale() == 'fa') data-jdp @else type="date" @endif
                                    name="{{ $filter['name'] }}" id="{{ $filter['name'] }}"
                                           value="{{ request($filter['name']) }}"
                                           class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500/20 outline-none"/>
                                    <svg class="w-4 h-4 absolute right-3 top-3 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                @break

                            @case('select')
                                <select name="{{ $filter['name'] }}" id="{{ $filter['name'] }}"
                                        class="select2 w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                    <option value="">-- @lang('general.all') --</option>
                                    @foreach($filter['options'] as $val => $text)
                                        <option value="{{ $val }}" {{ request($filter['name']) == $val ? 'selected' : '' }}>
                                            {!! strip_tags($text) !!}
                                        </option>
                                    @endforeach
                                </select>
                                @break

                            @case('switch')
                                <div class="flex items-center h-[42px]"> <!-- Aligns height with other inputs -->
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="{{ $filter['name'] }}" value="1" @checked(request($filter['name'])) class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                                    </label>
                                </div>
                                @break
                        @endswitch
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Filter Actions -->
        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-50 dark:border-gray-700">
            <a href="{{$back}}" class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                @lang('general.clear_filter')
            </a>
            <button type="submit" class="inline-flex items-center px-10 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-md shadow-primary-500/20 transition-all active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                @lang('general.do_filter')
            </button>
        </div>
    </div>
</form>


