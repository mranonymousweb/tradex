@extends('admin.layout.master')

@section('title', trans('general.security_setting'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900">

        <div class="max-w-7xl mx-auto mb-8">
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
                    <a href="{{route('admin.setting.security')}}"
                       class="inline-flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm transition-all dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        @lang('master.back')
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto space-y-6">

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">@lang('general.add_new_ip')</h3>
                    </div>
                </div>

                <form action="{{ route('admin.security.ip.store') }}" method="post" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                        <div class="md:col-span-5">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.title')</label>
                            <input type="text" name="name" value="{{old('name')}}" placeholder="e.g. Office VPN"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:ring-2 focus:ring-primary-500 transition-all outline-none text-sm @error('name') border-red-500 @enderror"/>
                            @error('name') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-5">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.ip')</label>
                            <input type="text" name="ip" value="{{old('ip')}}" placeholder="192.168.1.1"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:ring-2 focus:ring-primary-500 transition-all outline-none text-sm font-mono @error('ip') border-red-500 @enderror"/>
                            @error('ip') <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2 md:pt-7">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-500/20 transition-all active:scale-95 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                @lang('general.save')
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 dark:text-white">@lang('general.active_whitelist')</h3>
                    <span class="px-2.5 py-0.5 text-xs font-bold bg-green-100 text-green-700 rounded-full">@lang('general.system_active')</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/20">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-start">@lang('general.title')</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-start">@lang('general.ip')</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-start">@lang('general.created_at')</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-end">@lang('general.operation')</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($ips as $ip)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors group">
                                <td class="px-6 py-4 text-start">
                                    <span class="font-semibold text-gray-900 dark:text-gray-200">{{ $ip->label }}</span>
                                </td>
                                <td class="px-6 py-4 text-start">
                                    <code class="px-2 py-1 bg-gray-100 dark:bg-gray-900 rounded text-xs text-primary-600 dark:text-primary-400 font-mono">
                                        {{ $ip->ip }}
                                    </code>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-start">
                                    {{ $ip->getCreatedAt() }}
                                </td>
                                <td class="px-6 py-4 text-end">

                                    <form id="delete-form" method="POST" style="display:none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button  onclick="mboxDelete('{{ route('admin.security.ip.destroy',['id'=>$ip->id]) }}')" class="text-gray-400 hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                        <p>@lang('general.not_found')</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($ips->hasPages())
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                        {{ $ips->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
