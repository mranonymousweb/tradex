@extends('admin.layout.master')

@section('title', trans('general.inventory'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                        @lang('general.inventory')
                    </h2>
                    <span class="inline-flex items-center rounded-full bg-primary-50 px-3 py-1 text-xs font-semibold text-primary-700 ring-1 ring-inset ring-primary-200 dark:bg-primary-900/30 dark:text-primary-300 dark:ring-primary-800">
                        {{ \App\Models\SupplierExchange::getAllSupplier($supplier->name) ?? $supplier->name }}
                    </span>
                </div>
            </div>
            <a href="{{ route('admin.setting.supplier') }}"
               class="inline-flex items-center px-4 py-2.5 text-sm font-medium rounded-xl bg-white text-gray-700 hover:bg-gray-50 shadow-sm border border-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-200 dark:border-gray-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                @lang('master.back')
            </a>
        </div>

        @php
            $withLocked = $assets->filter(fn ($asset) => (float) ($asset['locked'] ?? 0) > 0)->count();
        @endphp
        <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('general.currencies')</p>
                <p class="mt-2 text-3xl font-extrabold text-gray-900 dark:text-white">{{ $assets->count() }}</p>
            </div>
            <div class="rounded-2xl border border-amber-200 dark:border-amber-900/50 bg-amber-50/50 dark:bg-amber-950/20 p-5 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">@lang('general.blocked_balance')</p>
                <p class="mt-2 text-3xl font-extrabold text-amber-700 dark:text-amber-300">{{ $withLocked }}</p>
            </div>
        </div>

        @include('admin.pages.filter', compact('filters', 'back', 'hidden'))

        @if($assets->isEmpty())
            <div class="rounded-2xl border border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-6 py-20 text-center shadow-sm">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-300">@lang('general.not_found')</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($assets as $asset)
                    @php
                        $total = (float) ($asset['balance'] ?? 0);
                        $available = (float) ($asset['available'] ?? 0);
                        $locked = (float) ($asset['locked'] ?? 0);
                        $lockedPercent = $total > 0 ? min(100, round(($locked / $total) * 100)) : 0;
                    @endphp
                    <div class="group relative overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm hover:shadow-lg hover:border-primary-200 dark:hover:border-primary-800 transition-all duration-300">
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-primary-500 via-primary-400 to-indigo-400 opacity-80 group-hover:opacity-100 transition-opacity"></div>

                        <div class="p-5">
                            <x-admin.currency :currency="$asset['currency']"/>

                            <div class="mt-5 rounded-xl bg-gray-50 dark:bg-gray-900/50 p-4 border border-gray-100 dark:border-gray-700/60">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1">
                                    @lang('general.balance')
                                </p>
                                <p class="text-2xl font-extrabold font-mono text-gray-900 dark:text-white tracking-tight">
                                    {{ toCurrency($asset['balance'] ?? 0, 8) }}
                                </p>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <div class="rounded-xl bg-emerald-50/80 dark:bg-emerald-950/20 p-3 border border-emerald-100 dark:border-emerald-900/40">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                                        @lang('general.general_balance')
                                    </p>
                                    <p class="mt-1 text-sm font-bold font-mono text-emerald-700 dark:text-emerald-300">
                                        {{ toCurrency($asset['available'] ?? 0, 8) }}
                                    </p>
                                </div>
                                <div class="rounded-xl bg-amber-50/80 dark:bg-amber-950/20 p-3 border border-amber-100 dark:border-amber-900/40">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">
                                        @lang('general.blocked_balance')
                                    </p>
                                    <p class="mt-1 text-sm font-bold font-mono text-amber-700 dark:text-amber-300">
                                        {{ toCurrency($asset['locked'] ?? 0, 8) }}
                                    </p>
                                </div>
                            </div>

                            @if($locked > 0)
                                <div class="mt-4">
                                    <div class="mb-1.5 flex items-center justify-between text-[10px] font-semibold text-gray-500 dark:text-gray-400">
                                        <span>@lang('general.blocked_balance')</span>
                                        <span>{{ $lockedPercent }}%</span>
                                    </div>
                                    <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                                        <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-500 transition-all duration-500"
                                             style="width: {{ $lockedPercent }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
@endsection
