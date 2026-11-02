@extends('admin.layout.master')

@section('title', trans('master.ticket'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('master.crete_ticket')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.ticket.index', request()->only('user')) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <!-- Create New Ticket Card -->
        <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft dark:shadow-soft-dark p-6 mb-6 border border-gray-100 dark:border-gray-700">

            <form action="{{ route('admin.ticket.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.title')<span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{old('title')}}"  class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="@lang('general.title')">
                    @error('title')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="user" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('master.user')<span class="text-red-500">*</span></label>
                    <select name="user" id="user" class="select2 w-full appearance-none px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected((string) old('user', $selectedUserId ?? '') === (string) $user->id)>{{ $user->getFullName()}}</option>
                        @endforeach
                    </select>
                    @error('user')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                    <div class="absolute inset-y-0 top-1/2 left-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('master.department')<span class="text-red-500">*</span></label>
                    <select  name="department" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                             required
                             oninvalid="this.setCustomValidity('@lang('general.department_required')')"
                             oninput="setCustomValidity('')"
                    >
                        <option value="" @selected(old('department') === null)>{{ __('general.select') }} ({{ __('master.department') }})</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected(old('department') == $department->id)>{{ $department->getName() }}</option>
                        @endforeach
                    </select>
                    @error('department')
                    <span class="text-red-500">{{$message}}</span>
                    @enderror
                </div>


                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.description')</label>
                    <textarea id="description" name="description" rows="5" class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="@lang('general.ticket.write_your_message')">{{old('description')}}</textarea>
                    @error('description')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.attachment')</label>
                    <input id="file-upload" multiple type="file" name="files[]"  class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                    @error('files')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg bg-primary text-white hover:bg-primary/90 dark:bg-primary-dark dark:hover:bg-primary/80">
                        @lang('general.save')
                    </button>
                </div>
            </form>

        </div>

    </main>
@endsection


