@extends('admin.layout.master')

@section('title', __('general.site_setting'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.site_setting')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.setting.site.index')}}" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <!-- Summary of cards -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.option.save') }}" method="POST" enctype="multipart/form-data"
                          class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Title --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.meta.title') <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="{{ config('constants.meta.title') }}"
                                       value="{{ old(config('constants.meta.title'), optional($options)[config('constants.meta.title')]) }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.meta.title')">
                                @error(config('constants.meta.title'))
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Author --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.meta.author')
                                </label>
                                <input type="text"
                                       name="{{ config('constants.meta.author') }}"
                                       value="{{ old(config('constants.meta.author'), optional($options)[config('constants.meta.author')]) }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.meta.author')">
                                @error(config('constants.meta.author'))
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.meta.description')
                                </label>
                                <textarea name="{{ config('constants.meta.description') }}"
                                          rows="3"
                                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                          placeholder="@lang('general.meta.description')">{{ old(config('constants.meta.description'), optional($options)[config('constants.meta.description')]) }}</textarea>
                                @error(config('constants.meta.description'))
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Keywords --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.meta.keywords')
                                </label>
                                <textarea name="{{ config('constants.meta.keywords') }}"
                                          rows="3"
                                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                          placeholder="@lang('general.meta.keywords')">{{ old(config('constants.meta.keywords'), optional($options)[config('constants.meta.keywords')]) }}</textarea>

                                @error(config('constants.meta.keywords'))
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>


                            {{-- Copyright --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.meta.copyright')
                                </label>
                                <input type="text"
                                       name="{{ config('constants.meta.copyright') }}"
                                       value="{{ old(config('constants.meta.copyright'), optional($options)[config('constants.meta.copyright')]) }}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.meta.copyright')">
                                @error(config('constants.meta.copyright'))
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Footer Link --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.meta.footer_link')
                                </label>

                                <input
                                    type="text"
                                    name="{{ config('constants.meta.footer') }}"
                                    value="{{ old(config('constants.meta.footer'), optional($options)[config('constants.meta.footer')]) }}"
                                    placeholder="@lang('general.meta.footer_link')"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    @lang('general.meta.meta_footer_note')
                                </p>

                                @error(config('constants.meta.footer'))
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Footer Link --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.meta.footer_link')
                                </label>

                                <input
                                    type="text"
                                    name="{{ config('constants.meta.footer_link') }}"
                                    value="{{ old(config('constants.meta.footer_link'), optional($options)[config('constants.meta.footer_link')]) }}"
                                    placeholder="@lang('general.meta.footer_link')"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    @lang('general.meta.meta_footer_note')
                                </p>

                                @error(config('constants.meta.footer_link'))
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Header  --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.meta.hear_link')
                                </label>

                                <input
                                    type="text"
                                    name="{{ config('constants.meta.header') }}"
                                    value="{{ old(config('constants.meta.header'), optional($options)[config('constants.meta.header')]) }}"
                                    placeholder="@lang('general.meta.hear_link')"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    @lang('general.meta.meta_footer_note')
                                </p>

                                @error(config('constants.meta.header'))
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Header Link --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.meta.hear_link')
                                </label>

                                <input
                                    type="text"
                                    name="{{ config('constants.meta.header_link') }}"
                                    value="{{ old(config('constants.meta.header_link'), optional($options)[config('constants.meta.header_link')]) }}"
                                    placeholder="@lang('general.meta.hear_link')"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    @lang('general.meta.meta_footer_note')
                                </p>

                                @error(config('constants.meta.header_link'))
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Phone  --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.mobile')
                                </label>

                                <input
                                    type="text"
                                    name="{{ config('constants.meta.phone') }}"
                                    value="{{ old(config('constants.meta.phone'), optional($options)[config('constants.meta.phone')]) }}"
                                    placeholder="@lang('general.mobile')"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >


                                @error(config('constants.meta.email'))
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Email --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.email')
                                </label>

                                <input
                                    type="text"
                                    name="{{ config('constants.meta.email') }}"
                                    value="{{ old(config('constants.meta.email'), optional($options)[config('constants.meta.email')]) }}"
                                    placeholder="@lang('general.email')"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >



                                @error(config('constants.meta.email'))
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- User id prefix  --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.user_id_prefix')
                                </label>

                                <input
                                    type="text"
                                    name="{{ config('constants.meta.meta_data_user_prefix') }}"
                                    value="{{ old(config('constants.meta.meta_data_user_prefix'), optional($options)[config('constants.meta.meta_data_user_prefix')]) }}"
                                    placeholder="@lang('general.user_id_prefix')"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                >


                                @error(config('constants.meta.meta_data_user_prefix'))
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Light Logo --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.light_logo')
                                </label>
                                <input type="file"
                                       name="{{ config('constants.meta.logo') }}"
                                       accept="image/*"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    @lang('general.meta.logo_not')
                                </p>
                                @if(optional($options)[config('constants.meta.logo')])
                                    <div class="mt-2">
                                        <img src="{{ optional($options)[config('constants.meta.logo')] }}"
                                             alt="Logo" class="h-96 rounded">
                                    </div>
                                @endif
                                @error(config('constants.meta.logo'))
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Dark Logo --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.dark_logo')
                                </label>
                                <input type="file"
                                       name="{{ config('constants.meta.dark_logo') }}"
                                       accept="image/*"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    @lang('general.meta.logo_not')
                                </p>
                                @if(optional($options)[config('constants.meta.dark_logo')])
                                    <div class="mt-2">
                                        <img src="{{ optional($options)[config('constants.meta.dark_logo')] }}"
                                             alt="Logo" class="h-96 rounded">
                                    </div>
                                @endif
                                @error(config('constants.meta.dark_logo'))
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Favicon --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.meta.favicon')
                                </label>
                                <input type="file"
                                       name="{{ config('constants.meta.favicon') }}"
                                       accept="image/*"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    @lang('general.meta.logo_not')
                                </p>
                                @if(optional($options)[config('constants.meta.favicon')])
                                    <div class="mt-2">
                                        <img src="{{ optional($options)[config('constants.meta.favicon')] }}"
                                             alt="Favicon" class="h-96 rounded">
                                    </div>
                                @endif
                                @error(config('constants.meta.favicon'))
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        {{-- Submit --}}
                        <div class="pt-4">
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

