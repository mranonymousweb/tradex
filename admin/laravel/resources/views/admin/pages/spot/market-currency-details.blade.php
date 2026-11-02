@extends('admin.layout.master')

@section('title', trans('setting.spot'))

@section('contents')

    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-background-dark">
        <!-- Page header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-dark dark:text-light">@lang('general.market') ({{$currency->getPairs()}})</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.market.currency.list',$currency)}}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-light hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">

            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.market.currency.detail.update', $currency) }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- Currencies --}}
                            <div>
                                <label class="block text-sm font-medium mb-2">
                                    @lang('general.currencies') <span class="text-red-500">*</span>
                                </label>
                                <select name="main_currency_id" class="select2 w-full form-input">
                                    @foreach($currencies as $key => $value)
                                        <option value="{{ $key }}" @selected($key == $currency->main_currency_id)>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('main_currency_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                            </div>

                            {{-- Numeric fields --}}
                            @php
                                $numberFields = [
                                    'min_trade_amount_usdt',
                                    'increase_percent',
                                    'increase_value',
                                    'decrease_percent',
                                    'decrease_value',
                                ];
                            @endphp

                            @foreach($numberFields as $field)
                                <div>
                                    <label class="block text-sm font-medium mb-2">
                                        @lang("general.$field")
                                    </label>

                                    <input type="text"
                                           name="{{ $field }}"
                                           value="{{ old($field, toCurrency($currency->$field)) }}"
                                           placeholder="@lang("general.$field")"
                                           class="separator w-full  px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-600 dark:border-gray-600 dark:text-white">

                                    @error($field)
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach

                            {{-- Switches --}}
                            @php
                                $switches = ['purchasable', 'sellable'];
                            @endphp

                            @foreach($switches as $switch)
                                <div>
                                    <label class="block text-sm font-medium mb-2">
                                        @lang("general.$switch")
                                    </label>

                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="hidden" name="{{ $switch }}" value="0">
                                        <input type="checkbox"
                                               name="{{ $switch }}"
                                               value="1"
                                               class="sr-only peer"
                                            @checked(old($switch,$currency->$switch))>

                                        <div class="w-11 h-6 bg-gray-300 rounded-full peer-checked:bg-green-600 transition-colors"></div>
                                        <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-full"></div>
                                    </label>

                                    @error($switch)
                                    <p class="text-red-500 text-sm">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach

                        </div>

                        {{-- Submit --}}
                        <div class="mt-6">
                            <button type="submit"
                                    class="w-32 px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700">
                                @lang('general.save')
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </main>

@endsection


