@extends('admin.layout.master')

@section('title', trans('general.staking'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 sm:p-8 bg-gray-50 dark:bg-gray-900 font-sans">

        <!-- Header & Actions -->
        <div class="max-w-6xl mx-auto mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">@lang('general.staking')</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">@lang('general.id'): #{{ $userStaking->id }} | @lang('general.date'): {!! $userStaking->getCreatedAt() !!}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.staking.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-primary-600 text-white rounded-lg text-sm font-semibold shadow-md hover:opacity-90 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="max-w-6xl mx-auto space-y-6">

            <!-- Main Info Wrapper -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden border-t-[12px] border-t-primary-600">

                <!-- Section 1: User Details & Status -->
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h3 class="text-xs uppercase tracking-widest text-gray-400 font-bold mb-3">@lang('general.customer_details')</h3>
                        <div class="flex items-center gap-4">
                            <div class="relative inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-primary-50 dark:bg-primary-900/30 border border-primary-100 dark:border-primary-800 shadow-sm overflow-hidden group">
                                @if($userStaking->user && $userStaking->user->avatar)
                                    <img src="{{ $userStaking->user->getAvatar() }}" alt="{{ $userStaking->user->name }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110">
                                @else
                                    <span class="font-bold text-2xl text-primary-600 dark:text-primary-400 uppercase">{{ substr($userStaking->user->name ?? 'U', 0, 1) }}</span>
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('admin.user.edit', $userStaking->user) }}" class="text-sm font-bold text-gray-900 dark:text-white hover:text-primary-600 transition-colors">
                                    {{ $userStaking->user?->getFullInfo() ?? '-' }}
                                </a>
                                <p class="text-sm text-gray-500">{{ $userStaking->user->email ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="md:text-start flex items-center md:justify-end">
                        <div class="text-sm text-gray-500 space-y-1">
                            <p>@lang('general.status'): <span class="font-bold uppercase">{!! $userStaking->getStatus() !!}</span></p>
                            <p>@lang('general.date'): <span class="font-bold uppercase">{!! $userStaking->getCreatedAt() !!}</span></p>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Staking Plan Core Metadata -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 p-8 bg-gray-50/50 dark:bg-gray-900/20">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black">@lang('general.currency')</p>
                        <p class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $userStaking->currency->symbol }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black">@lang('general.amount')</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ toCurrency($userStaking->amount) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black">@lang('general.min_day')</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-gray-200">{{ toCurrency($userStaking->day) }} @lang('general.day')</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black">@lang('general.daily_profit_percent')</p>
                        <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">{{ toCurrency($userStaking->daily_profit_percent) }}%</p>
                    </div>
                </div>

            </div>

            <!-- Analytics Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- User Staking Amount -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 text-center">
                    <h3 class="text-gray-400 dark:text-gray-500 text-[11px] uppercase font-bold tracking-wider mb-1">@lang('general.staking_user_amount')</h3>
                    <p class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                        {{ toCurrency($userStaking->amount) }} <span class="text-xs font-normal text-gray-400">{{ $userStaking->currency->symbol }}</span>
                    </p>
                </div>

                <!-- Total Deposited Profit -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 text-center border-b-4 border-b-emerald-500">
                    <h3 class="text-gray-400 dark:text-gray-500 text-[11px] uppercase font-bold tracking-wider mb-1">@lang('general.total_deposited_profit')</h3>
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 tracking-tight">
                        {{ toCurrency($totalProfit) }} <span class="text-xs font-normal text-gray-400">{{ $userStaking->currency->symbol }}</span>
                    </p>
                </div>

                <!-- Staking Duration Days -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 text-center">
                    <h3 class="text-gray-400 dark:text-gray-500 text-[11px] uppercase font-bold tracking-wider mb-1">@lang('general.staking_duration_days')</h3>
                    <p class="text-2xl font-black text-primary-600 dark:text-primary-400 tracking-tight">
                        {{ $userStaking->getCountDay() }}
                    </p>
                </div>
            </div>

            <!-- Transaction History Table -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <span class="w-2 h-6 bg-primary-600 rounded-full"></span>
                    @lang('general.transaction_history')
                </h3>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-start border-collapse">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 text-[10px] uppercase text-gray-500 tracking-wider border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-start">@lang('general.id')</th>
                                <th class="px-6 py-4 text-start">@lang('general.currency')</th>
                                <th class="px-6 py-4 text-start">@lang('general.amount')</th>
                                <th class="px-6 py-4 text-start">@lang('general.date')</th>
                                <th class="px-6 py-4 text-end">@lang('general.description')</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40 transition-colors">
                                    <td class="px-6 py-4 text-start font-mono text-gray-400">
                                        #{{ $transaction->id }}
                                    </td>
                                    <td class="px-6 py-4 text-start font-bold text-gray-900 dark:text-white uppercase">
                                        {{ $transaction->currency->symbol }}
                                    </td>
                                    <td class="px-6 py-4 text-start font-black text-emerald-600 dark:text-emerald-400">
                                        {{ toCurrency($transaction->amount) }}
                                    </td>
                                    <td class="px-6 py-4 text-start text-gray-500 dark:text-gray-400 text-xs">
                                        {{ $transaction->getCreatedAt() }}
                                    </td>
                                    <td class="px-6 py-4 text-end text-gray-600 dark:text-gray-300 font-medium max-w-xs truncate">
                                        {{ __($transaction->description, ['id' => $transaction->transactionable_id]) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">
                                        @lang('general.not_found')
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Container -->
                    @if($transactions->hasPages())
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700">
                            {{ $transactions->withQueryString()->links('pagination::tailwind') }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>
@endsection
