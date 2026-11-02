@extends('admin.layout.master')

@section('title', trans('general.converted_inventory'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.converted_inventory')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.setting.index')}}"
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

        <!-- Admin form -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            {{--            step 1 form--}}
            <div class="lg:col-span-1 space-y-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <form class="col-span-1" action="{{ route('admin.option.save') }}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="">
                            <div class="grid grid-cols-4 gap-5 mt-8">
                                <div class="col-span-2 md:col-span-1">
                                    <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.max_converted_inventory')
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="{{config('constants.option.min_converted_inventory')}}"
                                           required
                                           oninvalid="this.setCustomValidity('@lang('general.min_converted_inventory_required')')"
                                           oninput="setCustomValidity('')"
                                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                           value="{{old(config('constants.option.min_converted_inventory'),optional($options)[config('constants.option.min_converted_inventory')])}}"
                                    >
                                    @error(config('constants.option.min_converted_inventory'))
                                    <span class="text-red-500">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="col-span-2 md:col-span-1">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.max_request_converted_inventory')
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="{{config('constants.option.max_request_converted_inventory')}}"
                                           required
                                           oninvalid="this.setCustomValidity('@lang('general.max_request_converted_inventory_requried')')"
                                           oninput="setCustomValidity('')"
                                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                           value="{{old(config('constants.option.max_request_converted_inventory'),optional($options)[config('constants.option.max_request_converted_inventory')])}}"
                                    >
                                    @error(config('constants.option.max_request_converted_inventory'))
                                    <span class="text-red-500">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="col-span-2 md:col-span-1">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.converted_inventory_fee')
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="{{config('constants.option.converted_inventory_fee')}}"
                                           required
                                           oninvalid="this.setCustomValidity('@lang('general.converted_inventory_fee_required')')"
                                           oninput="setCustomValidity('')"
                                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                           value="{{old(config('constants.option.converted_inventory_fee'),optional($options)[config('constants.option.converted_inventory_fee')])}}"
                                    >
                                    @error(config('constants.option.converted_inventory_fee'))
                                    <span class="text-red-500">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="col-span-2 md:col-span-1">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.converted_inventory_fee_max')
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="{{config('constants.option.converted_inventory_fee_max')}}"
                                           required
                                           oninvalid="this.setCustomValidity('@lang('general.converted_inventory_fee_max_requried')')"
                                           oninput="setCustomValidity('')"
                                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                           value="{{old(config('constants.option.converted_inventory_fee_max'),optional($options)[config('constants.option.converted_inventory_fee_max')])}}"
                                    >
                                    @error(config('constants.option.converted_inventory_fee_max'))
                                    <span class="text-red-500">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="pt-4 col-span-full flex items-center gap-2">
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
