@extends('admin.layout.master')

@section('title', trans('general.transactions'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- Page Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                    @lang('general.transactions')
                </h2>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.excel.transaction', request()->query()) }}"
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


        <!-- Filter Bar Layout -->
        @include('admin.pages.filter', [
            'filters' => $filters,
            'back' => route('admin.reports.transaction'),
        ])

        <!-- Data Table Wrapper -->
        <div
            class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/80 overflow-hidden flex flex-col justify-between mt-6">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50/70 dark:bg-gray-700/40">
                        <tr>
                            <th
                                class="px-6 py-3.5 font-semibold  text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                @lang('general.id')</th>
                            <th
                                class="px-6 py-3.5 font-semibold  text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                @lang('general.user')</th>
                            <th
                                class="px-6 py-3.5 font-semibold  text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                @lang('general.currency')</th>
                            <th
                                class="px-6 py-3.5 font-semibold  text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                @lang('general.amount')</th>
                            <th
                                class="px-6 py-3.5 font-semibold  text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                @lang('general.section')</th>
                            <th
                                class="px-6 py-3.5 font-semibold  text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                @lang('general.status')</th>
                            <th
                                class="px-6 py-3.5 font-semibold  text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                @lang('general.created_at')</th>
                            <th
                                class="px-6 py-3.5 font-semibold  text-gray-500 dark:text-gray-400 text-end tracking-wider uppercase">
                                @lang('general.operation')</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                <!-- ID -->
                                <td
                                    class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 dark:text-white text-start">
                                    {{ $transaction->id }}
                                </td>

                                <!-- User Details -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-admin.user-profile :user="$transaction?->user" />
                                </td>

                                <!-- Currency Identifier -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                        {{ $transaction->currency->symbol ?? '' }}
                                    </span>
                                </td>

                                <!-- Transaction Amount -->
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-start font-bold tracking-start {{ $transaction->getCssClass() }}">
                                    {{ toCurrency($transaction->amount) ?? '' }}
                                </td>

                                <!-- Section Ledger Category -->
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-400 font-medium text-start">
                                    {{ \App\Models\UserTransaction::getSection($transaction->section) }}
                                </td>

                                <!-- Operational Badge Status -->
                                <td class="px-6 py-4 whitespace-nowrap text-start">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                        {{ \App\Models\UserTransaction::getStatus($transaction->status) }}
                                    </span>
                                </td>

                                <!-- Created Timestamp -->
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400 text-xs font-medium">
                                    {{ $transaction->getCreatedAt() }}
                                </td>

                                <!-- Operations Trigger Button -->
                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                    <a href="{{ route('admin.reports.transaction.edit', $transaction) }}"
                                        class="text-primary-600 hover:text-primary-800 font-bold text-xs underline underline-offset-4">
                                        @lang('general.details')
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <!-- Clean Empty Dataset Fallback UI -->
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-8 h-8 text-gray-400 dark:text-gray-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                        </svg>
                                        <span
                                            class="text-gray-500 dark:text-gray-400 text-sm font-medium">@lang('general.not_found')</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Contextual Layout Pagination Boundary -->
            <div class="p-4 border-t border-gray-100 dark:border-gray-700/60 bg-gray-50/50 dark:bg-gray-800/50">
                {{ $transactions->withQueryString()->links('pagination::tailwind') }}
            </div>
        </div>
    </main>
@endsection
