@extends('admin.layout.master')

@section('title', trans('general.manual_transaction') . ' : ' . $user->getFullInfo())

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                    @lang('general.manual_transaction') : {{ $user->getFullInfo() }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">@lang('general.manual_transaction_description')</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.user.edit', $user) }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 shadow-sm transition-all dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-3 space-y-8">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <h3 class="font-bold text-gray-800 dark:text-white">@lang('general.manual_transaction')</h3>
                    </div>

                    <form action="{{ route('admin.user.details.manual.transaction.store', $user) }}" method="POST"
                          class="p-6 space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    @lang('general.currency') <span class="text-red-500">*</span>
                                </label>
                                <select name="currency_id"
                                        class="select2 w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none @error('currency_id') border-red-500 @enderror"
                                        required>
                                    <option value="">@lang('general.select')</option>
                                    @foreach($currencies as $currency)
                                        <option
                                            value="{{ $currency->id }}" @selected(old('currency_id') == $currency->id) > {{ $currency->symbol }}
                                            — {{ $currency->name }} | @lang('general.balance')
                                            : {{ toCurrency($currency->balance) }} </option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    @lang('general.amount') <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="amount"
                                       value="{{ old('amount') }}"
                                       class="separator w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none @error('amount') border-red-500 @enderror"
                                       required>
                                @error('amount')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    @lang('general.type') <span class="text-red-500">*</span>
                                </label>
                                <select name="type"
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none @error('type') border-red-500 @enderror"
                                        required>
                                    <option value="">@lang('general.select')</option>
                                    <option
                                        value="{{ \App\Models\UserTransaction::TYPE_DEPOSIT }}" @selected(old('type') == \App\Models\UserTransaction::TYPE_DEPOSIT)>
                                        @lang('general.deposit')
                                    </option>
                                    <option
                                        value="{{ \App\Models\UserTransaction::TYPE_WITHDRAW }}" @selected(old('type') == \App\Models\UserTransaction::TYPE_WITHDRAW)>
                                        @lang('general.withdraw')
                                    </option>
                                </select>
                                @error('type')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    @lang('general.txt_id') <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="txt_id"
                                       value="{{ old('txt_id') }}"
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none @error('txt_id') border-red-500 @enderror"
                                       required>
                                @error('txt_id')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                @lang('general.description') <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description"
                                      rows="4"
                                      class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none @error('description') border-red-500 @enderror"
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div
                            class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-300 dark:border-yellow-700 rounded-lg p-3 text-sm">
                            @lang('general.manual_transaction_warning')
                        </div>

                        <button type="submit"
                                class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-semibold transition">
                            @lang('general.save')
                        </button>
                    </form>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <h3 class="font-bold text-gray-800 dark:text-white">@lang('general.manual_transactions_list')</h3>
                    </div>

                    <!-- Filter Component -->
                    <div class="mb-6">
                        @include('admin.pages.filter', ['filters' => $filters, 'back' => route('admin.user.details.manual.transaction',$user->id)])
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50/80 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.id')</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.currency')</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.amount')</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.type')</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.admin')</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.created_at')</th>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">@lang('general.operation')</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-6 py-4 text-center whitespace-nowrap">{{ $transaction->id }}</td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <x-admin.currency :currency="$transaction->currency"/>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap font-mono {{ $transaction->getCssClass() }}">
                                        {{ toCurrency($transaction->amount) }}
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="{{ $transaction->getCssClass() }}">
                                            {{ \App\Models\UserTransaction::getType($transaction->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        {{ $transaction->admin?->getFullInfo() ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        {{ $transaction->getCreatedAt() }}
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @can('admin.user.details.manual.transaction.show')
                                            <a href="{{ route('admin.user.details.manual.transaction.show', $transaction) }}"
                                               class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium text-sm transition-colors">
                                                @lang('general.view')
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"
                                        class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 font-medium">
                                        @lang('general.no_data')
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($transactions->hasPages())
                        <div
                            class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/20">
                            {{ $transactions->links('pagination::tailwind') }}
                        </div>
                    @endif
                </div>
            </div>

            <aside class="lg:col-span-1">
                @include('admin.pages.admin.side')
            </aside>
        </div>
    </main>
@endsection
