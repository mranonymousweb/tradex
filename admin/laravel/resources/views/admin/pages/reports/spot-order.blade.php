@extends('admin.layout.master')

@section('title', trans('general.spot_transaction'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">@lang('general.spot_transaction')</h2>
                <p class="text-sm text-gray-500 mt-2">@lang('general.manage_and_monitor_spot_trades')</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.excel.spot', request()->query()) }}"
                    class="text-white inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-gray-900 dark:text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 16.5V3m0 13.5 4.5-4.5M12 16.5l-4.5-4.5M3.75 19.5h16.5" />
                    </svg>
                    <span>@lang('general.download_excel')</span>
                </a>
            </div>
        </div>

        @include('admin.pages.filter', [
            'filters' => $filters,
            'back' => route('admin.reports.spot.order'),
        ])

        <!-- Table Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-start">
                    <thead
                        class="bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 uppercase  tracking-wider">
                        <tr>
                            <th class="px-6 py-4 font-bold">@lang('general.id')</th>
                            <th class="px-6 py-4 font-bold text-start">@lang('general.user')</th>
                            <th class="px-6 py-4 font-bold text-start">@lang('general.market')</th>
                            <th class="px-6 py-4 font-bold text-start">@lang('general.type')</th>
                            <th class="px-6 py-4 font-bold text-start">@lang('general.side')</th>
                            <th class="px-6 py-4 font-bold text-start">@lang('general.price')</th>
                            <th class="px-6 py-4 font-bold text-start">@lang('general.amount')</th>
                            <th class="px-6 py-4 font-bold text-start">@lang('general.total')</th>
                            <th class="px-6 py-4 font-bold text-start">@lang('general.status')</th>
                            <th class="px-6 py-4 font-bold text-center">@lang('general.operation')</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($markets as $market)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs text-gray-400 text-start">#{{ $market->id }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white text-start">
                                    <x-admin.user-profile :user="$market->user" />
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-700 dark:text-gray-200 text-start">
                                    {{ $market->getPairs('/') }}
                                </td>
                                <td class="px-6 py-4 text-start">
                                    <span
                                        class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md">{{ $market->getType() }}</span>
                                </td>
                                <td class="px-6 py-4 text-start">
                                    {!! $market->getSide() !!}
                                </td>
                                <td class="px-6 py-4 font-mono text-gray-700 dark:text-gray-300 text-start">
                                    {{ toCurrency($market->price) }} <span
                                        class="text-[10px] text-gray-400">{{ $market->baseCurrency->symbol ?? '' }}</span>
                                </td>
                                <td class="px-6 py-4 text-start">
                                    <div class="font-mono text-gray-700 dark:text-gray-300">
                                        {{ toCurrency($market->amount) }}</div>
                                    <div class="text-[10px] text-gray-400">Filled: {{ toCurrency($market->filled_amount) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-gray-900 dark:text-white text-start">
                                    {{ toCurrency($market->total) }}
                                </td>
                                <td class="px-6 py-4 text-start">
                                    {!! $market->getStatus() !!}
                                </td>
                                <td class="px-6 py-4 text-center flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.reports.spot.order.detail', $market) }}"
                                        class="text-primary-600 hover:text-primary-800 font-bold text-xs underline underline-offset-4">
                                        @lang('general.details')
                                    </a>
                                    @php
                                        $botMatch = collect($botSymbols)->first(fn($b) => $b['symbol'] === $market->base_currency_symbol && in_array($market->main_currency_symbol, collect($b['currencies'] ?? [])->pluck('symbol')->toArray()));
                                    @endphp
                                    @if(
                                        $market->status == \App\Models\Market::STATUS_DONE
                                        && $botMatch
                                        && (float) $market->amount > (float) $market->filled_amount
                                        && (empty($market->supplier_response) || ($market->supplier_response['status'] ?? null) != 1)
                                    )
                                        <form action="{{ route('admin.reports.spot.order.resend.supplier', $market) }}" method="POST" class="inline"
                                              onsubmit="event.preventDefault(); confirmAction(() => this.submit());">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-bold rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:hover:bg-amber-900/50 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                @lang('general.send_to_supplier')
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                                    @lang('general.not_found')
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                {{ $markets->withQueryString()->links('pagination::tailwind') }}
            </div>
        </div>
    </main>
@endsection
