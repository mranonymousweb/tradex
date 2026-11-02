@props(['orders' => []])

<div
    class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700/80 shadow-sm w-full overflow-hidden">
    <div class="overflow-x-auto w-full">
        <table class="w-full text-sm text-start">
            <thead>
                <tr
                    class="border-b border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 bg-gray-50/50 dark:bg-gray-800/50 h-14">
                    <th class="px-4 py-3 font-semibold text-start tracking-start">@lang('general.id')</th>
                    <th class="px-4 py-3 font-semibold text-start tracking-start">@lang('general.user')</th>
                    <th class="px-4 py-3 font-semibold text-start tracking-start">@lang('general.pairs')</th>
                    <th class="px-4 py-3 font-semibold text-start tracking-start">@lang('general.type')</th>
                    <th class="px-4 py-3 font-semibold text-start tracking-start">@lang('general.side')</th>
                    <th class="px-4 py-3 font-semibold text-start tracking-start">@lang('general.total') <span
                            class="text-xs font-normal text-gray-400">(USDT)</span></th>
                    <th class="px-4 py-3 font-semibold text-start tracking-start">@lang('general.pnl') <span
                            class="text-xs font-normal text-gray-400">(USDT)</span></th>
                    <th class="px-4 py-3 font-semibold text-start tracking-start">@lang('general.fee') <span
                            class="text-xs font-normal text-gray-400">(USDT)</span></th>
                    <th class="px-4 py-3 font-semibold text-start tracking-start">@lang('general.created_at')</th>
                    <th class="px-4 py-3 font-semibold text-start tracking-start">@lang('general.status')</th>
                    <th class="px-4 py-3 font-semibold text-start tracking-end">@lang('general.operation')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700/60 text-gray-600 dark:text-gray-400">
                @forelse($orders as $order)
                    <tr
                        class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors duration-150 h-16 text-start tracking-start">
                        <!-- ID -->
                        <td
                            class="px-4 py-3.5 whitespace-nowrap font-medium text-gray-900 dark:text-gray-300 min-w-[70px]">
                            <span>{{ $order->id }}</span>
                        </td>

                        <!-- User -->
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <x-admin.user-profile :user="$order?->user" />
                        </td>

                        <!-- Pairs -->
                        <td
                            class="px-4 py-3.5 whitespace-nowrap font-semibold text-gray-800 dark:text-gray-200 min-w-[90px]">
                            <span>{{ $order->pairs }}</span>
                        </td>

                        <!-- Type -->
                        <td class="px-4 py-3.5 whitespace-nowrap text-xs font-medium min-w-[80px]">
                            <span
                                class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                {{ $order->getType() }}
                            </span>
                        </td>

                        <!-- Side -->
                        <td class="px-4 py-3.5 whitespace-nowrap font-medium min-w-[70px]">
                            <span>{!! $order->getSide() !!}</span>
                        </td>

                        <!-- Total Cost -->
                        <td
                            class="px-4 py-3.5 whitespace-nowrap font-mono font-medium text-gray-900 dark:text-white min-w-[100px]">
                            <span>{{ toCurrency($order->cost) }}</span>
                        </td>

                        <!-- PnL -->
                        <td class="px-4 py-3.5 whitespace-nowrap font-mono font-bold min-w-[110px]">
                            @if ($order->pnl > 0)
                                <span
                                    class="text-emerald-600 dark:text-emerald-400">+{{ toCurrency($order->pnl) }}</span>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">{{ toCurrency($order->pnl) }}</span>
                            @endif
                        </td>

                        <!-- Fee -->
                        <td
                            class="px-4 py-3.5 whitespace-nowrap font-mono text-gray-500 dark:text-gray-400 min-w-[90px]">
                            <span>{{ toCurrency($order->fee) }}</span>
                        </td>

                        <!-- Created At -->
                        <td
                            class="px-4 py-3.5 whitespace-nowrap text-xs text-gray-400 dark:text-gray-500 min-w-[130px]">
                            <span>{{ $order->getCreatedAt() }}</span>
                        </td>

                        <!-- Status Badge -->
                        <td class="px-4 py-3.5 whitespace-nowrap min-w-[100px]">
                            <span>{!! $order?->status_badge ?? '-' !!}</span>
                        </td>

                        <!-- Operation Action -->
                        <td class="px-4 py-3.5 whitespace-nowrap min-w-[90px]">
                            <a href="{{ route('admin.reports.future.order.detail', $order) }}"
                                class="text-primary-600 hover:text-primary-800 font-bold text-xs underline underline-offset-4">
                                @lang('general.details')
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-12 text-center text-gray-400 dark:text-gray-500 bg-gray-50/30 dark:bg-gray-800/10 font-medium"
                            colspan="11">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-6 text-gray-300 dark:text-gray-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18" />
                                </svg>
                                <span class="text-sm">@lang('general.not_found')</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination Grid Footer wrapper layout -->
<div class="mt-4">
    {{ $orders->links('pagination::tailwind') }}
</div>
