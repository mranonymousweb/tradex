@extends('admin.layout.master')

@section('title', trans('general.profile'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.profile')</h2>
            </div>
        </div>
        <!-- Update Profile form -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">

                    <form action="{{ route('admin.admin.profile.update',$admin) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"> @lang('general.name')<span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{old('name',$admin->name)}}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="@lang('general.name')">
                                @error('name')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>
                            <div>
                                <label  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.family')</label>
                                <input type="text" name="family" value="{{old('family',$admin->family)}}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="@lang('general.family')">
                                @error('family')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>
                            <div>
                                <label  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.mobile')</label>
                                <input type="text" name="mobile" value="{{old('mobile',$admin->mobile)}}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="@lang('general.mobile')">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.email')<span class="text-red-500">*</span></label>
                                <input type="text" name="email" value="{{old('email',$admin->email)}}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="@lang('general.email')">
                                @error('email')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>
                            <div>
                                <label  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.image')</label>
                                <input type="file" name="avatar" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="@lang('general.image')">
                                @error('avatar')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="pt-4">
                                <button type="submit" class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
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


