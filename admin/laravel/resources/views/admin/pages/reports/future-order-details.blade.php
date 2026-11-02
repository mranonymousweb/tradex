@extends('admin.layout.master')

@section('title', trans('general.order_details'))

@push('styles')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .dark {
                background: white !important;
                color: black !important;
            }
        }
    </style>
@endpush

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 sm:p-8 bg-gray-50 dark:bg-gray-900 font-sans">

        <!-- Header & Quick Actions -->
        <div
            class="max-w-6xl mx-auto mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 no-print">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">@lang('general.order_details')</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Order ID: #{{ $order->id }} | @lang('general.created_at'): {{ $order->getCreatedAt() }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.future.order') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-primary-600 text-white rounded-lg text-sm font-semibold shadow-md hover:opacity-90 transition-all">
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="max-w-6xl mx-auto space-y-6">

            <!-- Main Perpetual/Futures Statement Card Wrapper -->
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden border-t-[12px] border-t-primary-600">

                <!-- Section 1: Position Ownership & Direct Metrics -->
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h3 class="text-xs uppercase tracking-widest text-gray-400 font-bold mb-3">@lang('general.customer_details')</h3>
                        <div class="flex items-center gap-4">
                            <div
                                class="relative inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-primary-50 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-800 shadow-sm overflow-hidden group">
                                @if ($order->user && $order->user->avatar)
                                    <img src="{{ $order->user->getAvatar() }}" alt="{{ $order->user->name }}" loading="lazy"
                                        class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110">
                                @else
                                    <span
                                        class="font-bold text-2xl text-primary-600 dark:text-primary-400 uppercase">{{ substr($order->user->name ?? 'U', 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('admin.user.edit', $order->user) }}"
                                    class="text-sm font-bold text-gray-900 dark:text-white hover:text-primary-600 transition-colors">
                                    {{ $order->user?->getFullInfo() ?? '-' }}
                                </a>
                                <p class="text-sm text-gray-500">{{ $order->user->email ?? '' }}</p>
                                <p class="text-sm text-gray-500">{{ $order->user->mobile ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="md:text-start flex flex-col justify-end">
                        <div class="text-sm text-gray-500 space-y-1.5">
                            <p class="flex items-center gap-2">@lang('general.status'): <span>{!! $order->status_badge !!}</span></p>
                            <p>@lang('general.type'): <span
                                    class="font-bold text-gray-700 dark:text-gray-300">{{ $order->getType() }}</span></p>
                            <p class="flex items-center gap-2">@lang('general.side'): <span>{!! $order->getSide() !!}</span></p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Margin & Leverage Parameters -->
                <div
                    class="grid grid-cols-2 md:grid-cols-4 gap-6 p-8 bg-gray-50/50 dark:bg-gray-900/20 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-wider">@lang('general.trading_pair')</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $order->pairs ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-wider">@lang('general.leverage') /
                            @lang('general.margin_mode')</p>
                        <p class="text-lg font-bold text-primary-600 dark:text-primary-400">
                            {{ $order->leverage }}x
                            <span
                                class="text-xs font-semibold text-gray-500 dark:text-gray-400">({{ $order->margin_mode == 1 ? 'Cross' : 'Isolated' }})</span>
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-wider">@lang('general.liq_price')</p>
                        <p class="text-lg font-bold text-rose-600 dark:text-rose-400">
                            {{ toCurrency($order->liq_price) ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-wider">@lang('general.exit_type')</p>
                        <p class="text-base font-bold text-gray-800 dark:text-gray-200">{!! $order->exit_type ?? '-' !!}</p>
                    </div>
                </div>

                <!-- Section 3: Execution Price Analysis Matrix -->
                <div class="overflow-x-auto">
                    <table class="w-full text-start border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50 dark:bg-gray-700/50 text-[11px] uppercase text-gray-500 tracking-wider border-b border-gray-100 dark:border-gray-700">
                                <th class="px-8 py-4 text-start">@lang('general.description')</th>
                                <th class="px-8 py-4 text-center">@lang('general.enter_price')</th>
                                <th class="px-8 py-4 text-center">@lang('general.exit_price')</th>
                                <th class="px-8 py-4 text-end">@lang('general.quantity_amount')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                            <tr class="text-sm text-start">
                                <td class="px-8 py-6">
                                    <p class="font-bold text-gray-900 dark:text-white">@lang('general.futures_position_execution')</p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        Base: {{ $order->baseCurrency->symbol ?? 'USDT' }} | Main:
                                        {{ $order->mainCurrency->symbol ?? '' }}
                                    </p>
                                    <div class="flex gap-4 mt-2 text-xs">
                                        <span class="text-emerald-600 font-medium">TP:
                                            {{ toCurrency($order->take_profit ?? 0) }}</span>
                                        <span class="text-rose-500 font-medium">SL:
                                            {{ toCurrency($order->stop_loss ?? 0) }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center font-mono font-medium text-gray-700 dark:text-gray-300">
                                    {{ toCurrency($order->enter_price) }}
                                </td>
                                <td class="px-8 py-6 text-center font-mono font-medium text-gray-700 dark:text-gray-300">
                                    {{ toCurrency($order->exit_price) ?: '-' }}
                                </td>
                                <td class="px-8 py-6 text-end font-black text-gray-900 dark:text-white text-base font-mono">
                                    {{ toCurrency($order->quantity) }}
                                    <span
                                        class="text-[10px] font-normal text-gray-400">{{ $order->mainCurrency->symbol ?? '' }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Section 4: Balances & Position Performance Breakdown -->
                <div
                    class="p-8 bg-white dark:bg-gray-800 flex flex-col md:flex-row justify-between gap-8 border-t border-gray-100 dark:border-gray-700">

                    <!-- Execution Lifecycle Timeline -->
                    <div class="flex-1 space-y-2 border-l-2 border-gray-100 dark:border-gray-700/80 pl-4">
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">@lang('general.execution_timeline')</p>
                        <div class="text-xs space-y-1.5 text-gray-600 dark:text-gray-400">
                            <p>@lang('general.created_at'): <span
                                    class="font-medium text-gray-800 dark:text-gray-300">{{ $order->getCreatedAt() ?? '-' }}</span>
                            </p>
                            <p>@lang('general.opened_at'): <span
                                    class="font-medium text-gray-800 dark:text-gray-300">{{ $order->getOpenedAt() ?? '-' }}</span>
                            </p>
                            <p>@lang('general.closed_at'): <span
                                    class="font-medium text-gray-800 dark:text-gray-300">{{ $order->getClosedAt() ?? '-' }}</span>
                            </p>
                            @if ($order->getLiquidationAt())
                                <p>@lang('general.liquidation_at'): <span
                                        class="font-bold text-rose-500">{{ $order->getLiquidationAt() }}</span></p>
                            @endif
                        </div>
                    </div>

                    <!-- PnL Ledger & Financial Balances Summary -->
                    <div class="w-full md:w-85 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 font-medium">@lang('general.enter_balance')</span>
                            <span
                                class="font-bold text-gray-900 dark:text-gray-200 font-mono">{{ toCurrency($order->enter_balance) }}
                                {{ $order->baseCurrency->symbol ?? 'USDT' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 font-medium">@lang('general.cost')</span>
                            <span
                                class="font-bold text-gray-900 dark:text-gray-200 font-mono">{{ toCurrency($order->cost) }}
                                {{ $order->baseCurrency->symbol ?? 'USDT' }}</span>
                        </div>
                        <div
                            class="flex justify-between text-sm items-center border-t border-dashed border-gray-200 dark:border-gray-700 pt-3">
                            <span class="text-gray-500 font-medium">@lang('general.fee')</span>
                            <span class="font-bold text-rose-500 font-mono">{{ toCurrency($order->fee) }}
                                {{ $order->baseCurrency->symbol ?? 'USDT' }}</span>
                        </div>

                        <div
                            class="flex justify-between items-center pt-2 border-t border-gray-100 dark:border-gray-700/60">
                            <span
                                class="text-base font-black text-gray-900 dark:text-white uppercase tracking-tight">@lang('general.pnl')</span>
                            <div class="text-right">
                                <p
                                    class="text-2xl font-black font-mono tracking-tight @if ($order->pnl > 0) text-emerald-600 dark:text-emerald-400 @elif($order->pnl < 0) text-rose-600 dark:text-rose-500 @else text-gray-400 @endif">
                                    @if ($order->pnl > 0)
                                        +
                                    @endif{{ toCurrency($order->pnl) }}
                                </p>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">
                                    {{ $order->baseCurrency->symbol ?? 'USDT' }}</p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <!-- Transaction History -->
            <div class="no-print">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="w-2 h-6 bg-primary-600 rounded-full"></span>
                    @lang('general.fee_history')
                </h3>
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <table class="w-full text-sm text-start overflow-auto">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 uppercase text-[10px] font-bold">
                            <tr>
                                <th class="px-6 py-4 text-start">@lang('general.id')</th>
                                <th class="px-6 py-4 text-start">@lang('general.type')</th>
                                <th class="px-6 py-4 text-start">@lang('general.fee')</th>
                                <th class="px-6 py-4 text-start">@lang('general.date')</th>
                                <th class="px-6 py-4 text-start">@lang('general.description')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($order->histories as $history)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-colors">
                                    <td class="text-start px-6 py-4 font-mono text-gray-400">#{{ $history->id }}</td>
                                    <td class="px-6 py-4 text-start">
                                        {!! $history->getType() !!}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-start font-bold tracking-start ">
                                        {{ toCurrency($history->fee) ?? '' }}
                                    </td>

                                    <td class="px-6 py-4 text-start">
                                        {!! $history->getCreatedAt() !!}
                                    </td>
                                    <td class="px-6 py-4 text-start">
                                        {!! $history->description ?? '' !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">
                                        @lang('general.not_found')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="no-print">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="w-2 h-6 bg-primary-600 rounded-full"></span>
                    @lang('general.transaction_history')
                </h3>
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <table class="w-full text-sm text-start overflow-auto">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 uppercase text-[10px] font-bold">
                            <tr>
                                <th class="px-6 py-4 text-start">@lang('general.id')</th>
                                <th class="px-6 py-4 text-start">@lang('general.currency')</th>
                                <th class="px-6 py-4 text-start">@lang('general.amount')</th>
                                <th class="px-6 py-4 text-start">@lang('general.date')</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($order->transactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-colors">
                                    <td class="text-start px-6 py-4 font-mono text-gray-400">#{{ $transaction->id }}</td>
                                    <td class="px-6 py-4 text-start">
                                        <x-admin.currency :currency="$transaction->currency" />
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-start font-bold tracking-start {{ $transaction->getCssClass() }}">
                                        {{ toCurrency($transaction->amount) ?? '' }}
                                    </td>

                                    <td class="px-6 py-4 text-start">
                                        {!! $transaction->getCreatedAt() !!}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">
                                        @lang('general.not_found')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
