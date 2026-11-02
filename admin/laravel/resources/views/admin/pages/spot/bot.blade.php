@extends('admin.layout.master')

@section('title', trans('setting.spot'))

@section('contents')

    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-background-dark">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <span class="p-1.5 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </span>
                    <span class="text-sm md:text-lg">@lang('general.bot_setting'):</span>
                    <span class="text-indigo-600 text-sm md:text-lg">{{$currency->symbol}}</span>
                </h2>
            </div>
            <div>
                <a href="{{route('admin.market.currency')}}"
                   class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 rtl:rotate-180 transition-transform group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="max-w-full mx-auto">
            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-3 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-tight">@lang('general.configure_bot')</h3>
                </div>

                <form action="{{ route('admin.market.currency.bot.creat', $currency) }}" method="POST" class="p-6">
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
                            @error('currencies') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Numeric fields --}}
                        @php
                            $numberFields = [
                                'leastAmount',
                                'mostAmount',
                                'sellPercent',
                                'buyPercent',
                                'donePercent',
                                'secount',
                            ];
                        @endphp

                        @foreach($numberFields as $field)
                            <div class="group">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2 group-focus-within:text-indigo-500 transition-colors">
                                    @lang("general.$field")
                                </label>
                                <input type="text"
                                       name="{{ $field }}"
                                       dir="ltr"
                                       value="{{ old($field, $bot->$field ?? '') }}"
                                       placeholder="0.00"
                                       class="separator w-full px-4 py-2 text-sm bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-mono text-gray-900 dark:text-white">
                                @error($field)
                                <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach

                        {{-- Status Switch --}}
                        <div class="md:col-span-2 lg:col-span-3">
                            <div class="flex items-center justify-between p-3 bg-indigo-50/30 dark:bg-indigo-900/10 rounded-lg border border-dashed border-indigo-200 dark:border-indigo-800">
                                <div>
                                    <span class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-white">@lang('general.status')</span>
                                    <span class="text-[10px] text-gray-500 uppercase tracking-tighter">@lang('general.bot_status_description')</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" name="status" value="1" class="sr-only peer" @checked(old('status', $bot->status ?? ''))>
                                    <div class="w-10 h-5 bg-gray-300 dark:bg-gray-600 rounded-full peer peer-checked:bg-green-500 transition-all after:content-[''] after:absolute after:top-[2px] after:end-5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5 rtl:peer-checked:after:-translate-x-5"></div>
                                </label>
                            </div>
                            @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-8 flex items-center justify-end border-t border-gray-100 dark:border-gray-700 pt-5">
                        <button type="submit" class="w-full md:w-auto px-10 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-lg shadow-md transition-all active:scale-95">
                            @lang('general.save')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

@endsection
