@extends('admin.layout.master')

@section('title', trans('general.deposit_request_setting'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-dark dark:text-light">@lang('general.deposit_request_setting')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.setting.plugin.index')}}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-light hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
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

                        {{-- Fields --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.amount_tolerance')
                                    <span class="text-red-500">*</span>
                                </label>

                                <input type="number"
                                       min="0.001"
                                       max="100"
                                       step="0.001"
                                       name="{{config('constants.plugin.plugin_deposit_with_request_tolerance')}}"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.amount_tolerance_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{old(config('constants.plugin.plugin_deposit_with_request_tolerance'),optional($options)[config('constants.plugin.plugin_deposit_with_request_tolerance')])}}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.amount_tolerance')">

                                @error(config('constants.plugin.plugin_deposit_with_request_tolerance'))
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Button Row --}}
                        <div class="mt-6 flex">
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

