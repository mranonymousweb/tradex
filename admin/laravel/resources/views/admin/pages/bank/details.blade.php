@extends('admin.layout.master')

@section('title', trans('general.banks'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.details')(@lang('general.bank'))</h2>
            </div>
            <div class="mt-4 md:mt-0">

                <a href="{{route('admin.bank.index')}}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">

                    <form action="{{ route('admin.bank.update',$bank) }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.name')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{old('name',$bank->name)}}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.name')">
                                @error('name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.icon')</label>
                                <input type="file" name="icon"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('icon')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>


                            <div>
                                <label  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"> @lang('general.status')<span class="text-red-500">*</span></label>

                                <select name="status" id="status"
                                        class="select2 w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                       focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                    <option value="">-- @lang('general.status') --</option>
                                    @foreach($options  as $key => $option)
                                        <option value="{{ $key }}" {{ old('status',$bank->status) == $key ? 'selected' : '' }}>
                                            {!! $option !!}
                                        </option>
                                    @endforeach
                                </select>

                                @error('status')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="mt-4">
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


