@extends('admin.layout.master')

@section('title', trans('setting.spot'))

@section('contents')

    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-background-dark">
        @include('admin.pages.spot.page-header')

        <div class="flex flex-col gap-6">

            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-3 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h2 class="text-base font-bold text-gray-800 dark:text-white">@lang('general.add_new_market')</h2>
                </div>
                <form action="{{ route('admin.market.currency.create') }}" method="post" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                        <div class="col-span-1">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                                @lang('general.currencies') <span class="text-red-500">*</span>
                            </label>
                            <select multiple name="currencies[]" class="select2 w-full text-sm">
                                @foreach($currencies as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('currencies')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-span-1">
                            <button type="submit" class="text-sm inline-flex items-center px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-lg shadow-md transition-all active:scale-95">
                                @lang('general.save')
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft border border-gray-100 dark:border-gray-700 p-6">
                <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">@lang('general.filter')</label>
                        <input type="text" name="base_currency_symbol" value="{{ request('base_currency_symbol') }}" placeholder="BTC"
                               class="text-sm w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 outline-none">
                    </div>
                    <div>
                        <select name="status" class="text-sm w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white outline-none">
                            <option value="">@lang('general.all')</option>
                            @foreach($status as $key  => $value)
                                <option value="{{$key}}" {{ request('status') == $key ? 'selected' : '' }}>{!! $value !!}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-lg transition-colors">
                            @lang('general.search')
                        </button>
                        <a href="{{ url()->current() }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-500 text-sm rounded-lg hover:bg-gray-200 transition-colors">@lang('general.clear_filter')</a>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">@lang('general.currency')</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-right rtl:text-right">
                        <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3 font-bold text-xs uppercase tracking-wider text-start">@lang('general.currency')</th>
                            <th class="px-6 py-3 font-bold text-xs uppercase tracking-wider text-center">@lang('general.status')</th>
                            <th class="px-6 py-3 font-bold text-xs uppercase tracking-wider text-center">@lang('general.operation')</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse( $marketCurrencies as $marketCurrency)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center font-bold text-primary-600 overflow-hidden">
                                            <img
                                                src="{{asset($marketCurrency?->baseCurrency?->icon ?? null)}}"
                                                alt="{{$marketCurrency?->baseCurrency?->symbol ?? null}}"
                                                loading="lazy"
                                                class="w-full h-full object-contain p-1"
                                            />
                                        </div>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{$marketCurrency?->baseCurrency->symbol ?? ''}}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-center text-xs">
                                    {!! $marketCurrency?->getStatus() ?? '' !!}
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex flex-wrap items-center justify-center gap-1.5">
                                        <a href="{{route('admin.market.currency.list',$marketCurrency)}}" class="px-2.5 py-1 bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 rounded-md text-xs font-bold hover:bg-blue-100 transition-all">@lang('general.details')</a>
                                        <a href="{{route('admin.market.currency.bot',$marketCurrency?->baseCurrency)}}" class="px-2.5 py-1 bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 rounded-md text-xs font-bold hover:bg-indigo-100 transition-all">@lang('general.busy_bot')</a>
                                        <a href="{{route('admin.market.currency.bot.taker',$marketCurrency?->baseCurrency)}}" class="px-2.5 py-1 bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 rounded-md text-xs font-bold hover:bg-amber-100 transition-all">@lang('general.tak_order_bot')</a>

                                        @if($marketCurrency->status == 1)
                                            <button onclick="mboxQuestion('{{route('admin.market.currency.inactive',$marketCurrency)}}')" class="px-2.5 py-1 bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 rounded-md text-xs font-bold hover:bg-red-100 transition-all">@lang('general.inactivated')</button>
                                        @else
                                            <button onclick="mboxQuestion('{{route('admin.future.market.currency.active',$marketCurrency)}}')" class="px-2.5 py-1 bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400 rounded-md text-xs font-bold hover:bg-green-100 transition-all">@lang('general.activated')</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <svg class="w-10 h-10 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span class="text-xs font-medium">@lang('general.not_found')</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/30 text-xs">
                    {{ $marketCurrencies->withQueryString()->links('pagination::tailwind') }}
                </div>
            </div>

        </div>
    </main>

@endsection
