@extends('admin.layout.master')

@section('title', trans('setting.futures'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        @include('admin.pages.future.page-header')

        <!-- Option form -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">

                    <form action="{{ route('admin.option.save') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Future Interest Rate --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.future.interest_rate')<span class="text-red-500">*</span>
                                </label>
                                <input required type="text" name="{{ config('constants.option.future_interest_rate') }}" value="{{ old(config('constants.option.future_interest_rate'), $options[config('constants.option.future_interest_rate')] ?? '') }}" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="@lang('general.future.interest_rate')">
                                @error(config('constants.option.future_interest_rate'))
                                <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Long Position Fee --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.future.long_fee_percentage')<span class="text-red-500">*</span>
                                </label>
                                <input type="text" required
                                       name="{{ config('constants.option.future_long_position_fee') }}"
                                       value="{{ old(config('constants.option.future_long_position_fee'), $options[config('constants.option.future_long_position_fee')] ?? '') }}"
                                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.future.long_fee_percentage')">
                                @error(config('constants.option.future_long_position_fee'))
                                <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Short Position Fee --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.future.short_fee_percentage')<span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       required
                                       name="{{ config('constants.option.future_short_position_fee') }}"
                                       value="{{ old(config('constants.option.future_short_position_fee'), $options[config('constants.option.future_short_position_fee')] ?? '') }}"
                                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.future.short_fee_percentage')">
                                @error(config('constants.option.future_short_position_fee'))
                                <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Periodic Profit --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.future.periodic_profit_percentage')<span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       required
                                       name="{{ config('constants.option.future_periodic_profit') }}"
                                       value="{{ old(config('constants.option.future_periodic_profit'), $options[config('constants.option.future_periodic_profit')] ?? '') }}"
                                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.future.periodic_profit_percentage')">
                            </div>

                            {{-- First Period --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.future.first_period')<span class="text-red-500">*</span>
                                </label>
                                <input type="time"
                                       required
                                       name="{{ config('constants.option.future_first_period') }}"
                                       value="{{ old(config('constants.option.future_first_period'), $options[config('constants.option.future_first_period')] ?? '') }}"
                                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.future.first_period')">
                                @error(config('constants.option.future_first_period'))
                                <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Second Period --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.future.second_period')<span class="text-red-500">*</span>
                                </label>
                                <input type="time"
                                       required
                                       name="{{ config('constants.option.future_second_period') }}"
                                       value="{{ old(config('constants.option.future_second_period'), $options[config('constants.option.future_second_period')] ?? '') }}"
                                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.future.second_period')">
                                @error(config('constants.option.future_second_period'))
                                <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Third Period --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.future.third_period')<span class="text-red-500">*</span>
                                </label>
                                <input type="time"
                                       required
                                       name="{{ config('constants.option.future_third_period') }}"
                                       value="{{ old(config('constants.option.future_third_period'), $options[config('constants.option.future_third_period')] ?? '') }}"
                                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.future.third_period')">
                                @error(config('constants.option.future_third_period'))
                                <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <div class="pt-4">
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

        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between mt-5">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.future.fees')</h2>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.future.update.fee') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                            <div>
                                <label  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.upload_file')<span class="text-red-500">*</span></label>
                                <input required type="file" name="file"  class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="@lang('general.name')">
                                @error('file')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="flex">
                                <button type="submit" class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                    @lang('general.save')
                                </button>
                                <a href="{{ route('admin.future.download.config') }}"
                                   class="flex items-center ms-2 px-4 py-2 text-sm font-medium rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-light hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600">
                                    @lang('general.future.download_config_file')
                                </a>
                            </div>
                        </div>
                    </form>
                    <table class="w-full">
                        <thead class="">
                        <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                            <th class="table-header py-4 font-medium">@lang('general.future.leverage')</th>
                            <th class="table-header py-4 font-medium">@lang('general.future.long_percentage')</th>
                            <th class="table-header py-4 font-medium">@lang('general.future.short_percentage')</th>

                        </tr>
                        </thead>
                        <tbody class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                        @foreach($long as $key => $longValue)
                            @php
                                $leverage = (int) str_replace('leverage_', '', $key);
                                $shortValue = $short["leverage_$leverage"] ?? '-';
                            @endphp
                            <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                                <td class="whitespace-nowrap max-xl:min-w-70">{{ $leverage }}x</td>
                                <td class="whitespace-nowrap max-xl:min-w-70">{{ $longValue }}</td>
                                <td class="whitespace-nowrap max-xl:min-w-70">{{ $shortValue }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection


