@extends('admin.layout.master')

@section('title', trans('general.plugin_id_analyzer_setting'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.plugin_id_analyzer_setting')</h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">@lang('general.plugin_id_analyzer_description')</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.setting.plugin.index') }}"
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

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <div class="lg:col-span-1 space-y-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.option.save') }}" method="post">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.plugin_id_analyzer_api_key')
                                </label>
                                <input type="text"
                                       name="{{ config('constants.plugin.plugin_id_analyzer_api_key') }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       @if(isset($options[config('constants.plugin.plugin_id_analyzer_api_key')]))
                                           placeholder="{{ Str::of($options[config('constants.plugin.plugin_id_analyzer_api_key')] ?? '')->mask('*', 0) }}"
                                       @else
                                           placeholder="@lang('general.plugin_id_analyzer_api_key')"
                                    @endif
                                >
                                @error(config('constants.plugin.plugin_id_analyzer_api_key'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.plugin_id_analyzer_profile_id')
                                </label>
                                <input type="text"
                                       name="{{ config('constants.plugin.plugin_id_analyzer_profile_id') }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       value="{{ old(config('constants.plugin.plugin_id_analyzer_profile_id'), $options[config('constants.plugin.plugin_id_analyzer_profile_id')] ?? null) }}"
                                       placeholder="@lang('general.plugin_id_analyzer_profile_id')"
                                >
                                @error(config('constants.plugin.plugin_id_analyzer_profile_id'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.plugin_id_analyzer_region')
                                </label>
                                <select name="{{ config('constants.plugin.plugin_id_analyzer_region') }}"
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @php
                                        $region = old(
                                            config('constants.plugin.plugin_id_analyzer_region'),
                                            $options[config('constants.plugin.plugin_id_analyzer_region')] ?? 'eu'
                                        );
                                    @endphp
                                    <option value="eu" @selected($region === 'eu')>EU (api2-eu.idanalyzer.com)</option>
                                    <option value="us" @selected($region === 'us')>US (api2.idanalyzer.com)</option>
                                </select>
                                @error(config('constants.plugin.plugin_id_analyzer_region'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.plugin_id_analyzer_webhook_url')
                                </label>
                                <input type="text"
                                       readonly
                                       value="{{ $webhookUrl }}"
                                       class="w-full px-4 py-2 border rounded-lg bg-gray-100 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300 text-gray-600"
                                >
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">@lang('general.plugin_id_analyzer_webhook_hint')</p>
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
