@extends('admin.layout.master')

@section('title', trans('general.plugins'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">

        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    @lang('general.plugins')
                </h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.setting.index')}}"
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

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @foreach($plugins as $key => $value)

                <form method="POST"
                      action="{{ route('admin.option.save') }}"
                      class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 p-6 flex flex-col justify-between">
                    @csrf

                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-6">
                        {{-- Status Badge --}}
                            @php
                                $isActive =
                                    isset($options[$value['status']]) &&
                                    $options[$value['status']] == config('constants.option.active') &&
                                    isset($options[$key]) &&
                                    $options[$key] == $value['key'];
                            @endphp
                        <div>
                            <h3 class="font-semibold text-gray-800 dark:text-white">
                                {{ $value['label'] }}
                            </h3>

                            @if($isActive)
                                <a href="{{ $value['route'] }}"
                                   class="text-md text-primary-600 hover:text-primary-800 font-medium">
                                    @lang('general.settings')
                                </a>
                            @endif



                            <span
                                class="text-xs px-2 py-1 rounded-full {{ $isActive? 'bg-emerald-100 text-emerald-600': 'bg-red-100 text-red-600' }}">{{ $isActive ? __('general.active') : __('general.inactive') }}
                            </span>

                        </div>

                        {{-- Toggle --}}
                        <label class="relative inline-flex items-center cursor-pointer">

                            <input type="hidden"
                                   name="{{ $value['status'] }}"
                                   value="{{config('constants.option.inactive')}}">

                            <input type="checkbox"
                                   class="sr-only peer"
                                   name="{{ $value['status'] }}"
                                   value="{{config('constants.option.active')}}"
                                   @if(isset($options[$value['status']]) && $options[$value['status']] == config('constants.option.active'))
                                       checked
                                @endif
                            >

                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full
                                      peer-checked:bg-green-600 transition-colors duration-300">
                            </div>

                            <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow
                                        transition-transform duration-300
                                        peer-checked:translate-x-full">
                            </div>

                        </label>


                    </div>

                    {{-- Body --}}
                    <div class="flex-1">

                        <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-2">
                            @lang('general.plugin_key')
                        </label>

                        <input type="text"
                               name="{{ $key }}"
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl
                                  focus:ring-2 focus:ring-primary-500 focus:border-transparent
                                  dark:bg-gray-700 dark:text-white transition"

                               @if(isset($options[$key]))
                                   placeholder="{{ \Illuminate\Support\Str::of($options[$key])->mask('*', 0) }}"
                               @else
                                   placeholder="@lang('general.enter_key_plugin')"
                            @endif
                        >

                    </div>

                    {{-- Footer --}}
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">

                        <button type="submit"
                                class="px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-xl shadow transition">
                            @lang('general.save')
                        </button>

                    </div>

                </form>

            @endforeach

        </div>

    </main>
@endsection
