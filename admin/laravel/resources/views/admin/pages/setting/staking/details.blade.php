@extends('admin.layout.master')

@section('title', trans('general.staking'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.staking')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.setting.staking.index')}}"
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
        <!-- Currency form -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.setting.staking.update',$staking) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-2 gap-5">

                            {{-- Currency --}}
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.currency')
                                </label>
                                <select  name="currency_id" class="select2 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">@lang('general.select')</option>
                                    @foreach($currencies as $symbol => $id)
                                        <option value="{{ $id }}" {{ old('currency_id',$staking->currency_id) == $id ? 'selected' : '' }}>
                                            {{ $symbol }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Minimum Amount --}}
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.min_amount')
                                </label>
                                <input type="text" step="0.00000001" name="min_amount"
                                       value="{{ old('min_amount',toCurrency($staking->min_amount)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.min_amount')">
                                @error('min_amount')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            {{-- Maximum Amount --}}
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.max_amount_staking')
                                </label>
                                <input type="text" step="0.00000001" name="max_amount"
                                       value="{{ old('max_amount',toCurrency($staking->max_amount)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.max_amount_staking')">
                                @error('max_amount')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Marketing Profit --}}
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.marketing_profit')
                                </label>
                                <input type="number"  name="marketing"
                                       value="{{ old('marketing',toCurrency($staking->marketing)) }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.marketing_profit')">
                                @error('marketing')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                            {{-- Minimum Days --}}
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.min_day')
                                </label>
                                <input type="text" name="min_day"
                                       value="{{ old('min_day',toCurrency($staking->min_day)) }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.min_day')">
                                @error('min_day')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Daily Profit Percent --}}
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.daily_profit_percent')
                                </label>
                                <input type="text" step="0.0001" name="daily_profit_percent"
                                       value="{{ old('daily_profit_percent',toCurrency($staking->daily_profit_percent)) }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.daily_profit_percent')">
                                @error('daily_profit_percent')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            {{-- Status --}}
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.status')
                                </label>
                                <select name="status"
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{old('status',$staking->status) == $key ? 'selected' : ''}}>
                                            {!! $label !!}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Accepted by Admin --}}
                            <div class="col-span-2 md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.accepted_by_admin')
                                </label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="accepted_by_admin" value="0">
                                    <input type="checkbox" class="sr-only peer"
                                           name="accepted_by_admin" value="1"
                                           @if(old('accepted_by_admin',$staking->accepted_by_admin)) checked @endif>
                                    <div
                                        class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-green-600 transition-colors"></div>
                                    <div
                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-full"></div>
                                </label>
                                @error('accepted_by_admin')
                                <span class="text-danger">{{ $message }}</span>
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

