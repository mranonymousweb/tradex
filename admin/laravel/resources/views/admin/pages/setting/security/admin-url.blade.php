@extends('admin.layout.master')

@section('title', trans('general.security_setting'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900">

        <div class="max-w-full mx-auto mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <nav class="flex mb-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                        <span>@lang('general.settings')</span>
                        <span class="mx-2">/</span>
                        <span class="text-primary-600">@lang('general.security')</span>
                    </nav>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        @lang('general.security_setting')
                    </h2>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.setting.security') }}"
                       class="inline-flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm transition-all dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        @lang('master.back')
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-full mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">@lang('general.admin_access_url')</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">@lang('general.admin_access_url_des')</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.option.save') }}" method="post" enctype="multipart/form-data" class="p-6 md:p-8">
                    @csrf

                    <div class="max-w-xl">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            @lang('general.login_admin_panel')
                        </label>

                        <div class="relative flex items-center shadow-sm rounded-xl overflow-hidden border border-gray-300 dark:border-gray-600 focus-within:ring-2 focus-within:ring-primary-500 focus-within:border-primary-500 transition-all dark:bg-gray-900">
                        <span class="pl-4 pr-2 text-gray-400 dark:text-gray-500 font-mono text-sm select-none">
                            /
                        </span>
                            <input type="text"
                                   name="{{ config('constants.option.admin_login_url') }}"
                                   value="{{ old(config('constants.option.admin_login_url'), optional($options)[config('constants.option.admin_login_url')] ?? '') }}"
                                   placeholder="secure-admin-login"
                                   class="w-full py-2.5 pr-4 bg-transparent border-none focus:ring-0 text-sm text-gray-900 dark:text-white placeholder-gray-400 outline-none font-mono text-"
                            >
                        </div>

                        @error(config('constants.option.admin_login_url'))
                        <p class="text-red-500 text-xs mt-2 font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                        @enderror

                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                           @lang('general.admin_access_url_change_des')
                        </p>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold rounded-xl bg-primary-600 hover:bg-primary-700 text-white shadow-lg shadow-primary-500/30 transition-all active:scale-95">
                            @lang('general.save')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection
