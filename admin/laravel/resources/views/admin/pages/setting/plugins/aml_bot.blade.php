@extends('admin.layout.master')

@section('title', trans('general.plugin_aml_bot_setting'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.plugin_aml_bot_setting')</h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">@lang('general.plugin_aml_bot_description')</p>
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
                                    @lang('general.plugin_aml_bot_access_id')
                                </label>
                                <input type="text"
                                       name="{{ config('constants.plugin.plugin_aml_bot_access_id') }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       value="{{ old(config('constants.plugin.plugin_aml_bot_access_id'), $options[config('constants.plugin.plugin_aml_bot_access_id')] ?? null) }}"
                                       placeholder="@lang('general.plugin_aml_bot_access_id')"
                                >
                                @error(config('constants.plugin.plugin_aml_bot_access_id'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.plugin_aml_bot_access_key')
                                </label>
                                <input type="text"
                                       name="{{ config('constants.plugin.plugin_aml_bot_access_key') }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       @if(isset($options[config('constants.plugin.plugin_aml_bot_access_key')]))
                                           placeholder="{{ Str::of($options[config('constants.plugin.plugin_aml_bot_access_key')] ?? '')->mask('*', 0) }}"
                                       @else
                                           placeholder="@lang('general.plugin_aml_bot_access_key')"
                                    @endif
                                >
                                @error(config('constants.plugin.plugin_aml_bot_access_key'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.plugin_aml_bot_base_url')
                                </label>
                                <input type="text"
                                       name="{{ config('constants.plugin.plugin_aml_bot_base_url') }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       value="{{ old(config('constants.plugin.plugin_aml_bot_base_url'), $options[config('constants.plugin.plugin_aml_bot_base_url')] ?? 'https://extrnlapiendpoint.silencatech.com') }}"
                                       placeholder="https://extrnlapiendpoint.silencatech.com"
                                >
                                @error(config('constants.plugin.plugin_aml_bot_base_url'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.plugin_aml_bot_default_locale')
                                </label>
                                <input type="text"
                                       name="{{ config('constants.plugin.plugin_aml_bot_default_locale') }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       value="{{ old(config('constants.plugin.plugin_aml_bot_default_locale'), $options[config('constants.plugin.plugin_aml_bot_default_locale')] ?? 'en_US') }}"
                                       placeholder="en_US"
                                >
                                @error(config('constants.plugin.plugin_aml_bot_default_locale'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.plugin_aml_bot_risk_threshold')
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="number"
                                       min="0"
                                       max="100"
                                       step="0.01"
                                       name="{{ config('constants.plugin.plugin_aml_bot_risk_threshold') }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       value="{{ old(
                                            config('constants.plugin.plugin_aml_bot_risk_threshold'),
                                            $options[config('constants.plugin.plugin_aml_bot_risk_threshold')] ?? \App\Services\Plugins\AmlBotPlugin::DEFAULT_RISK_THRESHOLD
                                       ) }}"
                                       placeholder="70"
                                >
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">@lang('general.plugin_aml_bot_risk_threshold_hint')</p>
                                @error(config('constants.plugin.plugin_aml_bot_risk_threshold'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.plugin_aml_bot_proxy')
                                </label>
                                <input type="text"
                                       name="{{ config('constants.plugin.plugin_aml_bot_proxy') }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       value="{{ old(config('constants.plugin.plugin_aml_bot_proxy'), $options[config('constants.plugin.plugin_aml_bot_proxy')] ?? null) }}"
                                       placeholder="http://user:pass@1.2.3.4:8080"
                                >
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">@lang('general.plugin_aml_bot_proxy_hint')</p>
                                @error(config('constants.plugin.plugin_aml_bot_proxy'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.plugin_aml_bot_webhook_url')
                                </label>
                                <input type="text"
                                       readonly
                                       value="{{ $webhookUrl }}"
                                       class="w-full px-4 py-2 border rounded-lg bg-gray-100 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300 text-gray-600"
                                >
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">@lang('general.plugin_aml_bot_webhook_hint')</p>
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
