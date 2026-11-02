@extends('admin.layout.master')

@php
    $isPopUp = ($section == config('constants.option.section_pop_up'));
    $title = $isPopUp ? trans('general.pop_up_notifications') : trans('general.normal_notifications');
@endphp

@section('title', $title)

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">

        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                    {{ $title }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    @lang('general.manage_and_monitor_notifications_sent_to_users')
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.notification.create', ['section' => $section]) }}"
                   class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-primary-600 hover:bg-primary-700 shadow-md shadow-primary-200 dark:shadow-none transition-all transform active:scale-95">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    @lang('general.create_new')
                </a>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="mb-6">
            @include('admin.pages.filter', [
                'filters' => $filters,
                'back' => route('admin.notification.index', ['section' => $section])
            ])
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-right border-collapse">
                    <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.id')</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">@lang('general.title')</th>

                        @if(!$isPopUp)
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.description')</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.type')</th>
                        @else
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.see_status')</th>
                        @endif

                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">@lang('master.user')</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.created_at')</th>

                        @if($isPopUp)
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.operation')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($notifications as $notification)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-mono text-gray-400">
                                #{{ $notification->id }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-[200px]">
                                    {{ $isPopUp ? $notification->getTranslation('title', $lang) : $notification->title }}
                                </div>
                            </td>

                            @if(!$isPopUp)
                                <td class="px-6 py-4 text-center">
                                    <button type="button" data-description="{{ $notification->description }}"
                                            class="description-button inline-flex items-center text-primary-600 hover:text-primary-700 font-medium text-sm transition-colors">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        @lang('general.see')
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-100 dark:border-blue-800">
                                        {{ $notification->getTypes() }}
                                    </span>
                                </td>
                            @else
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $notification->is_read ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                        <span class="w-1.5 h-1.5 ml-1.5 rounded-full {{ $notification->is_read ? 'bg-green-500' : 'bg-amber-500' }}"></span>
                                        {{ $notification->getRead() }}
                                    </span>
                                </td>
                            @endif

                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-admin.user-profile :user="$notification?->user" />
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs text-gray-500 dark:text-gray-400">
                                {{ $notification->getCreatedAt() }}
                            </td>

                            @if($isPopUp)
                                <td class="px-6 py-4 whitespace-nowrap text-center space-x-reverse space-x-2">
                                    <a href="{{ route('admin.notification.edit', [$notification->id, 'section' => config('constants.option.section_pop_up')]) }}"
                                       class="inline-flex items-center p-2 rounded-lg text-amber-600 hover:bg-amber-50 transition-colors" title="@lang('general.edit')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <button onclick="mboxDelete('{{ route('admin.notification.destroy', $notification->id) }}')"
                                            class="inline-flex items-center p-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors" title="@lang('general.delete')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 00-2 2H6a2 2 0 00-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">@lang('general.no_notifications_found')</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($notifications->hasPages())
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700">
                    {{ $notifications->withQueryString()->links('pagination::tailwind') }}
                </div>
            @endif
        </div>

        <!-- Hidden Delete Form -->
        <form id="delete-form" method="POST" style="display:none">
            @csrf
            @method('DELETE')
        </form>

    </main>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.description-button').forEach((button) => {
            button.addEventListener('click', (event) => {
                mboxNotice(event.currentTarget.dataset.description)
            })
        })
    </script>
@endsection
