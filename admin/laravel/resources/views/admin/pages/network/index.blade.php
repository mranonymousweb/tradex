@extends('admin.layout.master')

@section('title', trans('general.networks_management'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.networks_management')</h2>
            </div>
            <div class="mt-4 md:mt-0 flex items-center gap-3">
                <a href="{{route('admin.network.create')}}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                    @lang('general.save')
                </a>
                <a href="{{route('admin.setting.currency.management')}}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                    @lang('master.back')
                </a>
            </div>

        </div>
        @include('admin.pages.filter',['filters' => $filters,'back'=>route('admin.network.index')])
        <div class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="dark:bg-gray-800 dark:border-gray-700 w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="">
                    <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                        <th class="table-header py-4 font-medium">@lang('general.id')</th>
                        <th class="table-header py-4 font-medium">@lang('general.icon')</th>
                        <th class="table-header py-4 font-medium">@lang('general.symbol')</th>
{{--                        <th class="table-header py-4 font-medium">@lang('general.name')</th>--}}
                        <th class="table-header py-4 font-medium">@lang('general.status')</th>
                        <th class="table-header py-4 font-medium">@lang('general.operation')</th>

                    </tr>
                    </thead>
                    <tbody class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                    @forelse($networks as $network)
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$network?->id ?? ''}}</span>
                            </td>

                            <td class="whitespace-nowrap max-xl:min-w-70 items-center">
                                <img class="w-10 h-10" loading="lazy" src="{{asset($network->icon)}}" alt="{{$network->symbol}}">
                            </td>

                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$network?->symbol ?? ''}}</span>
                            </td>

{{--                            <td class="whitespace-nowrap max-xl:min-w-70">--}}
{{--                                <span>{{$network?->name ?? ''}}</span>--}}
{{--                            </td>--}}


                            <td class="whitespace-nowrap max-xl:min-w-70">
                               {!! $network->getStatus() !!}
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <a href="{{route('admin.network.edit',$network)}}" class="px-3 py-1 border rounded-lg text-sm font-medium text-white bg-primary-600 dark:bg-primary-500 hover:bg-primary-500 dark:hover:bg-primary-600 border-primary-600 dark:border-primary-500">@lang('general.setting')</a>
                            </td>

                        </tr>
                    @empty
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                            <td class="whitespace-nowrap" colspan="6">
                                <span> @lang('general.not_found')</span>
                            </td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            {{ $networks->withQueryString()->links('pagination::tailwind') }}
        </div>

    </main>
@endsection


