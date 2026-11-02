@extends('admin.layout.master')

@section('title', trans('master.roles'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('master.roles')</h2>
            </div>

                <div class="mt-4 md:mt-0">
                    @can('admin.roles.store')
                    <a href="{{route('admin.roles.create')}}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                        @lang('general.save')
                    </a>
                    @endcan
                </div>

        </div>
        <div
            class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="dark:bg-gray-800 dark:border-gray-700 w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="">
                    <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                        <th class="table-header py-4 font-medium">@lang('general.id')</th>
                        <th class="table-header py-4 font-medium">@lang('master.role')</th>
                        @can('admin.roles.update')
                        <th class="table-header py-4 font-medium">@lang('general.operation')</th>
                        @endcan
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($roles as $role)
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                            <td class="whitespace-nowrap max-xl:min-w-70"> {{ $role->id }}</td>
                            <td class="whitespace-nowrap max-xl:min-w-70">{{ $role->name }}</td>
                            @can('admin.roles.update')
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <a href="{{route('admin.roles.edit',$role)}}" class="px-3 py-1 border rounded-lg text-sm font-medium text-white bg-primary-600 dark:bg-primary-500 hover:bg-primary-500 dark:hover:bg-primary-600 border-primary-600 dark:border-primary-500">@lang('general.edit')</a>
{{--                                <button onclick="mboxDelete('{{route('admin.admin.roles.destroy',$role)}}')"   class="px-3 py-1 border rounded-lg text-sm font-medium text-white bg-danger dark:bg-danger hover:bg-danger dark:hover:bg-danger border-danger dark:border-danger">@lang('general.delete')</button>--}}
                            </td>
                            @endcan
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            {{ $roles->withQueryString()->links('pagination::tailwind') }}
        </div>

    </main>
@endsection


