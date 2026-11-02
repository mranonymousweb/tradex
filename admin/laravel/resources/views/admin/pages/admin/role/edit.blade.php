@extends('admin.layout.master')

@section('title', trans('master.roles'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('master.roles')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.roles.index')}}"
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
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">

                    <form action="{{ route('admin.roles.update', $role) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.title')<span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{old('name',$role->name)}}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.title')">
                                @error('name')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <br>

                        <label class="block mb-4 font-medium text-gray-700 dark:text-gray-300">
                            <input type="checkbox" onchange="document.querySelectorAll('[name=\'permissions[]\']').forEach(c => c.checked = this.checked)">
                            @lang('general.choose_all')
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach ($permissions as $group => $perms)
                                <fieldset style="margin-bottom: 1rem;">
                                    <legend style="font-weight: bold; text-transform: capitalize;">{{ getPermissionGroup($group) }}</legend>

                                    @foreach ($perms as $permission)
                                        @php
                                            $lastPart = explode('.', $permission->name);
                                            $action = end($lastPart) ?? $permission->name;
                                        @endphp
                                        <div>
                                            <input type="checkbox" id="perm_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}"{{ isset($role) && $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                            <label for="perm_{{ $permission->id }}">{{ getPermissionName($permission->name)}}</label>
                                        </div>

                                    @endforeach
                                </fieldset>
                            @endforeach


                        </div>
                        <button type="submit" class="px-3 py-1 border rounded-lg text-sm font-medium text-white bg-primary-600 dark:bg-primary-500 hover:bg-primary-500 dark:hover:bg-primary-600 border-primary-600 dark:border-primary-500">
                            @lang('general.save')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection


