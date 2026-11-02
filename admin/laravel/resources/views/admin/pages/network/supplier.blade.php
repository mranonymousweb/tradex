@extends('admin.layout.master')

@section('title', trans('general.supplier_networks'))

@section('contents')
    @php
        $badgeClass = 'inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400';

        $booleanFields = [
            'is_withdraw_able' => __('general.withdraw'),
            'is_deposit_able' => __('general.deposit'),
            'has_tag' => __('general.has_tag'),
        ];
    @endphp

    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900 space-y-6">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200 dark:border-gray-800">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    @lang('general.supplier_networks')
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @lang('general.manage_and_view_your_connected_supplier_networks')
                </p>
            </div>

            <a href="{{ route('admin.setting.currency.management') }}"
               class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-gray-300 bg-white text-gray-700 shadow-sm hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 mr-2"
                     fill="currentColor"
                     viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 111.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                          clip-rule="evenodd"/>
                </svg>

                @lang('master.back')
            </a>
        </div>

        @include('admin.pages.filter',[
            'filters'=>$filters,
            'back'=>route('admin.network.provider.supplier')
        ])

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full whitespace-nowrap text-left">

                    <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/40 text-xs uppercase tracking-wider font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">

                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">@lang('general.supplier')</th>
                        <th class="px-6 py-3">@lang('general.network')</th>
                        <th class="px-6 py-3">@lang('general.symbol')</th>

                        @foreach($booleanFields as $title)
                            <th class="px-6 py-3">{{ $title }}</th>
                        @endforeach

                        <th class="px-6 py-3">@lang('general.status')</th>
                        <th class="px-6 py-3">@lang('general.operation')</th>

                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-300">

                    @forelse($networks as $supplierItem)

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/20 transition">

                            <td class="px-6 py-4 font-mono text-xs text-gray-400">
                                #{{ $supplierItem->id }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $supplierItem->supplier?->name ?? '—' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $supplierItem->network?->name ?? '—' }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="{{ $badgeClass }}">
                                    {{ $supplierItem->symbol }}
                                </span>
                            </td>

                            @foreach(array_keys($booleanFields) as $field)
                                <td class="px-6 py-4">
                                    <span class="{{ $badgeClass }}">
                                        @if(data_get($supplierItem,$field) )
                                            <span>@lang('general.yes') </span>
                                        @else
                                            <span>@lang('general.no')</span>
                                        @endif
                                    </span>
                                </td>
                            @endforeach

                            <td class="px-6 py-4">
                                {!! $supplierItem->getStatus() !!}
                            </td>

                            <td class="px-6 py-4">
                                <a href="{{ route('admin.network.provider.setting',$supplierItem) }}"
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg bg-primary-600 hover:bg-primary-500 text-white text-sm font-medium">
                                    @lang('general.setting')
                                </a>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="{{ 6 + count($booleanFields) }}"
                                class="px-6 py-12 text-center">

                                <div class="flex flex-col items-center gap-2">

                                    <svg class="w-8 h-8 text-gray-400"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="1.5"
                                              d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>

                                    <p class="text-gray-500 dark:text-gray-400 font-medium">
                                        @lang('general.no_records_found')
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            @if($networks->hasPages())
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                    {{ $networks->links() }}
                </div>
            @endif

        </div>

    </main>
@endsection
