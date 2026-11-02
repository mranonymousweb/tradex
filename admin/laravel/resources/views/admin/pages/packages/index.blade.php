@extends('admin.layout.master')

@section('title', trans('master.packages'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.package_management')</h2>
            </div>

        </div>
        <div
            class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="dark:bg-gray-800 dark:border-gray-700 w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="">
                    <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                        <th class="table-header py-4 font-medium">@lang('general.id')</th>
                        <th class="table-header py-4 font-medium">@lang('general.name')</th>
                        <th class="table-header py-4 font-medium">@lang('general.status')</th>
                        <th class="table-header py-4 font-medium">@lang('general.users_count')</th>
                        <th class="table-header py-4 font-medium">@lang('general.operation')</th>

                    </tr>
                    </thead>
                    <tbody
                        class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                    @forelse($packages as $package)
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$package?->id ?? ''}}</span>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$package?->name ?? ''}}</span>
                            </td>


                            <td class="whitespace-nowrap max-xl:min-w-70">
                                {!! $package->getStatus() !!}
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                               {{$package->users->count()}}
                            </td>
                            @can('admin.packages.edit')
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <a href="{{route('admin.packages.edit',$package)}}"
                                   class="px-3 py-1 border rounded-lg text-sm font-medium text-white bg-primary-600 dark:bg-primary-500 hover:bg-primary-500 dark:hover:bg-primary-600 border-primary-600 dark:border-primary-500">@lang('general.setting')</a>
                            </td>
                            @endcan

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
        </div>

    </main>
@endsection


