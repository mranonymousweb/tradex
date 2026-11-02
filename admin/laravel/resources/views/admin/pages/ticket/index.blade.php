@extends('admin.layout.master')

@section('title', trans('master.ticket'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">

        <!-- Header Page -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                    @lang('master.ticket')
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">@lang('general.managing_and_responding_to_user_requests')</p>
            </div>

            <div class="flex items-center gap-3">
                @if($previousRouteName && request('user') && $previousRouteName == 'admin.user.edit')
                    <a href="{{route('admin.user.edit',['user'=>request('user')])}}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 shadow-sm transition-all dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        @lang('master.back')
                    </a>
                @endif

                @can('admin.ticket.store')
                    <a href="{{ route('admin.ticket.create', request()->only('user')) }}"
                       class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-primary-600 hover:bg-primary-700 shadow-md shadow-primary-200 dark:shadow-none transition-all transform active:scale-95">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        @lang('general.save')
                    </a>
                @endcan
            </div>
        </div>

        <!-- Filter Section -->
        @include('admin.pages.filter', [
                'filters' => $filters,
                'back' => route('admin.ticket.index')
            ])

        <!-- Tickets Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-start border-collapse">
                    <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-start">@lang('general.id')</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase text-start">@lang('master.user')</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-start">@lang('general.title')</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-start">@lang('general.department')</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-start">@lang('general.status')</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-start">@lang('general.created_at')</th>
                        @can('admin.ticket.chat')
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-end">@lang('general.operation')</th>
                        @endcan
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-start">
                                <span class="font-mono font-bold text-gray-400 dark:text-gray-500">{{$ticket?->id}}</span>
                            </td>

                            <td class="px-6 py-4 text-start">
                                <x-admin.user-profile :user="$ticket->user" />
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-start text-xs font-semibold text-gray-900 dark:text-white truncate max-w-xs">
                                    {{$ticket?->title}}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-start">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    {{$ticket?->department?->getName()}}
                                </span>
                            </td>



                            <td class="px-6 py-4 whitespace-nowrap text-start">
                                {!! $ticket->getStatus() !!}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-start text-xs text-gray-500 dark:text-gray-400">
                                {{ $ticket->getCreatedAt() }}
                            </td>

                            @can('admin.ticket.chat')
                                <td class="px-6 py-4 whitespace-nowrap text-end">
                                    <a href="{{route('admin.ticket.chat',$ticket)}}"
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold text-primary-600 bg-primary-50 hover:bg-primary-600 hover:text-white transition-all dark:bg-primary-900/20 dark:text-primary-400 dark:hover:bg-primary-600 dark:hover:text-white">
                                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        @lang('general.view')
                                    </a>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 00-2 2H6a2 2 0 00-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">@lang('general.not_found')</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($tickets->hasPages())
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700">
                    {{ $tickets->withQueryString()->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </main>
@endsection
