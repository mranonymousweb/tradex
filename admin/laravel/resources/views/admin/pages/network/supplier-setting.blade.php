@extends('admin.layout.master')

@section('title', trans('general.edit_network'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900 space-y-6" >

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200 dark:border-gray-800">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.edit_network')</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">@lang('general.update_network_settings_description')</p>
            </div>
            <div>
                <a href="{{route('admin.network.provider.supplier')}}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium rounded-xl bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 transition duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 111.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">@lang('general.network_settings')</h3>
            </div>

            <form action="{{ route('admin.network.provider.setting.update', $network) }}" method="post" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">


                    <select name="network_id" class="w-full select2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white transition">
                        <option value="">
                            {{ __('general.select') }} ({{ __('general.connect_to') }})
                        </option>

                        @foreach($networks as $item)
                            <option value="{{ $item->id }}"
                                @selected(old('network_id', $network->network_id ?? null) == $item->id)>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('network_id')
                    <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror

                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium rounded-xl bg-primary-600 text-white hover:bg-primary-700 shadow-sm focus:ring-4 focus:ring-primary-200 dark:bg-primary-500 dark:hover:bg-primary-600 dark:focus:ring-primary-800 transition duration-150">
                        @lang('general.save')
                    </button>
                </div>
            </form>
        </div>

    </main>
@endsection
