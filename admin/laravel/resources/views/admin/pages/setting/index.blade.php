@extends('admin.layout.master')

@section('title', 'Admin/Setting')

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('master.setting')</h2>
            </div>
        </div>
        <!-- Summary of cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
            @foreach($settings as $setting)

                @can($setting['permission'])
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                        <a href="{{ $setting['route'] }}" class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">
                                    {{ $setting['title'] }}
                                </p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $loop->iteration }}
                                </p>
                            </div>

                            <div class="p-3 rounded-xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281a1.125 1.125 0 0 0 .865.997l1.437.553a1.125 1.125 0 0 1 .51 1.685l-1.004.827a1.125 1.125 0 0 0 0 1.742l1.004.827a1.125 1.125 0 0 1-.51 1.685l-1.437.553a1.125 1.125 0 0 0-.865.997l-.213 1.281a1.125 1.125 0 0 1-1.11.94h-2.593a1.125 1.125 0 0 1-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.865-.997l-1.437-.553a1.125 1.125 0 0 1-.51-1.685l1.004-.827a1.125 1.125 0 0 0 0-1.742l-1.004-.827a1.125 1.125 0 0 1 .51-1.685l1.437-.553a1.125 1.125 0 0 0 .865-.997l.213-1.281Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </div>
                        </a>
                    </div>
                @endcan

            @endforeach


        </div>
    </main>
@endsection

