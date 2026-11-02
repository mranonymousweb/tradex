@extends('admin.layout.master')

@section('title', trans('general.fishing_detail'))


@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.fishing_detail')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.reports.transaction',['status'=>config('constants.transaction.fishing')])}}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                    @lang('master.back')
                </a>
            </div>
        </div>
        <!-- Transaction Details Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 space-x-8">
                <!-- ID -->
                <div
                    class="flex items-center justify-between py-3  border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.id') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ $transaction->id }}</dd>
                </div>

                <!-- User -->
                @if($transaction->user)
                    <div
                        class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.user') }}</dt>
                        <dd class="text-base text-gray-900 dark:text-white">
                            <a href="{{ route('admin.user.edit', $transaction->user) }}"
                               class="text-primary-600 hover:underline">
                                {{ $transaction->user?->getFullInfo() ?? '-' }}
                            </a>
                        </dd>
                    </div>

                @endif


                <!-- Currency -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.currency') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ $transaction->currency->symbol ?? '-' }}
                    </dd>
                </div>
                <!--  amount -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.amount') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ toCurrency($transaction->amount) ?? '-' }}
                    </dd>
                </div>


                <!-- Fee -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.fee') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ toCurrency($transaction->fee) ?? 0 }}
                    </dd>
                </div>

                <!-- final amount -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.final_amount') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ toCurrency($transaction->final_amount) ?? '-' }}
                    </dd>
                </div>

                <!-- Status -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.status') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ \App\Models\UserTransaction::getStatus($transaction->status) }}
                    </dd>
                </div>


                <!-- section -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.section') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ \App\Models\UserTransaction::getSection($transaction->section) }}
                    </dd>
                </div>



                <!-- Type -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.type') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ \App\Models\UserTransaction::getType($transaction->type) }}
                    </dd>
                </div>

                <!-- Txt ID -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700  last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.txt_id') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ $transaction->txt_id ?? '-' }}
                    </dd>
                </div>

                <!-- Created At -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.created_at') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ $transaction->getCreatedAt() }}
                    </dd>
                </div>


                <!-- Description -->
                <div
                    class="flex items-center justify-between py-3 pl-8 border-b border-gray-200 dark:border-gray-700  last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.description') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ isset($transaction?->description) ? trans($transaction->description) : '-' }}
                    </dd>
                </div>

            </dl>


                <div class="mt-6 flex justify-start gap-5">
                    <!-- Approve manual Confirm Button -->
                    <button type="button"
                            class="modal-trigger inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-green-500 text-white hover:bg-green-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-green-400"
                            data-modal-target="manual_confirm">
                        <i class="fa fa-check mr-2"></i> @lang('general.confirm_fishing')
                    </button>
                </div>


        </div>

    </main>
@endsection
@section('modal')
    <!-- Approve manual Confirm Modal -->
    <div
        class="modal fixed inset-0 z-50 flex items-center justify-center bg-gray-200/40 dark:bg-gray-900/40 backdrop-blur-md hidden"
        data-modal-id="manual_confirm">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6 relative">

            <button type="button" data-modal-close
                    class="absolute top-3 left-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">@lang('general.manual_confirm')</h3>
            <form action="{{route('admin.transactions.update',$transaction->id)}}" method="POST">
                @csrf
                <input type="hidden" name="status" value="{{\App\Models\UserTransaction::STATUS_CONFIRMED}}">
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" data-modal-close
                            class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200">
                        @lang('general.cancel_button')
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white">
                        @lang('general.confirm')
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection



