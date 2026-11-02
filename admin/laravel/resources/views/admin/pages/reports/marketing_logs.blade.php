@extends('admin.layout.master')

@section('title', trans('general.marketing_logs'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <!-- Header Page -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    @lang('general.marketing_logs')
                </h2>
            </div>
            <div class="shrink-0">
                <a href="{{ route('admin.reports.marketing.users', ['parent_id' => $user->parent_id]) }}"
                    class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold rounded-xl bg-white text-gray-700 hover:text-gray-900 hover:bg-gray-100 border border-gray-200 shadow-sm transition-all duration-200 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 rtl:ml-2 rtl:mr-0 transform rtl:rotate-180"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
                <a href="{{ route('admin.reports.excel.marketingLogs',$user) }}"
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

        <!-- Filter Component Wrapper -->
        @include('admin.pages.filter', [
            'filters' => $filters,
            'back' => route('admin.reports.marketing.logs', ['user_id' => $user->id]),
            'hidden' => ['user_id' => $user->id],
        ])

        <!-- Main Ledger Card Container -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden flex flex-col min-h-[600px]">

            <!-- Table Wrapper -->
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-sm text-gray-500 dark:text-gray-400 border-collapse">
                    <thead
                        class="bg-gray-50/70 dark:bg-gray-700/40 text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th scope="col" class="text-start px-6 py-4 font-semibold uppercase tracking-wider">
                                @lang('general.id')</th>
                            <th scope="col" class="text-start px-6 py-4 font-semibold uptracking-wider">@lang('general.user')
                            </th>
                            <th scope="col" class="text-start px-4 py-4 font-semibold uppercase tracking-wider">
                                @lang('general.currency')</th>
                            <th scope="col" class="text-start px-6 py-4 font-semibold   uppercase tracking-wider">
                                @lang('general.section')</th>
                            <th scope="col" class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">
                                @lang('general.total_profit')</th>
                            <th scope="col" class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">
                                @lang('general.parent_amount')</th>
                            <th scope="col" class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">
                                @lang('general.user_amount')</th>
                            <th scope="col" class="text-end px-6 py-4 font-semibold  uppercase tracking-wider">
                                @lang('general.created_at')</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                <!-- ID -->
                                <td
                                    class="px-6 py-4 text-start whitespace-nowrap font-mono  text-gray-600 dark:text-gray-400">
                                    {{ $log->id }}
                                </td>

                                <!-- User Profile Hook -->
                                <td class="px-6 py-4 whitespace-nowrap text-start">
                                    <x-admin.user-profile :user="$log?->user" />
                                </td>

                                <!-- Asset Currency Unit -->
                                <td class="px-4 py-4 text-start whitespace-nowrap font-bold text-gray-900 dark:text-white">
                                    <x-admin.currency :currency="$log?->currency" />
                                </td>

                                <!-- Channel Section Type -->
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">
                                    {{ \App\Models\MarketingLog::getSection($log->section) }}
                                </td>

                                <!-- Gross Total Payout -->
                                <td class="px-6 py-4 text-start whitespace-nowrap font-bold text-gray-900 dark:text-white">
                                    {{ toCurrency($log->total_amount) }}
                                </td>

                                <!-- Parent Network Split share -->
                                <td
                                    class="px-6 py-4 text-start whitespace-nowrap font-semibold text-emerald-600 dark:text-emerald-400">
                                    {{ toCurrency($log->parent_amount) }}
                                </td>

                                <!-- End-user Kickback Split Share -->
                                <td
                                    class="px-6 py-4 text-start whitespace-nowrap font-medium text-gray-600 dark:text-gray-400">
                                    {{ toCurrency($log->user_amount) }}
                                </td>

                                <!-- Created At Log Timestamp -->
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400 text-end">
                                    {{ $log->getCreateAt() }}
                                </td>
                            </tr>
                        @empty
                            <!-- Grid View Empty Placeholder State -->
                            <tr>
                                <td colspan="8" class="px-6 py-24 text-center">
                                    <div class="max-w-sm mx-auto flex flex-col items-center justify-center">
                                        <div
                                            class="p-4 rounded-full bg-gray-50 dark:bg-gray-700/50 text-gray-400 dark:text-gray-500 mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
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

            <!-- Pagination Wrapper Footnote -->
            @if ($logs->hasPages())
                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/20 border-t border-gray-100 dark:border-gray-700/60">
                    {{ $logs->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </main>
@endsection
