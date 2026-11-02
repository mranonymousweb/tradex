@extends('admin.layout.master')

@section('title', trans('general.details'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                    @lang('general.details')
                </h2>
                <div class="mt-3">
                    <x-admin.currency :currency="$currency"/>
                </div>
            </div>
            <a href="{{ route('admin.reports.user.assets') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200">
                @lang('master.back')
            </a>
        </div>

        @include('admin.pages.filter', ['filters' => $filters, 'back' => route('admin.reports.user.assets.detail', $currency)])

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50/70 dark:bg-gray-700/40 text-gray-700 dark:text-gray-300 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="text-start px-4 py-4 font-semibold uppercase tracking-wider">@lang('general.user')</th>
                        <th class="text-start px-6 py-4 font-semibold uppercase tracking-wider">@lang('general.general_balance')</th>
                        <th class="text-start px-6 py-4 font-semibold uppercase tracking-wider">@lang('general.future_balance')</th>
                        <th class="text-start px-6 py-4 font-semibold uppercase tracking-wider">@lang('general.blocked_balance')</th>
                        <th class="text-start px-6 py-4 font-semibold uppercase tracking-wider">@lang('general.margin_balance')</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($assets as $asset)
                        <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <x-admin.user-profile :user="$asset->user"/>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700 dark:text-gray-300">
                                {{ toCurrency($asset->balance, 6) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700 dark:text-gray-300">
                                {{ toCurrency($asset->feature_balance, 6) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700 dark:text-gray-300">
                                {{ toCurrency($asset->block_balance, 6) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700 dark:text-gray-300">
                                {{ toCurrency($asset->margin_balance, 6) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-24 text-center">
                                <p class="text-xs text-gray-500 dark:text-gray-400">@lang('general.not_found')</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($assets->hasPages())
                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-700/20 border-t border-gray-100 dark:border-gray-700/60">
                    {{ $assets->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </main>
@endsection
