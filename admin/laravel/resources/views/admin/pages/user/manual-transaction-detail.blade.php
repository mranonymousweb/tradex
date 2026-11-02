@extends('admin.layout.master')

@section('title', trans('general.manual_transaction_details') . ' #' . $transaction->id)

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                    @lang('general.manual_transaction_details') #{{ $transaction->id }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $user->getFullInfo() }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.user.details.manual.transaction', $user) }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 shadow-sm transition-all dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.id')</dt>
                            <dd class="text-base text-gray-900 dark:text-white">{{ $transaction->id }}</dd>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.user')</dt>
                            <dd class="text-base text-gray-900 dark:text-white">
                                <a href="{{ route('admin.user.edit', $user) }}" class="text-primary-600 hover:underline">
                                    {{ $user->getFullInfo() }}
                                </a>
                            </dd>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.currency')</dt>
                            <dd class="text-base text-gray-900 dark:text-white">
                                <x-admin.currency :currency="$transaction->currency"/>
                            </dd>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.amount')</dt>
                            <dd class="text-base font-mono {{ $transaction->getCssClass() }}">{{ toCurrency($transaction->amount) }}</dd>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.final_amount')</dt>
                            <dd class="text-base font-mono text-gray-900 dark:text-white">{{ toCurrency($transaction->final_amount) }}</dd>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.type')</dt>
                            <dd class="text-base {{ $transaction->getCssClass() }}">{{ \App\Models\UserTransaction::getType($transaction->type) }}</dd>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.status')</dt>
                            <dd class="text-base text-gray-900 dark:text-white">{{ \App\Models\UserTransaction::getStatus($transaction->status) }}</dd>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.txt_id')</dt>
                            <dd class="text-base font-mono text-gray-900 dark:text-white break-all">{{ $transaction->txt_id }}</dd>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.admin')</dt>
                            <dd class="text-base text-gray-900 dark:text-white">{{ $transaction->admin?->getFullInfo() ?? '-' }}</dd>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.created_at')</dt>
                            <dd class="text-base text-gray-900 dark:text-white">{{ $transaction->getCreatedAt() }}</dd>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.asset_type')</dt>
                            <dd class="text-base text-gray-900 dark:text-white">{{ $transaction->getAssetKeyLabel() }}</dd>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.old_balance')</dt>
                            <dd class="text-base font-mono text-gray-900 dark:text-white">
                                {{ filled($transaction->old_balance) ? toCurrency($transaction->old_balance) : '-' }}
                            </dd>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.new_balance')</dt>
                            <dd class="text-base font-mono text-gray-900 dark:text-white">
                                {{ filled($transaction->new_balance) ? toCurrency($transaction->new_balance) : '-' }}
                            </dd>
                        </div>

                        <div class="md:col-span-2 py-3 border-b border-gray-200 dark:border-gray-700">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">@lang('general.description')</dt>
                            <dd class="text-base text-gray-900 dark:text-white whitespace-pre-wrap">{{ $transaction->description }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <aside class="lg:col-span-1">
                @include('admin.pages.admin.side')
            </aside>
        </div>
    </main>
@endsection
