@extends('admin.layout.master')

@section('title', trans('general.profits'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <!-- Header Page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                @lang('general.profits')
            </h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.excel.profits',$currencyId) }}"
                   class="text-white inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-gray-900 dark:text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                         stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 16.5V3m0 13.5 4.5-4.5M12 16.5l-4.5-4.5M3.75 19.5h16.5"/>
                    </svg>
                    <span>@lang('general.download_excel')</span>
                </a>
            </div>
        </div>

        <!-- Filter Area Wrapper Container -->
        @include('admin.pages.filter',['filters' => $filters,'back'=>route('admin.reports.profits',$currencyId)])

        <!-- Main Ledger Card Container -->
        <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden flex flex-col min-h-[600px]">

            <!-- Table Wrapper -->
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-sm text-gray-500 dark:text-gray-400 border-collapse">
                    <thead
                            class="bg-gray-50/70 dark:bg-gray-700/40 text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th scope="col"
                            class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">@lang('general.id')</th>
                        <th scope="col"
                            class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">@lang('general.user')</th>
                        <th scope="col"
                            class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">@lang('general.currency')</th>
                        <th scope="col"
                            class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">@lang('general.section')</th>
                        <th scope="col"
                            class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">@lang('general.transaction_id')</th>
                        <th scope="col"
                            class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">@lang('general.profit')</th>
                        <th scope="col"
                            class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">@lang('general.created_at')</th>
                        <th scope="col"
                            class="text-end px-6 py-4 font-semibold  uppercase tracking-wider">@lang('general.operation')</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-700/30 transition-colors duration-150">

                            <!-- Record ID -->
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-gray-600 dark:text-gray-400">
                                {{ $transaction->id }}
                            </td>

                            <!-- Bound User Account Profile -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-admin.user-profile :user="$transaction?->user"/>
                            </td>
                            <!-- Linked Asset Logo Icon -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <x-admin.currency :currency="$transaction?->currency"/>
                            </td>
                            <!-- Activity Ledger Section Type -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">
                                    <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md  font-medium bg-gray-100 dark:bg-gray-900/60 text-gray-800 dark:text-gray-200">
                                        {{ \App\Models\UserTransaction::sections($transaction->section) }}
                                    </span>
                            </td>

                            <!-- Polymorphic Transaction Reference Hash ID -->
                            <td class="px-6 py-4 whitespace-nowrap font-mono  text-gray-500 dark:text-gray-400">
                                {{ $transaction->transactionable_id ?? $transaction->id }}
                            </td>

                            <!-- Individual Generated Profit Split -->
                            <td class="px-6 py-4 text-right whitespace-nowrap font-bold text-emerald-600 dark:text-emerald-400">
                                {{ toCurrency($transaction->fee) }} <span
                                        class="text-xs font-normal text-gray-400 dark:text-gray-500 uppercase ml-0.5">{{ $transaction->currency?->symbol }}</span>
                            </td>

                            <!-- Created Timestamp Field -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400 text-xs">
                                {{ to_jalali( $transaction->created_at,true) }}
                            </td>

                            <!-- Polymorphic Routing Action Control -->
                            <td class="px-6 py-4 text-end whitespace-nowrap">
                                <a href="{{\App\Models\UserTransaction::getUrl($transaction->section,$transaction->transactionable_id ?? $transaction->id)}}"
                                   class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-all duration-150 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                                    @lang('general.see')
                                </a>
                            </td>
                        </tr>
                    @empty
                        <!-- Grid View Empty Standby State -->
                        <tr>
                            <td colspan="8" class="px-6 py-24 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center justify-center">
                                    <div
                                            class="p-4 rounded-full bg-gray-50 dark:bg-gray-700/50 text-gray-400 dark:text-gray-500 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                        </svg>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        @lang('general.not_found')
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Wrapper Footer -->
            @if($transactions->hasPages())
                <div
                        class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/20 border-t border-gray-100 dark:border-gray-700/60">
                    {{ $transactions->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </main>
@endsection
