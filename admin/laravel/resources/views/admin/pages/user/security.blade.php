@extends('admin.layout.master')

@section('title', trans('general.security'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">

        <!-- Header Section -->
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">@lang('general.security')</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">@lang('general.manage_user_security_and_sessions')</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{route('admin.user.index')}}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 shadow-sm transition-all dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <!-- Main Content (Tables) -->
            <div class="lg:col-span-3 space-y-8">

                <!-- 2FA Section -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all hover:shadow-md">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
                        <h3 class="font-bold text-gray-800 dark:text-white flex items-center">
                            <svg class="w-5 h-5 ml-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            @lang('general.2fa')
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-gray-50/80 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.id')</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.status')</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.last_used_at')</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.operation')</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($googleAuths as $auth)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 text-center font-mono">#{{$auth?->id}}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        @if($auth?->is_enabled)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <span class="w-1.5 h-1.5 ml-1.5 rounded-full bg-green-500"></span>
                                            @lang('general.active')
                                        </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                            <span class="w-1.5 h-1.5 ml-1.5 rounded-full bg-red-500"></span>
                                            @lang('general.inactive')
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">{{$auth?->getVerifiedAt() ?? '-'}}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button onclick="mboxDelete('{{route('admin.user.details.security.destroy.google.auth',$auth)}}')"
                                                class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors group">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-10 text-center text-gray-400">@lang('general.not_found')</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Active Devices Section -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all hover:shadow-md">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
                        <h3 class="font-bold text-gray-800 dark:text-white flex items-center">
                            <svg class="w-5 h-5 ml-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            @lang('general.active_devices')
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-right">
                            <thead class="bg-gray-50/80 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.id')</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">@lang('general.details')</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.last_used_at')</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.operation')</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($tokens as $token)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 text-center font-mono">#{{$token?->id}}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{$token?->platform ?? 'Unknown'}} - {{$token?->browser}}</div>
                                                <div class="text-xs text-gray-500 flex items-center mt-0.5">
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    IP: {{$token?->ip_address}}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">{{$token?->last_used_at ?? '-'}}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button onclick="mboxDelete('{{route('admin.user.details.security.destroy.token',$token->id)}}')"
                                                class="px-3 py-1.5 rounded-lg text-xs font-bold text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all">
                                            @lang('general.terminate')
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-10 text-center text-gray-400">@lang('general.not_found')</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Sidebar Section -->
            <aside class="lg:col-span-1">
                @include('admin.pages.admin.side')
            </aside>

        </div>

        <!-- Hidden Master Form for Deletion -->
        <form id="delete-form" method="POST" style="display:none">
            @csrf
            @method('DELETE')
        </form>
    </main>
@endsection
