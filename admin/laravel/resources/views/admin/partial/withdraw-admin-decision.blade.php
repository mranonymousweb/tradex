<div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 p-5">
    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">@lang('general.withdraw_admin_decision_title')</h3>
    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">@lang('general.withdraw_admin_decision_hint')</p>

    <div class="flex flex-wrap items-center justify-between gap-4">
        <x-admin.user-profile :user="$withdrawActivityLog->admin" />
        <span class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
            {{ \App\Models\UserTransaction::getStatus($transaction->status) }}
        </span>
    </div>

    <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
        @lang('general.withdraw_admin_decision_at'): {{ $withdrawActivityLog->getCreatedAt() }}
    </p>

    @if($transaction->description && $transaction->status == \App\Models\UserTransaction::STATUS_REJECT)
        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">@lang('general.description')</p>
            <p class="text-sm text-gray-700 dark:text-gray-300">{{ trans($transaction->description) }}</p>
        </div>
    @endif
</div>
