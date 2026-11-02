@extends('admin.layout.master')

@section('title', trans('master.users'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900">
        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">@lang('master.users')</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">@lang('general.manage_and_view_all_registered_users')</p>
            </div>
            <div class="flex items-center gap-3">
                @can('admin.user.store')
                    <a href="{{ route('admin.user.add') }}"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-bold rounded-xl bg-primary-600 text-white hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition-all active:scale-95">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        @lang('general.save')
                    </a>
                @endcan
            </div>
        </div>

        <!-- Filter Component -->
        <div class="mb-6">
            @include('admin.pages.filter', ['filters' => $filters, 'back' => route('admin.user.index')])
        </div>

        <!-- Table Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-start">
                    <thead>
                        <tr
                            class="bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300">
                            <th class="px-6 py-4 font-semibold text-sm text-start">@lang('general.user')</th>
                            <th class="px-6 py-4 font-semibold text-sm text-start">@lang('general.contact_info')</th>
                            <th class="px-6 py-4 font-semibold text-sm text-start">@lang('general.package')</th>
                            <th class="px-6 py-4 font-semibold text-sm text-center">@lang('general.status')</th>
                            @canany(['admin.user.edit', 'admin.user.toggle-status'])
                                <th class="px-6 py-4 font-semibold text-sm text-center">@lang('general.operation')</th>
                            @endcanany
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-admin.user-profile :user="$user" />
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 dark:text-gray-300">{{ $user->mobile }}</div>
                                    <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300 border border-blue-100 dark:border-blue-800">
                                        {{ $user->package->name ?? '---' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="inline-block">
                                        {!! $user->getStatus() !!}
                                    </div>
                                </td>
                                @canany(['admin.user.edit', 'admin.user.toggle-status'])
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-center items-center gap-2">
                                            @can('admin.user.edit')
                                                <a href="{{ route('admin.user.edit', $user) }}"
                                                    class="p-2 text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-lg transition-colors tooltip"
                                                    title="@lang('general.details')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            @endcan

                                            @can('admin.user.toggle-status')
                                                @if ($user->status == 1)
                                                    <button onclick="mboxQuestion('{{ route('admin.user.toggle-status', $user) }}')"
                                                        type="button"
                                                        class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                                                        title="@lang('general.inactive')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>

                                                    </button>
                                                @else
                                                    <button onclick="mboxQuestion('{{ route('admin.user.toggle-status', $user) }}')"
                                                        type="button"
                                                        class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                                                        title="@lang('general.active')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="green" class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M4.5 12.75 10.5 18 19.5 6" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                @endcanany
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        <span class="text-gray-500 font-medium">@lang('general.not_found')</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer / Pagination -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
                {{ $users->withQueryString()->links('pagination::tailwind') }}
            </div>
        </div>

        <!-- Standard Hidden Delete Form -->
        <form id="delete-form" method="POST" style="display:none">
            @csrf
            @method('DELETE')
        </form>
    </main>
@endsection
