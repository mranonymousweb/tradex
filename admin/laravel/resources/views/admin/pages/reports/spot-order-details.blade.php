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

        <!-- Header & Actions -->
        <div
            class="max-w-6xl mx-auto mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 no-print">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">@lang('general.order_details')</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Order ID: #{{ $order->id }}| @lang('general.created_at'): {{ $order->getCreatedAt() }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.spot.order') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-primary-600 text-white rounded-lg text-sm font-semibold shadow-md hover:opacity-90 transition-all">
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="max-w-6xl mx-auto space-y-6">

            <!-- Main Invoice Wrapper -->
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden border-t-[12px] border-t-primary-600">

                <!-- Section 1: User & Order Status -->
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h3 class="text-xs uppercase tracking-widest text-gray-400 font-bold mb-3">@lang('general.customer_details')</h3>
                        <div class="flex items-center gap-4">
                            <div
                                class="relative inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-primary-50 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-800 shadow-sm overflow-hidden group">
                                @if($order->user && $order->user->avatar)
                                    <img src="{{ $order->user->getAvatar() }}"
                                         alt="{{ $order->user->name }}"
                                         loading="lazy"
                                         class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110">
                                @else
                                    <span class="font-bold text-2xl text-primary-600 dark:text-primary-400 uppercase">{{ substr($order->user->name ?? 'U', 0, 1) }}</span>
                                @endif

                            </div>
                            <div>
                                <a href="{{ route('admin.user.edit', $order->user_id) }}" class="text-sm font-bold text-gray-900 dark:text-white hover:text-primary-600 transition-colors">
                                    {{ $order->user?->getFullInfo() ?? '-' }}
                                </a>
                                <p class="text-sm text-gray-500">{{ $order->user->email ?? '' }}</p>
                                <p class="text-sm text-gray-500">{{ $order->user->mobile ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="md:text-start">

                        <div class="mt-2 text-sm text-gray-500 space-y-1">
                            <p>@lang('general.order_status'): <span class="font-bold uppercase">{!! $order->status_badge !!}  </span></p>
                            <p>@lang('general.type'): <span class="font-bold text-gray-700 dark:text-gray-300">{!! $order->getType() !!}  </span></p>
                            <p>@lang('general.side'): <span class="font-bold uppercase">{!! $order->side_badge !!}  </span></p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Market & Pairs Info -->
                <div
                    class="grid grid-cols-2 md:grid-cols-4 gap-6 p-8 bg-gray-50/50 dark:bg-gray-900/20 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black">@lang('general.id')</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black">@lang('general.trading_pair')</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $order->pairs }}</p>
                    </div>

                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black">@lang('general.stop_price')</p>
                        <p class="text-lg font-bold text-amber-600">{{ toCurrency($order->stop_price) ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black">@lang('general.market_price')</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ toCurrency($order->market_price) ?: '-' }}</p>
                    </div>
                </div>

                <!-- Section 3: Execution Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-start border-collapse">
                        <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 text-[11px] uppercase text-gray-500 tracking-tighter border-b border-gray-100 dark:border-gray-700">
                            <th class="px-8 py-4 text-start">@lang('general.description')</th>
                            <th class="px-8 py-4 text-start">@lang('general.quantity_amount')</th>
                            <th class="px-8 py-4 text-start">@lang('general.price')</th>
                            <th class="px-8 py-4 text-end">@lang('general.total_cost')</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        <tr class="text-sm text-start">
                            <td class="px-8 py-6">
                                <p class="font-bold text-gray-900 dark:text-white">@lang('general.spot_trade_execution')</p>
                                <p class="text-xs text-gray-500">Base: {{ $order->base_currency_symbol }} |
                                    Main: {{ $order->main_currency_symbol }}</p>
                            </td>
                            <td class="px-8 py-6 text-start">
                                <p class="font-bold">{{ toCurrency($order->amount) }}</p>
                                <p class="text-[10px] text-green-600">@lang('general.filled')
                                    : {{ toCurrency($order->filled_amount) }}</p>
                            </td>
                            <td class="px-8 py-6 text-start text-gray-600 dark:text-gray-400 font-medium">
                                {{ toCurrency($order->price) ?: toCurrency($order->price) }}
                            </td>
                            <td class="px-8 py-6 text-end font-black text-gray-900 dark:text-white text-base">
                                {{ toCurrency($order->total) }} <span
                                    class="text-[10px] font-normal text-gray-400">{{ $order->base_currency_symbol }}</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Section 4: Totals & Fees Breakdown -->
                <div class="p-8 bg-white dark:bg-gray-800 flex flex-col md:flex-row justify-between gap-8">
                    <!-- Timeline Info -->
                    <div class="flex-1 space-y-2 border-l-2 border-gray-100 dark:border-gray-700 pl-4">
                        <p class="text-xs text-gray-400 font-bold uppercase">@lang('general.execution_timeline')</p>
                        <div class="text-xs space-y-1 text-gray-600 dark:text-gray-400">
                            <p>@lang('general.open_at'): <span class="font-medium">{{ $order->getOpenedAt() ?? '-' }}</span>
                            </p>
                            <p>@lang('general.done_at'): <span
                                    class="font-medium text-green-600">{{ $order->getDoneAt() ?? '-' }}</span></p>
                            @if($order->closed_at)
                                <p>@lang('general.canceled_at'): <span
                                        class="font-medium text-red-500">{{ $order->getClosedAt() }}</span></p>
                            @endif
                        </div>
                    </div>

                    <!-- Financial Summary -->
                    <div class="w-full md:w-80 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 font-medium">@lang('general.filled_total')</span>
                            <span
                                class="font-bold text-gray-900 dark:text-gray-200">{{ toCurrency($order->filled_total) }} {{ $order->base_currency_symbol }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 font-medium">@lang('general.receive_amount')</span>
                            <span class="font-bold text-green-600">{{ toCurrency($order->getAmount()) }} {{ $order->getSymbol() }}</span>
                        </div>
                        <div
                            class="flex justify-between text-sm items-center border-t border-dashed border-gray-200 dark:border-gray-700 pt-3">
                            <span class="text-gray-500 font-medium">@lang('general.commission_fee')</span>
                            <span
                                class="font-bold text-red-500">{{ toCurrency($order->fee) }} {{ $order->getSymbol() }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span
                                class="text-base font-black text-gray-900 dark:text-white uppercase">@lang('general.grand_total')</span>
                            <div class="text-right">
                                <p class="text-2xl font-black text-primary-600 tracking-tight">
                                    {{ toCurrency($order->getTotal()) }}
                                </p>
                                <p class="text-[10px] text-gray-400 uppercase font-bold">{{ $order->getSymbol() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Note -->
                {{--                <div class="px-8 py-4 bg-gray-50 dark:bg-gray-700/30 text-center border-t border-gray-100 dark:border-gray-700">--}}
                {{--                    <p class="text-[10px] text-gray-400 uppercase tracking-widest">--}}
                {{--                        @lang('general.order_meta_info'): {{ is_array($order->meta) ? json_encode($order->meta) : $order->meta ?? 'N/A' }}--}}
                {{--                    </p>--}}
                {{--                </div>--}}
            </div>

            <!-- Transaction History -->
            <div class="no-print space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-6 bg-primary-600 rounded-full"></span>
                        @lang('general.transaction_history')
                    </h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        {{ $historys->count() }} @lang('general.transaction')
                    </span>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($historys as $history)
                        @php
                            $meta = $history->meta ?? [];
                            $historyDetailsId = 'history-details-' . $history->id;
                        @endphp
                        <details class="group">
                            <summary class="list-none cursor-pointer px-4 py-3 sm:px-5 flex flex-wrap items-center gap-x-4 gap-y-2 hover:bg-gray-50 dark:hover:bg-gray-900/30 transition-colors [&::-webkit-details-marker]:hidden">
                                <span class="font-mono text-xs font-bold text-primary-600 dark:text-primary-400 shrink-0">#{{ $history->id }}</span>

                                @if($history->is_robot)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-[10px] font-bold uppercase shrink-0">
                                        BOT
                                    </span>
                                @else
                                    <span class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate max-w-[160px] sm:max-w-[220px]">
                                        {{ $history?->counter_market?->user?->getFullInfo() ?? '-' }}
                                    </span>
                                @endif

                                <span class="text-xs text-gray-500 dark:text-gray-400 shrink-0">
                                    {{ toCurrency($history->amount) }}
                                    <span class="text-gray-400">@</span>
                                    {{ toCurrency($history->price) }}
                                </span>

                                <span class="text-[11px] text-gray-400 ms-auto shrink-0">{{ $history->getCreatedAt() }}</span>

                                <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>

                            <div class="px-4 pb-4 sm:px-5 sm:pb-5 bg-gray-50/60 dark:bg-gray-900/20 border-t border-gray-100 dark:border-gray-700">
                                <div class="pt-3 flex flex-wrap items-center justify-between gap-2 mb-3">
                                    <p class="text-xs text-gray-500">
                                        @lang('general.amount'):
                                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ toCurrency($history->amount) }}</span>
                                        ·
                                        @lang('general.price'):
                                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ toCurrency($history->price) }}</span>
                                    </p>
                                    <a href="{{ route('admin.reports.spot.order.detail', $history?->market) }}"
                                       class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-primary-50 text-primary-700 hover:bg-primary-100 dark:bg-primary-900/30 dark:text-primary-300">
                                        @lang('general.details')
                                    </a>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-3">
                                        <h4 class="text-[10px] uppercase tracking-wider font-bold text-gray-400 mb-2">@lang('general.details')</h4>
                                        @if(filled($meta))
                                            <div class="space-y-1.5 max-h-40 overflow-y-auto">
                                                @foreach($meta as $key => $value)
                                                    @continue(is_null($value))
                                                    <div class="flex items-start justify-between gap-3 text-xs">
                                                        <span class="text-gray-500 shrink-0">{{ Str::headline($key) }}</span>
                                                        <span class="text-gray-800 dark:text-gray-200 text-end break-all">
                                                            @if(is_bool($value))
                                                                {{ $value ? __('general.yes') : __('general.no') }}
                                                            @elseif(is_numeric($value))
                                                                {{ rtrim(rtrim(number_format((float) $value, 18, '.', ''), '0'), '.') }}
                                                            @elseif(is_array($value))
                                                                {{ json_encode($value, JSON_UNESCAPED_UNICODE) }}
                                                            @else
                                                                {{ $value }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-xs text-gray-400 italic">@lang('general.not_found')</p>
                                        @endif
                                    </div>

                                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-3">
                                        <h4 class="text-[10px] uppercase tracking-wider font-bold text-gray-400 mb-2">@lang('general.operation')</h4>
                                        @if(filled($history->operation))
                                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                                @foreach($history->operation as $role => $data)
                                                    <div class="rounded-md border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 p-2.5">
                                                        <div class="flex items-center justify-between mb-1.5">
                                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $role === 'maker' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' }}">
                                                                {{ ucfirst($role) }}
                                                            </span>
                                                            <span class="text-[11px] font-mono text-gray-400">#{{ $data['id'] ?? '-' }}</span>
                                                        </div>
                                                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-gray-600 dark:text-gray-300">
                                                            <span>Fee: <strong>{{ $data['fee'] ?? '-' }}</strong></span>
                                                            <span>Status: <strong>{{ $data['status'] ?? '-' }}</strong></span>
                                                        </div>
                                                        @if(!empty($data['meta']))
                                                            <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700 space-y-1">
                                                                @foreach($data['meta'] as $key => $value)
                                                                    <div class="flex justify-between gap-2 text-[11px]">
                                                                        <span class="text-gray-500">{{ Str::headline($key) }}</span>
                                                                        <span class="text-gray-800 dark:text-gray-200 text-end break-all">
                                                                            {{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}
                                                                        </span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-xs text-gray-400 italic">@lang('general.not_found')</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </details>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-sm text-gray-400 italic">@lang('general.not_found')</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @include('admin.partial.supplier-exchange-data', ['record' => $order])
        </div>
    </main>

@endsection
