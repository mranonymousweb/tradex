@extends('admin.layout.master')

@section('title', trans('setting.gateway_settings'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('setting.gateway_settings')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.irt.index')}}"
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

        <!-- Grid for gateways -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Gateway 1: Zarinpal -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">@lang('setting.gateway_zarinpal')</h3>

                <form action="{{ route('admin.option.save') }}" method="post">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                @lang('setting.gate_key') <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="{{config('constants.option.gateway_zarinpal_merchant_id')}}"
                                   value="{{old(config('constants.option.gateway_zarinpal_merchant_id'),optional($options)[config('constants.option.gateway_zarinpal_merchant_id')])}}"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error(config('constants.option.gateway_zarinpal_merchant_id'))
                            <span class="text-red-500">{{$message}}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.status')</label>
                            <label class="w-11 relative flex items-center">
                                <input type="hidden" name="{{ config('constants.option.gateway_zarinpal_status') }}" value="{{ config('constants.option.inactive') }}">
                                <input type="checkbox" class="hidden peer"
                                       value="{{ config('constants.option.active') }}"
                                       name="{{ config('constants.option.gateway_zarinpal_status') }}"
                                    {{ optional($options)[config('constants.option.gateway_zarinpal_status')] == config('constants.option.active') ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-green-600 transition-colors"></div>
                                <div
                                    class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-full"></div>
                            </label>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full flex justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm">
                                @lang('general.save')
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Gateway 2: Pay Ping -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">@lang('setting.gateway_payPing')</h3>

                <form action="{{ route('admin.option.save') }}" method="post">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                @lang('setting.gate_key') <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="{{config('constants.option.gateway_pay_ping_api_key')}}"
                                   value="{{old(config('constants.option.gateway_pay_ping_api_key'),optional($options)[config('constants.option.gateway_pay_ping_api_key')] ?? null)}}"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error(config('constants.option.gateway_pay_ping_api_key'))
                            <span class="text-red-500">{{$message}}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.status')</label>
                            <label class="w-11 relative flex items-center">
                                <input type="hidden" name="{{ config('constants.option.gateway_pay_ping_status') }}" value="{{ config('constants.option.inactive') }}">
                                <input type="checkbox" class="hidden peer"
                                       value="{{ config('constants.option.active') }}"
                                       name="{{ config('constants.option.gateway_pay_ping_status') }}"
                                    {{ optional($options)[config('constants.option.gateway_pay_ping_status')] == config('constants.option.active') ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-green-600 transition-colors"></div>
                                <div
                                    class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-full"></div>
                            </label>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full flex justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm">
                                @lang('general.save')
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Gateway 2: Direct Pay -->

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">@lang('general.direct_pay_gateway')</h3>

                <form action="{{ route('admin.option.save') }}" method="post">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                @lang('setting.gate_key') <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="{{config('constants.option.gateway_direct_pay_api_key')}}"
                                   value="{{old(config('constants.option.gateway_direct_pay_api_key'),optional($options)[config('constants.option.gateway_direct_pay_api_key')] ?? null)}}"
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error(config('constants.option.gateway_direct_pay_api_key'))
                            <span class="text-red-500">{{$message}}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.status')</label>
                            <label class="w-11 relative flex items-center">
                                <input type="hidden" name="{{ config('constants.option.gateway_direct_pay_status') }}" value="{{ config('constants.option.inactive') }}">
                                <input type="checkbox" class="hidden peer"
                                       value="{{ config('constants.option.active') }}"
                                       name="{{ config('constants.option.gateway_direct_pay_status') }}"
                                    {{ optional($options)[config('constants.option.gateway_direct_pay_status')] == config('constants.option.active') ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-green-600 transition-colors"></div>
                                <div
                                    class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-full"></div>
                            </label>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full flex justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm">
                                @lang('general.save')
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </main>
@endsection
