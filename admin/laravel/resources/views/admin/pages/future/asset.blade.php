@extends('admin.layout.master')

@section('title', trans('setting.futures'))

@section('contents')

    <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-50 dark:bg-background-dark">
        <!-- Page header -->
        @include('admin.pages.future.page-header')

        <div class="max-full space-y-6">

            <!-- Top Section: Balance & Form -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Inventory / Balance Widget -->
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-primary-600 to-primary-800 dark:from-primary-700 dark:to-primary-900 rounded-2xl shadow-lg p-6 text-white h-full flex flex-col justify-center relative overflow-hidden">
                        <!-- Decorative background element -->
                        <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>

                        <div class="relative z-10">
                            <span class="block text-primary-100 text-sm font-medium mb-1">@lang('general.inventory')</span>
                            <div class="flex items-baseline space-x-2 space-x-reverse">
                                <span class="text-4xl font-bold tracking-tight">{{ toCurrency($inventory) }}</span>
                                <span class="text-primary-200 font-medium">USDT</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction Form -->
                <div class="lg:col-span-2 bg-white dark:bg-card-dark rounded-2xl shadow-soft dark:shadow-soft-dark p-6 border border-gray-100 dark:border-gray-800">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-5">@lang('general.new_transaction')</h2>

                    <form action="{{ route('admin.future.asset.transaction') }}" method="post">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Type Section (Modern Toggle/Pills) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.type')</label>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach([1 => __('general.deposit'), 2 => __('general.withdraw')] as $value => $label)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="type" value="{{ $value }}" class="peer sr-only" {{ old('type', 1) == $value ? 'checked' : '' }}>
                                            <div class="text-center px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-400 font-medium text-sm transition-all peer-checked:bg-primary-50 peer-checked:border-primary-500 peer-checked:text-primary-700 dark:peer-checked:bg-primary-900/30 dark:peer-checked:border-primary-500 dark:peer-checked:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                {{ $label }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('type') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Amount Section -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.amount')</label>
                                <div class="relative">
                                    <input type="number" step="any" name="amount" id="amount" value="{{ old('amount') }}"
                                           class="w-full pl-4 pr-16 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white transition-shadow"
                                           placeholder="0.00">
                                    <div class="absolute inset-y-0 end-2 flex items-center pr-4 pointer-events-none">
                                        <span class="text-gray-400 dark:text-gray-500 font-medium text-sm">USDT</span>
                                    </div>
                                </div>
                                @error('amount') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror

                                <!-- Preset amounts -->
                                <div class="flex flex-wrap gap-2 mt-3">
                                    @foreach([100, 150, 200, 250] as $preset)
                                        <button type="button"
                                                class="px-3 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-600 hover:bg-primary-100 hover:text-primary-700 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors amount-btn"
                                                data-amount="{{ $preset }}">
                                            +{{ number_format($preset) }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.description') <span class="text-gray-400 font-normal text-xs">(Optional)</span></label>
                            <textarea id="description" name="description" rows="2" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-700 dark:text-white transition-shadow resize-none" placeholder="@lang('general.description')...">{{ old('description') }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold rounded-xl bg-primary-600 text-white hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-800 transition-all shadow-sm">
                                @lang('general.save')
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Transaction History Table -->
            <div class="bg-white dark:bg-card-dark rounded-2xl shadow-soft dark:shadow-soft-dark border border-gray-100 dark:border-gray-800 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">@lang('general.transaction_history')</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            <th class="px-6 py-4 font-semibold">@lang('general.id')</th>
                            <th class="px-6 py-4 font-semibold">@lang('general.amount')</th>
                            <th class="px-6 py-4 font-semibold">@lang('general.type')</th>
                            <th class="px-6 py-4 font-semibold">@lang('general.date')</th>
                            <th class="px-6 py-4 font-semibold">@lang('general.description')</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm text-gray-700 dark:text-gray-300">
                        @forelse( $transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">
                                    #{{ $transaction?->id ?? '' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold">
                                    {{ toCurrency($transaction?->amount ?? 0) }} <span class="text-xs text-gray-400 font-normal">USDT</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {!! $transaction?->getType() ?? '' !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    {{ $transaction?->getCreatedAt() ?? '' }}
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate text-gray-500 dark:text-gray-400" title="{{ $transaction?->description ?? '' }}">
                                    {{ $transaction?->description ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <span class="text-base font-medium">@lang('general.not_found')</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-50/30 dark:bg-gray-800/30">
                        {{ $transactions->withQueryString()->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>

        </div>
    </main>

    <!-- JS for preset amounts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('amount');
            const amountBtns = document.querySelectorAll('.amount-btn');

            amountBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    amountInput.value = this.getAttribute('data-amount');
                    amountInput.focus();
                });
            });
        });
    </script>
@endsection
