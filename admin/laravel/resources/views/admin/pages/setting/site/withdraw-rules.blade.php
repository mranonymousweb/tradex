@extends('admin.layout.master')

@section('title', __('general.site_setting'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.site_setting')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.setting.site.rule.index')}}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <!-- Summary of cards -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.option.save') }}" method="POST" enctype="multipart/form-data"
                          class="space-y-6">
                        @csrf

                        @foreach($ruleItems as $rule)
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">

                                {{-- Title --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        @lang('general.title')
                                    </label>
                                    <input type="text"
                                           name="{{ $rule['title'] }}"
                                           value="{{ old($rule['title'], $options[$rule['title']] ?? '') }}"
                                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                           placeholder="@lang('general.title')">
                                    @error($rule['title'])
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- URL --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        @lang('general.url')
                                    </label>
                                    <input type="text"
                                           name="{{ $rule['url'] }}"
                                           value="{{ old($rule['url'], $options[$rule['url']] ?? '') }}"
                                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                           placeholder="@lang('general.url')">
                                    @error($rule['url'])
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Description / Text --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        @lang('general.description')
                                    </label>
                                    <textarea name="{{ $rule['text'] }}"
                                              rows="7"
                                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                              placeholder="@lang('general.description')">{{ old($rule['text'], $options[$rule['text']] ?? '') }}</textarea>
                                    @error($rule['text'])
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        @endforeach

                        {{-- Submit --}}

                            <div class="pt-4">
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

