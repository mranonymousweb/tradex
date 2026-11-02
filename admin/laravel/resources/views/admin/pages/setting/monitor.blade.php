@extends('admin.layout.master')

@section('title', 'Admin/Setting')

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('master.setting')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.setting.index')}}"
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
        <!-- Summary of cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">

            @can('admin.monitor.price.socket')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <a href="{{route('admin.monitor.price.socket')}}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('general.monitor_socket_prices')</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">1</p>
                        </div>
                        <div
                            class="p-3 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281a1.125 1.125 0 0 0 .865.997l1.437.553a1.125 1.125 0 0 1 .51 1.685l-1.004.827a1.125 1.125 0 0 0 0 1.742l1.004.827a1.125 1.125 0 0 1-.51 1.685l-1.437.553a1.125 1.125 0 0 0-.865.997l-.213 1.281a1.125 1.125 0 0 1-1.11.94h-2.593a1.125 1.125 0 0 1-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.865-.997l-1.437-.553a1.125 1.125 0 0 1-.51-1.685l1.004-.827a1.125 1.125 0 0 0 0-1.742l-1.004-.827a1.125 1.125 0 0 1 .51-1.685l1.437-.553a1.125 1.125 0 0 0 .865-.997l.213-1.281Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                            </svg>
                        </div>
                    </a>
                </div>
            @endcan

            @can('admin.monitor.future.order.book.socket')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <a href="{{route('admin.monitor.future.order.book.socket')}}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('general.monitor_socket_futures_orders')</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">2</p>
                        </div>
                        <div
                            class="p-3 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281a1.125 1.125 0 0 0 .865.997l1.437.553a1.125 1.125 0 0 1 .51 1.685l-1.004.827a1.125 1.125 0 0 0 0 1.742l1.004.827a1.125 1.125 0 0 1-.51 1.685l-1.437.553a1.125 1.125 0 0 0-.865.997l-.213 1.281a1.125 1.125 0 0 1-1.11.94h-2.593a1.125 1.125 0 0 1-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.865-.997l-1.437-.553a1.125 1.125 0 0 1-.51-1.685l1.004-.827a1.125 1.125 0 0 0 0-1.742l-1.004-.827a1.125 1.125 0 0 1 .51-1.685l1.437-.553a1.125 1.125 0 0 0 .865-.997l.213-1.281Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                            </svg>
                        </div>
                    </a>
                </div>
            @endcan

            @can('admin.monitor.future.open.order.socket')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <a href="{{route('admin.monitor.future.open.order.socket')}}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('general.monitor_socket_futures_open_orders')</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">3</p>
                        </div>
                        <div
                            class="p-3 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281a1.125 1.125 0 0 0 .865.997l1.437.553a1.125 1.125 0 0 1 .51 1.685l-1.004.827a1.125 1.125 0 0 0 0 1.742l1.004.827a1.125 1.125 0 0 1-.51 1.685l-1.437.553a1.125 1.125 0 0 0-.865.997l-.213 1.281a1.125 1.125 0 0 1-1.11.94h-2.593a1.125 1.125 0 0 1-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.865-.997l-1.437-.553a1.125 1.125 0 0 1-.51-1.685l1.004-.827a1.125 1.125 0 0 0 0-1.742l-1.004-.827a1.125 1.125 0 0 1 .51-1.685l1.437-.553a1.125 1.125 0 0 0 .865-.997l.213-1.281Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                            </svg>
                        </div>
                    </a>
                </div>
            @endcan

            @can('admin.monitor.system.log')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <a href="{{route('admin.monitor.system.log')}}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('general.monitor_system_logs')</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">4</p>
                        </div>
                        <div
                            class="p-3 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281a1.125 1.125 0 0 0 .865.997l1.437.553a1.125 1.125 0 0 1 .51 1.685l-1.004.827a1.125 1.125 0 0 0 0 1.742l1.004.827a1.125 1.125 0 0 1-.51 1.685l-1.437.553a1.125 1.125 0 0 0-.865.997l-.213 1.281a1.125 1.125 0 0 1-1.11.94h-2.593a1.125 1.125 0 0 1-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.865-.997l-1.437-.553a1.125 1.125 0 0 1-.51-1.685l1.004-.827a1.125 1.125 0 0 0 0-1.742l-1.004-.827a1.125 1.125 0 0 1 .51-1.685l1.437-.553a1.125 1.125 0 0 0 .865-.997l.213-1.281Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                            </svg>
                        </div>
                    </a>
                </div>
            @endcan

        </div>

    </main>
@endsection

