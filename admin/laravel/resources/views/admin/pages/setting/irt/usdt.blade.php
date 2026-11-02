@extends('admin.layout.master')

@section('title', trans('setting.usd_price_setting'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('setting.usd_price_setting')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.irt.index') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                            clip-rule="evenodd" />
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="mb-6">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    @lang('setting.usdt_irt_live_origin_price')
                </label>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    {{ filled($usdtIrtPrice->origin_last ?? null) ? toCurrency($usdtIrtPrice->origin_last) : '-' }}
                </p>
                {{-- <input type="number" readonly step="any"
                    value="{{ filled($usdtIrtPrice->origin_last ?? null) ? toCurrency($usdtIrtPrice->origin_last) : '' }}"
                    class="w-full max-w-md px-4 py-2 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white cursor-default">
             --}}
            </div>
        </div>

        <!-- Admin form -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">

                    <form action="{{ route('admin.option.save') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('setting.price_type')
                                    <span class="text-red-500">*</span></label>
                                <select id="price_type" name="{{ config('constants.option.usdt_price_type') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @foreach ($type as $key => $value)
                                        <option @selected(old(config('constants.option.usdt_price_type'), optional($options)[config('constants.option.usdt_price_type')] ?? null) == $key) value="{{ $key }}">{{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error(config('constants.option.usdt_price_type'))
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.price_data')
                                    <span class="text-red-500">*</span></label>
                                <select name="{{ config('constants.option.usdt_price_data') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @foreach ($priceType as $key => $value)
                                        <option @selected(old(config('constants.option.usdt_price_data'), optional($options)[config('constants.option.usdt_price_data')] ?? null) == $key) value="{{ $key }}">
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error(config('constants.option.usdt_price_data'))
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.usdt_price_supplier')
                                    <span class="text-red-500">*</span></label>
                                <select name="{{ config('constants.option.usdt_price_supplier') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @foreach ($supplierTypes as $key => $value)
                                        <option @selected(old(config('constants.option.usdt_price_supplier'), optional($options)[config('constants.option.usdt_price_supplier')] ?? null) == $key) value="{{ $key }}">
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                                @error(config('constants.option.usdt_price_supplier'))
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div id="manual_input" class="hidden">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('setting.manual_price')
                                    <span class="text-red-500">*</span></label>
                                <input type="number" name="{{ config('constants.option.usdt_manual_price') }}"
                                    value="{{ old(config('constants.option.usdt_manual_price'), optional($options)[config('constants.option.usdt_manual_price')]) }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="@lang('setting.manual_price')">
                                @error(config('constants.option.usdt_manual_price'))
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>


                            <div id="percentage_input" class="hidden">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 hide">@lang('setting.price_percentage')
                                    <span class="text-red-500">*</span></label>
                                <input type="number" min="-100" max="100" step="0.01"
                                    name="{{ config('constants.option.usdt_price_change_percentage') }}"
                                    value="{{ old(config('constants.option.usdt_price_change_percentage'), optional($options)[config('constants.option.usdt_price_change_percentage')]) }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="@lang('setting.price_percentage')">
                                @error(config('constants.option.usdt_price_change_percentage'))
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('setting.usdt_change_buy_price_base_irt')</label>
                                <input type="number" step="any"
                                    name="{{ config('constants.option.usdt_change_buy_price_base_irt') }}"
                                    value="{{ old(config('constants.option.usdt_change_buy_price_base_irt'), optional($options)[config('constants.option.usdt_change_buy_price_base_irt')] ?? '') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="@lang('setting.usdt_change_buy_price_base_irt_placeholder')">
                                @error(config('constants.option.usdt_change_buy_price_base_irt'))
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    {{ filled($usdtIrtPrice->buy_last ?? null) ? toCurrency($usdtIrtPrice->buy_last) : '-' }}
                                </p>
                            </div>

                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('setting.usdt_change_sell_price_base_irt')</label>
                                <input type="number" step="any"
                                    name="{{ config('constants.option.usdt_change_sell_price_base_irt') }}"
                                    value="{{ old(config('constants.option.usdt_change_sell_price_base_irt'), optional($options)[config('constants.option.usdt_change_sell_price_base_irt')] ?? '') }}"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="@lang('setting.usdt_change_sell_price_base_irt_placeholder')">
                                @error(config('constants.option.usdt_change_sell_price_base_irt'))
                                    <span class="text-red-500">{{ $message }}</span>
                                @enderror
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                    {{ filled($usdtIrtPrice->last ?? null) ? toCurrency($usdtIrtPrice->last) : '-' }}
                                </p>
                            </div>


                            <div class="pt-4 col-span-full">
                                <button type="submit"
                                    class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                    @lang('general.save')
                                </button>
                            </div>


                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        // var prices = document.querySelectorAll('.priceInput');
        // prices.forEach((price) => {
        //     currencyIMask(price)
        // })

        const priceType = document.getElementById('price_type');
        const percentageInput = document.getElementById('percentage_input')
        const manualInput = document.getElementById('manual_input')
        changeElements(priceType)
        priceType.addEventListener("change", function(event) {
            console.log(event.currentTarget.value)
            changeElements(event.currentTarget);
        });

        function changeElements(element) {
            if (element.value == "{{ config('constants.option.usdt_manual_type') }}") {
                percentageInput.classList.add('hidden')
                manualInput.classList.remove('hidden')
            } else if (element.value == "{{ config('constants.option.usdt_percentage_type') }}") {
                percentageInput.classList.remove('hidden')
                manualInput.classList.add('hidden')
            } else {
                percentageInput.classList.add('hidden')
                manualInput.classList.add('hidden')
            }
        }
    </script>
@endsection
