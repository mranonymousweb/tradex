@extends('admin.layout.master')

@section('title', trans('setting.futures'))

@section('contents')

    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-background-dark">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    @lang('setting.base_market') <span class="text-primary-600">(USDT)</span>
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{route('admin.setting.futures')}}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-light hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 shadow-sm transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 ml-2 rtl:rotate-180 transition-transform group-hover:translate-x-1"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>
{{--        @include('admin.pages.future.page-header')--}}

        <div class="flex flex-col gap-6">

            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft dark:shadow-soft-dark border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                        @lang('general.add_new_market')
                    </h2>
                </div>

                <form action="{{ route('admin.future.market.currency.create') }}" method="POST" class="p-6">
                    @csrf
                    <input type="hidden" name="type" value="{{ \App\Models\MarketCurrency::TYPE_FEATURE }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Currencies --}}
                        <div>
                            <x-admin.select2
                                name="currencies[]"
                                :label="__('general.currencies')"
                                :options="$currencies->pluck('symbol', 'id')"
                                :selected="old('currencies')"
                                multiple
                            />
                        </div>

                        {{-- Numeric fields --}}
                        @php
                            $numberFields = ['leverage'];
                        @endphp

                        @foreach($numberFields as $field)
                            <div>
                                <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang("general.$field")
                                </label>

                                <input type="text"
                                       id="{{ $field }}"
                                       name="{{ $field }}"
                                       value="{{ old($field) }}"
                                       placeholder="@lang("general.$field")"
                                       class="separator w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors duration-200 dark:bg-gray-700 dark:text-white sm:text-sm">

                                @error($field)
                                <p class="mt-1 text-red-500 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>

                    {{-- Submit Actions --}}
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="inline-flex justify-center items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 w-full sm:w-auto">
                            @lang('general.save')
                        </button>
                    </div>
                </form>
            </div>
            {{-- Filter Section --}}
            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        @lang('general.filters')
                    </h2>
                </div>

                <form action="{{ route('admin.future.market.currency') }}" method="GET" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        {{-- Search by Pair --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.main_currency')</label>
                            <input type="text" name="main_currency_symbol" value="{{ request('main_currency_symbol') }}" placeholder="e.g. BTC"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>

                        {{-- Status Filter --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.status')</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white sm:text-sm">
                                <option value="">@lang('general.all')</option>
                                @foreach($status as $key  => $value)
                                    <option value="{{$key}}" {{ request('status') == $key ? 'selected' : '' }}>{!! $value !!}</option>
                                @endforeach

                            </select>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                                @lang('general.search')
                            </button>
                            <a href="{{ url()->current() }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg transition-colors">
                                @lang('general.clear_filter')
                            </a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-start border-collapse">
                        <thead class="bg-gray-50/80 dark:bg-gray-800/80 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-medium text-start">@lang('general.id')</th>
                            <th scope="col" class="px-6 py-4 font-medium text-start">@lang('general.market')</th>
                            <th scope="col" class="px-6 py-4 font-medium text-start">@lang('general.currency')</th>
                            <th scope="col" class="px-6 py-4 font-medium text-start">@lang('general.leverage')</th>
                            <th scope="col" class="px-6 py-4 font-medium text-center">@lang('general.status')</th>
                            <th scope="col" class="px-6 py-4 font-medium text-center">@lang('general.operation')</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-300">
                        @forelse($marketCurrencies as $marketCurrency)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-200 group">

                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    {{ $marketCurrency?->id ?? '-' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                    {{ $marketCurrency?->pairs ?? '-' }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="relative flex-shrink-0 w-9 h-9">
                                            <img
                                                src="{{ asset($marketCurrency?->mainCurrency?->icon) }}"
                                                alt="{{ $marketCurrency?->mainCurrency?->symbol }}"
                                                loading="lazy"
                                                class="w-full h-full rounded-full object-contain bg-white dark:bg-gray-800 p-0.5 border border-gray-200 dark:border-gray-600 shadow-sm group-hover:scale-105 transition-transform duration-300"
                                            />
                                        </div>
                                        <span class="font-semibold text-gray-900 dark:text-white">
                                {{ $marketCurrency?->mainCurrency->symbol ?? '' }}
                            </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 dark:bg-gray-800 text-xs font-semibold text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                            {{ toCurrency($marketCurrency->leverage ?? 0) }}x
                        </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    {!! $marketCurrency?->getStatus() ?? '-' !!}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.future.market.currency.detail', $marketCurrency) }}"
                                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-500/20 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                                            @lang('general.details')
                                        </a>

                                        @if($marketCurrency->status == 1)
                                            <button onclick="mboxQuestion('{{ route('admin.future.market.currency.inactive', $marketCurrency) }}')"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-red-200 bg-white text-red-600 hover:bg-red-50 hover:border-red-300 dark:bg-transparent dark:border-red-500/30 dark:text-red-400 dark:hover:bg-red-500/10 focus:outline-none focus:ring-2 focus:ring-red-500/50 transition-all">
                                                @lang('general.inactivated')
                                            </button>
                                        @else
                                            <button onclick="mboxQuestion('{{ route('admin.future.market.currency.active', $marketCurrency) }}')"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-emerald-200 bg-white text-emerald-600 hover:bg-emerald-50 hover:border-emerald-300 dark:bg-transparent dark:border-emerald-500/30 dark:text-emerald-400 dark:hover:bg-emerald-500/10 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transition-all">
                                                @lang('general.activated')
                                            </button>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto text-gray-500 dark:text-gray-400">
                                        <div class="w-16 h-16 mb-4 rounded-full bg-gray-50 dark:bg-gray-800/50 flex items-center justify-center border border-gray-100 dark:border-gray-700">
                                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">@lang('general.not_found')</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($marketCurrencies->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        {{ $marketCurrencies->withQueryString()->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </main>

@endsection
