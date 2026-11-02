@extends('admin.layout.master')

@section('title', trans('general.user_assets_summary'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900">
        <div class="mb-6">
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                @lang('general.user_assets_summary')
            </h2>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">@lang('general.user_assets_summary_description')</p>
        </div>

        @include('admin.pages.filter', ['filters' => $filters, 'back' => route('admin.reports.user.assets')])

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50/70 dark:bg-gray-700/40 text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="text-start px-4 py-4 font-semibold uppercase tracking-wider">@lang('general.currency')</th>
                        <th class="text-start px-6 py-4 font-semibold uppercase tracking-wider">@lang('general.general_balance')</th>
                        <th class="text-start px-6 py-4 font-semibold uppercase tracking-wider">@lang('general.future_balance')</th>
                        <th class="text-start px-6 py-4 font-semibold uppercase tracking-wider">@lang('general.blocked_balance')</th>
                        <th class="text-start px-6 py-4 font-semibold uppercase tracking-wider">@lang('general.margin_balance')</th>
                        <th class="text-start px-6 py-4 font-semibold uppercase tracking-wider">@lang('general.details')</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($currencies as $currency)
                        @php($total = $totals->get($currency->id))
                        <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <x-admin.currency :currency="$currency"/>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700 dark:text-gray-300">
                                {{ toCurrency($total->balance ?? 0,6) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700 dark:text-gray-300">
                                {{ toCurrency($total->feature_balance ?? 0,6) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700 dark:text-gray-300">
                                {{ toCurrency($total->block_balance ?? 0,6) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700 dark:text-gray-300">
                                {{ toCurrency($total->margin_balance ?? 0,6) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.reports.user.assets.detail', $currency) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600">
                                    @lang('general.details')
                                </a>
                            </td>
                        </tr>
                    @empty
                        <!-- Grid Empty State Canvas -->
                        <tr>
                            <td colspan="6" class="px-6 py-24 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center justify-center">
                                    <div class="p-4 rounded-full bg-gray-50 dark:bg-gray-700/50 text-gray-400 dark:text-gray-500 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
            <!-- Pagination Footer Area Container -->
            @if($currencies->hasPages())
                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/20 border-t border-gray-100 dark:border-gray-700/60">
                    {{ $currencies->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </main>
@endsection
