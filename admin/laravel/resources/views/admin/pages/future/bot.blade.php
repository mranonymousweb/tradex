@extends('admin.layout.master')

@section('title', trans('setting.futures'))

@section('contents')

    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-background-dark">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex mb-2 text-sm text-gray-500" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li>@lang('setting.futures')</li>
                        <li><svg class="w-3 h-3 mx-1 text-gray-400" fill="none" viewBox="0 0 6 10" stroke="currentColor"><path d="m1 9 4-4-4-4"/></svg></li>
                        <li class="font-medium text-gray-800 dark:text-gray-200">@lang('general.bot_config')</li>
                    </ol>
                </nav>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    @lang('general.market_bot'): <span class="text-primary-600">{{ $currency->symbol }}</span>
                </h2>
            </div>

            <div>
                <a href="{{ route('admin.setting.futures') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 rtl:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="max-w-full mx-auto" >
            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft dark:shadow-soft-dark border border-gray-100 dark:border-gray-700 overflow-hidden">

                <form action="{{ route('admin.future.bot.creat') }}" method="POST" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        {{-- Left Side: Selection & Status --}}
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.target_currencies') <span class="text-red-500">*</span>
                                </label>

                                <select name="currencies[]" multiple class="select2 w-full">
                                    @php
                                        $selectedCurrencies = collect(old('currencies', $bot->currencies ?? []))
                                            ->pluck('id')
                                            ->toArray();
                                    @endphp
                                    @foreach($currencies as $currencyItem)
                                        <option value="{{ $currencyItem->main_currency_id }}" @selected(in_array($currencyItem->main_currency_id, $selectedCurrencies))>
                                            {{ $currencyItem->main_currency_symbol }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('currencies') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-100 dark:border-gray-700">
                                <label class="flex items-center justify-between cursor-pointer">
                                    <div>
                                        <span class="block text-sm font-semibold text-gray-700 dark:text-gray-300">@lang('general.status')</span>
                                        <span class="text-xs text-gray-500">@lang('general.bot_status_hint')</span>
                                    </div>
                                    <div class="relative inline-flex items-center cursor-pointer">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" name="status" value="1" class="sr-only peer" @checked(old('status', $bot->status ?? ''))>
                                        <div class="w-12 h-6 bg-gray-300 rounded-full peer peer-checked:bg-green-600 transition-colors duration-300"></div>
                                        <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-6"></div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Right Side: Numeric Parameters --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @php
                                $fields = [
                                    'leastAmount' => ['icon' => 'USDT', 'unit' => 'Qty'],
                                    'mostAmount'  => ['icon' => 'USDT', 'unit' => 'Qty'],
                                    'buyPercent'  => ['icon' => '%', 'unit' => 'Buy'],
                                    'sellPercent' => ['icon' => '%', 'unit' => 'Sell'],
                                    'donePercent' => ['icon' => '%', 'unit' => 'Done'],
                                    'secount'     => ['icon' => '⏱', 'unit' => 'Sec'],
                                ];
                            @endphp

                            @foreach($fields as $field => $meta)
                                <div class="space-y-1">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-tight">@lang("general.$field")</label>
                                    <div class="relative">
                                        <input type="text"
                                               name="{{ $field }}"
                                               value="{{ old($field, $bot->$field ?? '') }}"
                                               class="separator w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 shadow-sm transition-all"
                                               placeholder="0.00">
                                        <span class="absolute end-3 top-2.5 text-xs text-gray-400 font-mono">{{ $meta['icon'] }}</span>
                                    </div>
                                    @error($field) <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p> @enderror
                                </div>
                            @endforeach
                        </div>

                    </div>

                    {{-- Action Footer --}}
                    <div class="mt-10 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-lg shadow-lg hover:shadow-primary-500/30 transition-all duration-200">
                            @lang('general.save')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

@endsection
