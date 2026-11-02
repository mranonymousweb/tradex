@extends('admin.layout.master')

@section('title', trans('setting.spot'))

@section('contents')

    <main class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50 dark:bg-background-dark">
        {{-- Header Section --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-3">
                    <span class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </span>
                    <span class="text-sm md:text-lg tracking-wide">@lang('general.tak_order_bot'):</span>
                    <span class="text-amber-600 dark:text-amber-400 text-sm md:text-lg">{{$currency->symbol}}</span>
                </h2>
            </div>
            <div>
                <a href="{{route('admin.market.currency')}}"
                   class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 shadow-sm transition-all duration-200 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5 rtl:rotate-180 transition-transform group-hover:-translate-x-1 rtl:group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>
        <div class="mb-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl p-5 text-start transition-colors" >
            <div class="flex items-center gap-2 mb-3 text-blue-800 ">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h4 class="text-base font-bold">@lang('general.bot_title')</h4>
            </div>

            <div class="space-y-4 text-sm text-blue-800 dark:text-blue-200 leading-relaxed">
                <p>
                    <strong>@lang('general.note_title')</strong>@lang('general.bot_setting_note')
                </p>
                <p>
                    @lang('general.interval_desc')
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">
                    {{-- Suggested Time Box --}}
                    <div class="bg-white/60 dark:bg-gray-800/50 p-4 rounded-lg border border-blue-200/60 dark:border-blue-700/50 shadow-sm">
                        <div class="font-bold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @lang('general.recommended_interval_title')
                        </div>
                        <p class="text-xs opacity-90 leading-relaxed">@lang('general.recommended_interval_desc')</p>
                    </div>

                    {{-- Example Box --}}
                    <div class="bg-white/60 dark:bg-gray-800/50 p-4 rounded-lg border border-blue-200/60 dark:border-blue-700/50 shadow-sm">
                        <div class="font-bold text-blue-900 dark:text-blue-100 mb-2 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                            @lang('general.example_title')
                        </div>
                        <p class="text-xs opacity-90 mb-3 leading-relaxed">@lang('general.example_desc')</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="max-w-full mx-auto">
            <div class="bg-white dark:bg-card-dark rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden transition-all">




                {{-- Card Header --}}
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800/50">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">
                        @lang('general.configure_taker_bot')
                    </h3>
                </div>

                <div class="p-6">

                    {{-- Form --}}
                    <form action="{{ route('admin.market.currency.bot.order.creat', $currency) }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {{-- Currency Multi-Select --}}
                            <div class="md:col-span-2 lg:col-span-3">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                                    @lang('general.currencies') <span class="text-red-500">*</span>
                                </label>
                                <select name="currencies[]" multiple class="select2 w-full text-sm">
                                    @php
                                        $selectedCurrencies = collect(old('currencies', $bot->currencies ?? []))
                                            ->pluck('id')
                                            ->toArray();
                                    @endphp
                                    @foreach($currencies as $curr)
                                        <option value="{{ $curr->main_currency_id }}"
                                            @selected(in_array($curr->main_currency_id, $selectedCurrencies))>
                                            {{ $curr->main_currency_symbol }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('currencies')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            {{-- Numeric fields --}}
                            @php
                                $numberFields = ['sellPercent', 'buyPercent', 'secount'];
                            @endphp

                            @foreach($numberFields as $field)
                                <div class="group">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2 group-focus-within:text-amber-600 dark:group-focus-within:text-amber-400 transition-colors">
                                        @lang("general.$field")
                                    </label>
                                    <div class="relative">
                                        <input type="text"
                                               name="{{ $field }}"
                                               dir="ltr"
                                               value="{{ old($field, $bot->$field ?? '') }}"
                                               placeholder="0.00"
                                               class="separator w-full px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-600 rounded-lg focus:bg-white dark:focus:bg-gray-700 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all font-mono text-gray-900 dark:text-white shadow-sm">
                                    </div>
                                    @error($field)
                                    <p class="mt-1.5 text-red-500 text-xs flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            @endforeach

                            {{-- Switches Row --}}
                            <div class="md:col-span-2 lg:col-span-3 grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4">
                                @foreach (['sendToContactor', 'status'] as $switch)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800/40 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition-colors">
                                        <span class="text-sm font-bold tracking-wide text-gray-700 dark:text-gray-300">
                                            @lang("general.$switch")
                                        </span>

                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="hidden" name="{{ $switch }}" value="0">
                                            <input
                                                type="checkbox"
                                                name="{{ $switch }}"
                                                value="1"
                                                class="sr-only peer"
                                                @checked(old($switch, $bot->$switch ?? false))
                                            >
                                            <div class="w-11 h-6 bg-gray-300 dark:bg-gray-600 rounded-full peer peer-focus:ring-4 peer-focus:ring-green-300 dark:peer-focus:ring-green-800 peer-checked:bg-green-500 transition-all
                                                after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full
                                                after:h-5 after:w-5 after:transition-all
                                                peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white">
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                        </div>

                        {{-- Submit Button --}}
                        <div class="mt-8 flex items-center justify-end border-t border-gray-100 dark:border-gray-700 pt-6">
                            <button type="submit" class="w-full sm:w-auto px-8 py-2.5 bg-primary hover:bg-primary-700 text-white text-sm font-bold rounded-lg shadow-sm hover:shadow-md transition-all active:scale-95 flex items-center justify-center gap-2">
                                @lang('general.save')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

@endsection
