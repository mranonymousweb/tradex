@extends('admin.layout.master')

@section('title', trans('setting.spot'))

@section('contents')

    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-background-dark">
        <!-- Page header -->
        @include('admin.pages.spot.page-header')

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">

            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.option.save') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-2 gap-5" >
                            <div class="col-span-1 md:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.supplier')
                                    <span class="text-red-500">*</span></label>
                                <select  name="{{config('constants.option.spot_chart_provider')}}"
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value=""> @lang('general.select')</option>
                                    @foreach($suppliers as $key => $value)
                                        <option value="{{ $value }}" @if($value == $provider) selected @endif>{{ strtoupper($value) }}</option>
                                    @endforeach
                                </select>
                                @error(config('constants.option.spot_chart_provider'))
                                <span class="text-red-500">{{$message}}</span>
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


