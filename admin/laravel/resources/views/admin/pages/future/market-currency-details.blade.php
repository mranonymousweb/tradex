@extends('admin.layout.master')

@section('title', trans('setting.futures'))

@section('contents')

    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-background-dark">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-1">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse text-sm text-gray-500">
                        <li>@lang('setting.futures')</li>
                        <li><svg class="w-3 h-3 mx-1 text-gray-400" fill="none" viewBox="0 0 6 10" stroke="currentColor"><path d="m1 9 4-4-4-4"/></svg></li>
                        <li class="font-medium text-gray-800 dark:text-gray-200">@lang('general.edit')</li>
                    </ol>
                </nav>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    @lang('general.edit'): {{ $marketCurrency->pairs }}
                </h2>
            </div>

            <div>
                <a href="{{ route('admin.future.market.currency') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2 rtl:rotate-180" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="max-w-full">
            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft dark:shadow-soft-dark border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">@lang('general.update_market_settings_description')</p>
                </div>

                <form action="{{ route('admin.future.market.currency.update', $marketCurrency) }}" method="POST" class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Left Column: Settings --}}
                        <div class="space-y-6">
                            {{-- Currencies --}}
                            <div>
                                <x-admin.select2
                                    name="main_currency_id"
                                    :label="__('general.currencies')"
                                    :options="$currencies->pluck('symbol', 'id')"
                                    :selected="old('main_currency_id', $marketCurrency->main_currency_id)"
                                />
                            </div>

                            {{-- Numeric fields --}}
                            @php $numberFields = ['leverage']; @endphp
                            @foreach($numberFields as $field)
                                <div>
                                    <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        @lang("general.$field")
                                    </label>
                                    <div class="relative">
                                        <input type="text"
                                               id="{{ $field }}"
                                               name="{{ $field }}"
                                               value="{{ old($field, $marketCurrency->$field) }}"
                                               class="separator w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 transition-all shadow-sm">
                                        <span class="absolute inset-y-0 end-4 flex items-center text-gray-400 pointer-events-none">x</span>
                                    </div>
                                    @error($field)
                                    <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        {{-- Right Column: Information/Notice --}}
                        <div class="flex flex-col justify-start">
                            <div class="rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="mr-3">
                                        <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">@lang('general.important_notice')</h3>
                                        <div class="mt-2 text-sm text-amber-700 dark:text-amber-300">
                                            <p>@lang('general.update_pyramids_notice')</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Section --}}
                    <div class="mt-10 pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end">
                        <button type="submit" class="inline-flex items-center px-8 py-2.5 text-sm font-semibold rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-md hover:shadow-lg focus:ring-4 focus:ring-primary-500/20 transition-all duration-200">
                            @lang('general.save')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

@endsection
