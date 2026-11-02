@extends('admin.layout.master')

@section('title', 'Admin/Setting')

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-dark dark:text-light">@lang('general.email_setting')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.setting.index')}}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-light hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>
        <!-- Summary of cards -->

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">

            @can('admin.setting.email.setting')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <a href="{{route('admin.setting.email.setting')}}" class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('setting.base_setting')</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">1</p>
                        </div>
                        <div class="p-3 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281a1.125 1.125 0 0 0 .865.997l1.437.553a1.125 1.125 0 0 1 .51 1.685l-1.004.827a1.125 1.125 0 0 0 0 1.742l1.004.827a1.125 1.125 0 0 1-.51 1.685l-1.437.553a1.125 1.125 0 0 0-.865.997l-.213 1.281a1.125 1.125 0 0 1-1.11.94h-2.593a1.125 1.125 0 0 1-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.865-.997l-1.437-.553a1.125 1.125 0 0 1-.51-1.685l1.004-.827a1.125 1.125 0 0 0 0-1.742l-1.004-.827a1.125 1.125 0 0 1 .51-1.685l1.437-.553a1.125 1.125 0 0 0 .865-.997l.213-1.281Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                            </svg>
                        </div>
                    </a>
                </div>
            @endcan

            @can('admin.setting.email.setting')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <a href="{{route('admin.setting.email.user.pattern')}}" class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('setting.user_pattern_setting')</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">2</p>
                        </div>
                        <div class="p-3 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281a1.125 1.125 0 0 0 .865.997l1.437.553a1.125 1.125 0 0 1 .51 1.685l-1.004.827a1.125 1.125 0 0 0 0 1.742l1.004.827a1.125 1.125 0 0 1-.51 1.685l-1.437.553a1.125 1.125 0 0 0-.865.997l-.213 1.281a1.125 1.125 0 0 1-1.11.94h-2.593a1.125 1.125 0 0 1-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.865-.997l-1.437-.553a1.125 1.125 0 0 1-.51-1.685l1.004-.827a1.125 1.125 0 0 0 0-1.742l-1.004-.827a1.125 1.125 0 0 1 .51-1.685l1.437-.553a1.125 1.125 0 0 0 .865-.997l.213-1.281Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                            </svg>
                        </div>
                    </a>
                </div>
            @endcan

            @can('admin.setting.email.admin.pattern')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <a href="{{route('admin.setting.email.admin.pattern')}}" class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('setting.admin_pattern_setting')</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">3</p>
                        </div>
                        <div class="p-3 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281a1.125 1.125 0 0 0 .865.997l1.437.553a1.125 1.125 0 0 1 .51 1.685l-1.004.827a1.125 1.125 0 0 0 0 1.742l1.004.827a1.125 1.125 0 0 1-.51 1.685l-1.437.553a1.125 1.125 0 0 0-.865.997l-.213 1.281a1.125 1.125 0 0 1-1.11.94h-2.593a1.125 1.125 0 0 1-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.865-.997l-1.437-.553a1.125 1.125 0 0 1-.51-1.685l1.004-.827a1.125 1.125 0 0 0 0-1.742l-1.004-.827a1.125 1.125 0 0 1 .51-1.685l1.437-.553a1.125 1.125 0 0 0 .865-.997l.213-1.281Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                            </svg>
                        </div>
                    </a>
                </div>
            @endcan

            @can('admin.setting.email.index')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.setting.email.logs') }}" class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('general.notification_email_logs')</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">4</p>
                        </div>
                        <div class="p-3 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281a1.125 1.125 0 0 0 .865.997l1.437.553a1.125 1.125 0 0 1 .51 1.685l-1.004.827a1.125 1.125 0 0 0 0 1.742l1.004.827a1.125 1.125 0 0 1-.51 1.685l-1.437.553a1.125 1.125 0 0 0-.865.997l-.213 1.281a1.125 1.125 0 0 1-1.11.94h-2.593a1.125 1.125 0 0 1-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.865-.997l-1.437-.553a1.125 1.125 0 0 1-.51-1.685l1.004-.827a1.125 1.125 0 0 0 0-1.742l-1.004-.827a1.125 1.125 0 0 1 .51-1.685l1.437-.553a1.125 1.125 0 0 0 .865-.997l.213-1.281Z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                            </svg>
                        </div>
                    </a>
                </div>
            @endcan

        </div>
    </main>
@endsection

