@extends('admin.layout.master')

@section('title', trans('setting.futures'))

@section('contents')
    @php
        $activeTab = old('_tab', request('tab', 'general'));
        if ($errors->hasAny(['base_currency_id', 'adl_min_exchange_profit_percent', 'min_trade_amount_usdt'])) {
            $activeTab = 'adl';
        }

        $futureLinks = collect([
            [
                'can' => 'admin.future.index',
                'route' => route('admin.future.index'),
                'title' => __('setting.base_setting'),
                'description' => __('general.future.global_config'),
                'tone' => 'primary',
                'icon' => 'settings',
            ],
            [
                'can' => 'admin.future.asset',
                'route' => route('admin.future.asset'),
                'title' => __('general.future_asset'),
                'description' => __('general.balance_so_far'),
                'tone' => 'emerald',
                'icon' => 'wallet',
            ],
            [
                'can' => 'admin.future.market.currency',
                'route' => route('admin.future.market.currency'),
                'title' => __('setting.base_market'),
                'description' => __('general.pairs'),
                'tone' => 'sky',
                'icon' => 'chart',
            ],
            [
                'can' => 'admin.future.bot',
                'route' => route('admin.future.bot'),
                'title' => __('general.bot_setting'),
                'description' => __('general.bot_settings.title'),
                'tone' => 'amber',
                'icon' => 'bot',
            ],
            [
                'can' => null,
                'route' => route('admin.setting.futures', ['tab' => 'adl']),
                'title' => __('general.adl_settings'),
                'description' => __('general.future_orders'),
                'tone' => 'rose',
                'icon' => 'shield',
                'active' => $activeTab === 'adl',
            ],
        ])->filter(function ($item) {
            if (blank($item['can'])) {
                return true;
            }

            return auth('admin')->user()?->can($item['can']) ?? false;
        });
    @endphp

    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-background-dark">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-primary-600 dark:text-primary-400">
                    @lang('master.setting')
                </p>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('setting.futures')</h2>
                <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                    @lang('general.manage_futures_description')
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if ($activeTab === 'adl')
                    <a href="{{ route('admin.setting.futures') }}"
                        class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        @lang('general.future.global_config')
                    </a>
                @endif

                <a href="{{ route('admin.setting.index') }}"
                    class="group inline-flex items-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-all hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-0.5 rtl:rotate-180"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        @if ($activeTab !== 'adl')
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($futureLinks as $item)
                    @php
                        $tones = [
                            'primary' => [
                                'icon' => 'bg-primary-100 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400',
                                'ring' => 'hover:border-primary-300 dark:hover:border-primary-700',
                                'arrow' => 'group-hover:text-primary-600 dark:group-hover:text-primary-400',
                            ],
                            'emerald' => [
                                'icon' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'ring' => 'hover:border-emerald-300 dark:hover:border-emerald-700',
                                'arrow' => 'group-hover:text-emerald-600 dark:group-hover:text-emerald-400',
                            ],
                            'sky' => [
                                'icon' => 'bg-sky-100 text-sky-600 dark:bg-sky-900/30 dark:text-sky-400',
                                'ring' => 'hover:border-sky-300 dark:hover:border-sky-700',
                                'arrow' => 'group-hover:text-sky-600 dark:group-hover:text-sky-400',
                            ],
                            'amber' => [
                                'icon' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                                'ring' => 'hover:border-amber-300 dark:hover:border-amber-700',
                                'arrow' => 'group-hover:text-amber-600 dark:group-hover:text-amber-400',
                            ],
                            'rose' => [
                                'icon' => 'bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400',
                                'ring' => 'hover:border-rose-300 dark:hover:border-rose-700',
                                'arrow' => 'group-hover:text-rose-600 dark:group-hover:text-rose-400',
                            ],
                        ];
                        $tone = $tones[$item['tone']] ?? $tones['primary'];
                    @endphp

                    <a href="{{ $item['route'] }}"
                        class="group relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-soft transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-card-dark {{ $tone['ring'] }} {{ !empty($item['active']) ? 'ring-2 ring-primary-500/40' : '' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $tone['icon'] }}">
                                    @if ($item['icon'] === 'settings')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281a1.125 1.125 0 0 0 .865.997l1.437.553a1.125 1.125 0 0 1 .51 1.685l-1.004.827a1.125 1.125 0 0 0 0 1.742l1.004.827a1.125 1.125 0 0 1-.51 1.685l-1.437.553a1.125 1.125 0 0 0-.865.997l-.213 1.281a1.125 1.125 0 0 1-1.11.94h-2.593a1.125 1.125 0 0 1-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.865-.997l-1.437-.553a1.125 1.125 0 0 1-.51-1.685l1.004-.827a1.125 1.125 0 0 0 0-1.742l-1.004-.827a1.125 1.125 0 0 1 .51-1.685l1.437-.553a1.125 1.125 0 0 0 .865-.997l.213-1.281Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    @elseif ($item['icon'] === 'wallet')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1 0-6h5.25A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18V6a2.25 2.25 0 0 1 2.25-2.25h13.5" />
                                        </svg>
                                    @elseif ($item['icon'] === 'chart')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22M2.25 18v-7.5A2.25 2.25 0 0 1 4.5 8.25h15A2.25 2.25 0 0 1 21.75 10.5V18" />
                                        </svg>
                                    @elseif ($item['icon'] === 'bot')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M15.75 3v1.5M4.5 9.75h15A1.5 1.5 0 0 1 21 11.25v7.5A1.5 1.5 0 0 1 19.5 20.25h-15A1.5 1.5 0 0 1 3 18.75v-7.5A1.5 1.5 0 0 1 4.5 9.75Zm4.5 5.25h.008v.008H9v-.008Zm3 0h.008v.008H12v-.008Zm3 0h.008v.008H15v-.008Z" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.25-8.25-3.286Z" />
                                        </svg>
                                    @endif
                                </div>

                                <div>
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white">
                                        {{ $item['title'] }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $item['description'] }}
                                    </p>
                                </div>
                            </div>

                            <span class="mt-1 text-gray-300 transition-colors {{ $tone['arrow'] }} dark:text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="space-y-6">
                <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-soft dark:border-gray-700 dark:bg-card-dark">
                    <div class="flex items-center justify-between gap-4 border-b border-gray-100 bg-gray-50/60 px-6 py-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">@lang('general.adl_settings')</h3>
                            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">@lang('general.manage_futures_description')</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/30 dark:text-rose-300">
                            ADL
                        </span>
                    </div>

                    <form method="POST" action="{{ route('admin.setting.futures.adl.store') }}" class="p-6">
                        @csrf
                        <input type="hidden" name="_tab" value="adl">

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                            <div class="md:col-span-2 xl:col-span-1">
                                <x-admin.select2
                                    name="base_currency_id"
                                    :label="__('general.base_currency')"
                                    :options="$currencies"
                                    :selected="old('base_currency_id', $adlSetting?->base_currency_id)"
                                    :required="true"
                                />
                            </div>

                            <div>
                                <label for="adl_min_exchange_profit_percent"
                                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    @lang('general.adl_min_exchange_profit_percent')
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        type="number"
                                        id="adl_min_exchange_profit_percent"
                                        name="adl_min_exchange_profit_percent"
                                        value="{{ old('adl_min_exchange_profit_percent', $adlSetting?->adl_min_exchange_profit_percent ?? '') }}"
                                        placeholder="@lang('general.adl_min_exchange_profit_percent_placeholder')"
                                        min="0"
                                        max="100"
                                        step="1"
                                        required
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 pe-10 shadow-sm transition-colors duration-200 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    >
                                    <span class="pointer-events-none absolute inset-y-0 end-4 flex items-center text-sm text-gray-400">%</span>
                                </div>
                                @error('adl_min_exchange_profit_percent')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="min_trade_amount_usdt"
                                    class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    @lang('general.balance_so_far')
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        id="min_trade_amount_usdt"
                                        name="min_trade_amount_usdt"
                                        value="{{ filled(old('min_trade_amount_usdt', $adlSetting?->min_trade_amount_usdt)) ? toCurrency(old('min_trade_amount_usdt', $adlSetting?->min_trade_amount_usdt), 0) : '' }}"
                                        placeholder="@lang('general.adl_fund_balance_placeholder')"
                                        required
                                        class="separator w-full rounded-lg border border-gray-300 px-4 py-2.5 shadow-sm transition-colors duration-200 focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    >
                                </div>
                                @error('min_trade_amount_usdt')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end border-t border-gray-100 pt-4 dark:border-gray-700">
                            <button type="submit"
                                class="rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition-all hover:bg-primary-700">
                                @lang('general.save')
                            </button>
                        </div>
                    </form>
                </section>

                <section>
                    <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">@lang('general.future_orders')</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ isset($orders) ? $orders->total() : 0 }} @lang('general.future_orders')
                            </p>
                        </div>
                    </div>

                    @include('admin.pages.filter', [
                        'filters' => $filters,
                        'hidden' => ['tab' => 'adl'],
                        'back' => route('admin.setting.futures', ['tab' => 'adl']),
                    ])

                    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-soft dark:border-gray-700 dark:bg-card-dark">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full text-start text-sm">
                                <thead>
                                    <tr class="h-14 border-b border-gray-100 bg-gray-50/60 text-gray-600 dark:border-gray-700 dark:bg-gray-800/50 dark:text-gray-300">
                                        <th class="px-5 py-3 text-start font-semibold">@lang('general.id')</th>
                                        <th class="px-5 py-3 text-start font-semibold">@lang('general.user')</th>
                                        <th class="px-5 py-3 text-start font-semibold">@lang('general.pairs')</th>
                                        <th class="px-5 py-3 text-start font-semibold">@lang('general.pnl')</th>
                                        <th class="px-5 py-3 text-start font-semibold">@lang('general.created_at')</th>
                                        <th class="px-5 py-3 text-start font-semibold">@lang('general.closed_at')</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 text-gray-600 dark:divide-gray-700/60 dark:text-gray-400">
                                    @forelse($orders ?? [] as $order)
                                        <tr class="h-16 transition-colors duration-150 hover:bg-gray-50/70 dark:hover:bg-gray-800/40">
                                            <td class="whitespace-nowrap px-5 py-3.5 font-medium text-gray-900 dark:text-gray-200">
                                                #{{ $order->id }}
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3.5">
                                                <x-admin.user-profile :user="$order?->user" />
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3.5">
                                                <span class="inline-flex rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                                                    {{ $order->pairs }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3.5 font-mono font-bold">
                                                @if ($order->pnl > 0)
                                                    <span class="inline-flex rounded-lg bg-emerald-50 px-2.5 py-1 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400">
                                                        +{{ toCurrency($order->pnl, 0) }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex rounded-lg bg-red-50 px-2.5 py-1 text-red-600 dark:bg-red-900/20 dark:text-red-400">
                                                        {{ toCurrency($order->pnl, 0) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3.5 text-xs text-gray-400 dark:text-gray-500">
                                                {{ $order->getCreatedAt() }}
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3.5 text-xs text-gray-400 dark:text-gray-500">
                                                {{ $order->closed_at ? $order->getClosedAt() : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="px-5 py-16 text-center text-gray-400 dark:text-gray-500" colspan="6">
                                                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                                                    </svg>
                                                </div>
                                                @lang('general.not_found')
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if ($orders)
                        <div class="mt-4">
                            {{ $orders->links('pagination::tailwind') }}
                        </div>
                    @endif
                </section>
            </div>
        @endif
    </main>
@endsection
