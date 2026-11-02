@extends('admin.layout.master')

@section('title', trans('general.marketing_logs'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <!-- Header Page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.marketing_logs')</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.excel.marketingParents') }}"
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
        @include('admin.pages.filter', ['filters' => $filters, 'back' => route('admin.reports.marketing.parents')])

        <!-- Main Data Card Container -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden flex flex-col min-h-[600px]">

            <!-- Table Wrapper -->
            <div class="flex-1 overflow-x-auto">
                <table class="w-full text-sm text-gray-500 dark:text-gray-400 border-collapse">
                    <thead class="bg-gray-50/70 dark:bg-gray-700/40 text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th scope="col" class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">@lang('general.id')</th>
                        <th scope="col" class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">@lang('general.user')</th>
                        <th scope="col" class="text-start px-4 py-4 font-semibold  uppercase tracking-wider">@lang('general.marketing_code_count')</th>
                        <th scope="col" class="text-start px-4 py-4 font-semibold  uppercase tracking-wider">@lang('general.marketing_user_count')</th>
                        <th scope="col" class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">@lang('general.total_profit') (USDT)</th>
                        <th scope="col" class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">@lang('general.parent_amount') (USDT)</th>
                        <th scope="col" class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">@lang('general.users_amount') (USDT)</th>
                        <th scope="col" class="text-start px-6 py-4 font-semibold  uppercase tracking-wider">@lang('general.created_at')</th>
                        <th scope="col" class="text-end px-6 py-4 font-semibold    uppercase tracking-wider">@lang('general.operation')</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-700/30 transition-colors duration-150">
                            <!-- ID -->
                            <td class="px-6 py-4 whitespace-nowrap font-mono  text-gray-600 dark:text-gray-400">
                                {{ $user->id }}
                            </td>

                            <!-- User -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-admin.user-profile :user="$user"/>
                            </td>

                            <!-- Code Count -->
                            <td class="px-4 py-4 text-start whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full  font-semibold bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">
                                        {{ $user?->marketingCodes?->count() ?? 0 }}
                                    </span>
                            </td>

                            <!-- User Count -->
                            <td class="px-4 py-4 text-start whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full  font-semibold bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300">
                                        {{ $user?->users?->count() ?? 0 }}
                                    </span>
                            </td>

                            <!-- Total Profit -->
                            <td class="px-6 py-4 text-start whitespace-nowrap font-bold text-gray-900 dark:text-white">
                                {{ toCurrency($user->total_amount) }}
                            </td>

                            <!-- Parent Amount -->
                            <td class="px-6 py-4 text-start whitespace-nowrap font-semibold text-emerald-600 dark:text-emerald-400">
                                {{ toCurrency($user->total_parent_amount) }}
                            <!-- Users Amount -->
                            <td class="px-6 py-4 text-start whitespace-nowrap font-medium text-gray-700 dark:text-gray-300">
                                {{ toCurrency($user->total_user_amount) }}
                            </td>

                            <!-- Created At -->
                            <td class="px-6 py-4 text-start whitespace-nowrap text-gray-500 dark:text-gray-400">
                                {{ $user->getCreateAt() }}
                            </td>

                            <!-- Operation Action -->
                            <td class="px-6 py-4 text-end whitespace-nowrap">
                                <a href="{{ route('admin.reports.marketing.users', ['parent_id' => $user->id]) }}"
                                   class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-all duration-150 focus:outline-none focus:ring-4 focus:ring-blue-500/10">
                                    @lang('general.details')
                                </a>
                            </td>
                        </tr>
                    @empty
                        <!-- Empty State Visual Block -->
                        <tr>
                            <td colspan="9" class="px-6 py-24 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center justify-center">
                                    <div class="p-4 rounded-full bg-gray-50 dark:bg-gray-700/50 text-gray-400 dark:text-gray-500 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.003 9.003 0 1020.945 13H11V3.055z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
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

            <!-- Pagination Section Container -->
            @if($users->hasPages())
                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/20 border-t border-gray-100 dark:border-gray-700/60">
                    {{ $users->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </main>
@endsection
