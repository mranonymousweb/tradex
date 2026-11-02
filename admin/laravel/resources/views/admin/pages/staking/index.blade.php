@extends('admin.layout.master')

@section('title', trans('general.staking'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- Page Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">@lang('general.staking')</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.excel.staking', request()->query()) }}"
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

        <!-- Filter Bar Component -->
        @include('admin.pages.filter', ['filters' => $filters, 'back' => route('admin.staking.index')])

        <!-- Data Card Container -->
        <div class="bg-white dark:bg-gray-800 w-full min-h-[700px] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/80 overflow-hidden flex flex-col justify-between mt-6">
            <div class="w-full overflow-x-auto">
                <!-- Table Section Header -->
                <div class="p-5 border-b border-gray-100 dark:border-gray-700/50">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">@lang('general.requests')</h3>
                </div>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50/70 dark:bg-gray-700/40">
                    <tr>
                        <th class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">@lang('general.id')</th>
                        <th class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">@lang('general.user')</th>
                        <th class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">@lang('general.currency')</th>
                        <th class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">@lang('general.amount')</th>
                        <th class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">@lang('general.min_day')</th>
                        <th class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">@lang('general.daily_profit_percent')</th>
                        <th class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">@lang('general.created_at')</th>
                        <th class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">@lang('general.status')</th>
                        @canany(['admin.staking.cancel', 'admin.staking.accept', 'admin.staking.details'])
                            <th class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-end tracking-wider uppercase">@lang('general.operation')</th>
                        @endcanany
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                    @forelse($userStakings as $staking)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                            <!-- ID -->
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 dark:text-white">
                                {{$staking->id}}
                            </td>

                            <!-- User Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-admin.user-profile :user="$staking?->user" />
                            </td>

                            <!-- Currency -->
                            <td class="px-6 py-4 whitespace-nowrap text-start">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                    {{$staking?->currency->symbol ?? ''}}
                                </span>
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-4 whitespace-nowrap text-start font-bold text-gray-900 dark:text-white">
                                {{toCurrency($staking->amount)}}
                            </td>

                            <!-- Days Limit -->
                            <td class="px-6 py-4 whitespace-nowrap text-start font-medium text-gray-600 dark:text-gray-400">
                                {{toCurrency($staking->day)}} <span class="text-xs text-gray-400 font-normal">@lang('general.day')</span>
                            </td>

                            <!-- Profit Percentage -->
                            <td class="px-6 py-4 whitespace-nowrap text-start font-semibold text-emerald-600 dark:text-emerald-400">
                                +{{toCurrency($staking->daily_profit_percent)}}%
                            </td>

                            <!-- Created Timestamp -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400 text-xs font-medium">
                                {{$staking->getCreatedAt()}}
                            </td>

                            <!-- Badge Status -->
                            <td class="px-6 py-4 whitespace-nowrap text-start">
                                {!! $staking->getStatus() !!}
                            </td>

                            <!-- Admin Actions Mapping -->
                            @canany(['admin.staking.cancel', 'admin.staking.accept', 'admin.staking.details'])
                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        @if(in_array($staking->status, [config('constants.staking.active'), config('constants.staking.wetting')]))
                                            @can('admin.staking.cancel')
                                                <button onclick="mboxQuestion('{{ route('admin.staking.cancel', $staking) }}')"
                                                        class="inline-flex items-center px-2.5 py-1.5 border border-red-200 dark:border-red-900/30 bg-red-50 dark:bg-red-950/20 text-xs font-semibold text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors duration-150">
                                                    @lang('general.cancel')
                                                </button>
                                            @endcan
                                        @endif

                                        @if($staking->status == config('constants.staking.wetting'))
                                            @can('admin.staking.accept')
                                                <button onclick="mboxQuestion('{{ route('admin.staking.accept', $staking) }}')"
                                                        class="inline-flex items-center px-2.5 py-1.5 bg-primary-600 dark:bg-primary-500 text-xs font-semibold text-white rounded-lg hover:bg-primary-700 dark:hover:bg-primary-600 shadow-sm transition-colors duration-150">
                                                    @lang('general.accept')
                                                </button>
                                            @endcan
                                        @endif

                                        @can('admin.staking.detail')
                                            <a href="{{ route('admin.staking.detail', $staking) }}"
                                               class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-xs font-semibold text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 shadow-sm transition-colors duration-150">
                                                @lang('general.details')
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <!-- Empty Data View Component -->
                        <tr>
                            <td colspan="@canany(['admin.staking.cancel', 'admin.staking.accept', 'admin.staking.details']) 9 @else 8 @endcanany" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-400 dark:text-gray-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H15a3 3 0 1 1-6 0H5.25A2.25 2.25 0 0 0 3 12m18 0v6A2.25 2.25 0 0 1 18.75 20H5.25A2.25 2.25 0 0 1 3 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 9m18 0V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v3" />
                                    </svg>
                                    <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">@lang('general.not_found')</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Dynamic Tailwind Pagination -->
            <div class="p-4 border-t border-gray-100 dark:border-gray-700/60 bg-gray-50/50 dark:bg-gray-800/50">
                {{ $userStakings->withQueryString()->links('pagination::tailwind') }}
            </div>
        </div>
    </main>
@endsection
