@extends('admin.layout.master')

@section('title', trans('general.currency_deposit_logs'))

@php
    // A deposit can also be held for review when its tag matches no user on a
    // shared address, which has nothing to do with AML, so the status column has
    // to appear even with the plugin switched off.
    $showValidationStatus = resolve(\App\Services\Plugins\AmlBotPlugin::class)->isActivePlugin()
        || $transactions->contains(fn ($log) => $log->isWaitingAdminReview());
@endphp

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.currency_deposit_logs')</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.excel.currencyDeposit') }}"
                   class="text-white inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                         stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 16.5V3m0 13.5 4.5-4.5M12 16.5l-4.5-4.5M3.75 19.5h16.5"/>
                    </svg>
                    <span>@lang('general.download_excel')</span>
                </a>
            </div>
        </div>

        @include('admin.pages.filter',['filters' => $filters,'back'=>route('admin.reports.currency.deposit.log')])

        <div class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="dark:bg-gray-800 dark:border-gray-700 w-full overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-600 dark:text-gray-300 text-gray-700 h-16">
                        <th class="table-header px-4 py-4 font-medium whitespace-nowrap">@lang('general.id')</th>
                        <th class="table-header px-4 py-4 font-medium whitespace-nowrap">@lang('general.user')</th>
                        <th class="table-header px-4 py-4 font-medium whitespace-nowrap">@lang('general.currency')</th>
                        <th class="table-header px-4 py-4 font-medium whitespace-nowrap">@lang('general.network')</th>
                        <th class="table-header px-4 py-4 font-medium whitespace-nowrap">@lang('general.amount')</th>
                        @if($showValidationStatus)
                            <th class="table-header px-4 py-4 font-medium whitespace-nowrap">@lang('general.deposit_validation_status')</th>
                        @endif
                        <th class="table-header px-4 py-4 font-medium whitespace-nowrap">@lang('general.is_used')</th>
                        <th class="table-header px-4 py-4 font-medium whitespace-nowrap">@lang('general.created_at')</th>
                        <th class="table-header px-4 py-4 font-medium whitespace-nowrap">@lang('general.operation')</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                    @forelse($transactions as $transaction)
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16">
                            <td class="px-4 py-3 whitespace-nowrap max-xl:min-w-20">
                                <span>{{ $transaction->id }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap max-xl:min-w-48">
                                @if($transaction->user)
                                    <a href="{{ route('admin.user.edit', $transaction->user) }}"
                                       class="text-primary-600 hover:underline">
                                        {{ $transaction->user->getFullInfo() ?? '' }}
                                    </a>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap max-xl:min-w-24">
                                <span>{{ $transaction->symbol ?? '' }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap max-xl:min-w-24">
                                <span>{{ $transaction->network ?? '' }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap max-xl:min-w-28">
                                <span>{{ toCurrency($transaction->amount) }}</span>
                            </td>
                            @if($showValidationStatus)
                                <td class="px-4 py-3 whitespace-nowrap max-xl:min-w-40">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $transaction->getValidationStatusBadgeClass() }}">
                                        {{ $transaction->getValidationStatusLabel() }}
                                    </span>
                                </td>
                            @endif
                            <td class="px-4 py-3 whitespace-nowrap max-xl:min-w-24">
                                @if($transaction->is_used)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">@lang('general.yes')</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">@lang('general.no')</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap max-xl:min-w-36">
                                <span>{{ $transaction->getCreateAt() }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap max-xl:min-w-28">
                                <a href="{{ route('admin.reports.currency.deposit.log.show', $transaction) }}"
                                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm">
                                    @lang('general.details')
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16">
                            <td class="px-4 py-3 whitespace-nowrap" colspan="{{ $showValidationStatus ? 9 : 8 }}">
                                <span>@lang('general.not_found')</span>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $transactions->links('pagination::tailwind') }}
        </div>
    </main>
@endsection
