@extends('admin.layout.master')

@section('title', trans('general.supplier_log'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900/50">

        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex mb-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <span>@lang('general.reports')</span>
                    <span class="mx-2">/</span>
                    <span class="text-primary-600 dark:text-primary-400">@lang('general.supplier_log')</span>
                </nav>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    @lang('general.supplier_log')
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.excel.supplierLog') }}"
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
        <div class="mb-6">
            @include('admin.pages.filter', [
                'filters' => $filters,
                'back' => route('admin.reports.supplier.log'),
            ])
        </div>

        <!-- Data Table Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-start">
                                @lang('general.id')</th>
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-start">
                                @lang('general.supplier')</th>
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-start">
                                @lang('general.section')</th>
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-start">
                                @lang('general.amount')</th>
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-start">
                                @lang('general.total')</th>
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-start">
                                @lang('general.status')</th>
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-start">
                                @lang('general.created_at')</th>
                            <th
                                class="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-end">
                                @lang('general.description')</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-start">
                                    <span
                                        class="text-start font-mono text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 px-2 py-1 rounded">
                                        #{{ $log->id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-start">
                                    {{ $log->supplier }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-start">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white text-start">
                                        {{ $log->section }}</div>
                                    <div class="text-xs text-gray-500 text-start">{{ $log->pairs }}</div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-start font-medium text-gray-900 dark:text-white ">
                                    {{ toCurrency($log->amount) }} <span
                                        class="text-xs text-gray-500">{{ $log->base_symbol }}</span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-start font-medium text-gray-900 dark:text-white ">
                                    {{ toCurrency($log->total) }} <span
                                        class="text-xs text-gray-500">{{ $log->main_symbol }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-start">
                                    {!! $log->getStatus() !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-start">
                                    {{ $log->getCreateAt() }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 min-w-[100px] text-end">
                                    <button type="button" data-extra="{{$log->description}}"
                                            class="extra-data w-32  px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                        @lang('general.see')
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                        <span class="text-gray-500 font-medium">@lang('general.not_found')</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination Area -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                {{ $logs->links('pagination::tailwind') }}
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        document.querySelectorAll('.extra-data').forEach((button) => {
            button.addEventListener('click', (event) => {
                mboxNotice(event.currentTarget.dataset.extra,'info','{{__('general.description')}}')
            })
        })
    </script>
@endsection
