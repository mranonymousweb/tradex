@extends('admin.layout.master')

@section('title', trans('general.security_setting'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.security_setting')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.setting.security')}}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>
        <!-- Currency form -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.option.save') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-2 gap-5">
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.google_captcha_site_key')</label>
                                <input type="text" name="{{config('constants.option.google_captcha_site_key')}}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"

                                       @if(isset($options[config('constants.option.google_captcha_site_key')]))
                                           placeholder = "{{ Str::of($options[config('constants.option.google_captcha_site_key')] ?? '')->mask('*', 0) }}"
                                       @else
                                           placeholder="@lang('general.google_captcha_site_key')"
                                    @endif

                                >
                                @error(config('constants.option.google_captcha_site_key'))
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.google_captcha_secret_key')</label>
                                <input type="text" name="{{config('constants.option.google_captcha_secret_key')}}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"

                                       @if(isset($options[config('constants.option.google_captcha_secret_key')]))
                                           placeholder = "{{ Str::of($options[config('constants.option.google_captcha_secret_key')] ?? '')->mask('*', 0) }}"
                                       @else
                                           placeholder="@lang('general.google_captcha_secret_key')"
                                    @endif

                                >
                                @error(config('constants.option.google_captcha_secret_key'))
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.google_captcha_status')
                                    <span class="text-red-500">*</span></label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" value="{{config('constants.option.inactive')}}" name="{{config('constants.option.google_captcha_status')}}">
                                    <input type="checkbox" class="sr-only peer"
                                           name="{{config('constants.option.google_captcha_status')}}" value="{{config('constants.option.active')}}"
                                           @if(isset($options[config('constants.option.google_captcha_status')]) && $options[config('constants.option.google_captcha_status')] == config('constants.option.active'))
                                               checked @endif>
                                    <div
                                        class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-green-600 transition-colors"></div>
                                    <div
                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-full"></div>
                                </label>
                                @error(config('constants.option.limit_ip'))
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                    class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                @lang('general.save')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

@endsection

