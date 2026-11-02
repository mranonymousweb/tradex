@extends('admin.layout.master')

@section('title', trans('master.user_card'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">

        <!-- Header Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                    @lang('general.bank_cards_management')
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    @lang('general.review_and_manage_user_bank_cards')
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                @if($previousRouteName && request('user') && $previousRouteName == 'admin.user.edit')
                    @can('admin.user.edit')
                        <a href="{{route('admin.user.edit',['user'=>request('user')])}}"
                           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            @lang('master.back')
                        </a>
                    @endcan
                @endif

                @can('admin.card.store')
                    <a href="{{route('admin.card.add')}}"
                       class="inline-flex items-center px-5 py-2.5 text-sm font-semibold rounded-xl text-white bg-primary-600 hover:bg-primary-700 shadow-md shadow-primary-200 dark:shadow-none transition-all transform active:scale-95">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        @lang('general.add_new_card')
                    </a>
                @endcan
            </div>
        </div>

        <!-- Filter Section -->
        <div class="mb-6">
            @include('admin.pages.filter',['filters' => $filters,'back'=>route('admin.card.index')])
        </div>

        <!-- Data Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto text-right">
                <table class="w-full border-collapse">
                    <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">@lang('general.user')</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">@lang('general.bank_info')</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase text-center">@lang('general.card_details')</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase text-center">@lang('general.status')</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase text-center">@lang('general.request_date')</th>
                        @canany(['admin.card.details', 'admin.card.destroy'])
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase text-center">@lang('general.operation')</th>
                        @endcanany
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($cards as $card)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                            <!-- User Column -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-admin.user-profile :user="$card?->user"/>
                            </td>

                            <!-- Bank Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-admin.bank-profile :bank="$card?->bank"/>
                            </td>

                            <!-- Card & IBAN -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1 items-center">
                                    <div class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-md text-sm font-mono font-semibold tracking-widest text-gray-700 dark:text-gray-200">
                                        {{ $card->card_number ? implode(' - ', str_split($card->card_number, 4)) : '-' }}
                                    </div>
                                    <div class="text-[11px] font-mono text-gray-500 flex items-center">
                                        <span class="ml-1 opacity-50 italic">IBAN:</span> {{ $card->iban ?? '-' }}
                                    </div>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="inline-block transform scale-90">
                                    {!! $card->getStatus() !!}
                                </div>
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $card->getCreatedAt() }}
                                </span>
                            </td>

                            <!-- Actions -->
                            @canany(['admin.card.details', 'admin.card.destroy'])
                                <td class="px-6 py-4 whitespace-nowrap text-center space-x-reverse space-x-2">
                                    @can('admin.card.details')
                                        <a href="{{ route('admin.card.details', $card) }}"
                                           class="inline-flex items-center p-2 rounded-lg text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 transition-all" title="@lang('general.details')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                    @endcan

                                    @can('admin.card.destroy')
                                        <button onclick="mboxDelete('{{ route('admin.card.destroy', $card) }}')"
                                                class="inline-flex items-center p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition-all" title="@lang('general.delete')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endcan
                                </td>
                            @endcanany
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="p-4 rounded-full bg-gray-50 dark:bg-gray-700/50 mb-4">
                                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    </div>
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">@lang('general.no_bank_cards_registered')</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($cards->hasPages())
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-100 dark:border-gray-700">
                    {{ $cards->links('pagination::tailwind') }}
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
