@extends('admin.layout.master')

@section('title', trans('general.transaction_details'))


@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.transaction_details')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.reports.transaction')}}"
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

                <!-- Currency -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.currency') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ $transaction->currency->symbol ?? '-' }}
                    </dd>
                </div>

                <!-- amount -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.amount') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ toCurrency($transaction->final_amount) ?? '-' }}
                    </dd>
                </div>


                <!-- bank -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.bank') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ $transaction->bank->name ?? '-' }}
                    </dd>
                </div>

                <!-- owner -->

                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.owner') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ $transaction->transactionable->card_owner ?? '-' }}
                    </dd>
                </div>

                <!-- Sheba -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.sheba') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ $transaction->sheba ?? '-' }}
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
               @if($file)
                    <!-- Transaction Receipt Image -->
                    <div class="mt-6 flex flex-col items-start">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                            {{ trans('general.transaction_receipt') }}
                        </dt>
                        <div class="block w-[280px] h-[200px]">
                            <img src="{{ asset($file->path) }}" onclick="showFullImage('{{ asset($file->path) }}')"
                                 class="w-full h-full rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-300 cursor-pointer">
                        </div>
                    </div>
               @endif


            </dl>


            @if($transaction->status == \App\Models\UserTransaction::STATUS_WAITING)
                <div class="mt-6 flex justify-start gap-5">
                    <!-- Approve manual Confirm Button -->
                    <button type="button"
                            class="modal-trigger inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-green-500 text-white hover:bg-green-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-green-400"
                            data-modal-target="manual_confirm">
                        <i class="fa fa-check mr-2"></i> @lang('general.manual_confirm')
                    </button>


                    <!-- Approve Supplier Confirm Button -->
                    <button type="button"
                            class="modal-trigger inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-blue-500 text-white hover:bg-green-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-green-400"
                            data-modal-target="supplier_confirm">
                        <i class="fa fa-check mr-2"></i> @lang('general.supplier_confirm')
                    </button>

                    <!-- Reject Button -->
                    <button type="button"
                            class="modal-trigger inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-red-500 text-white hover:bg-red-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-red-400"
                            data-modal-target="rejectModal">
                        <i class="fa fa-times mr-2"></i> @lang('general.reject')
                    </button>
                </div>
            @endif

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

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">@lang('general.confirm')</h3>
            <form action="{{route('admin.reports.transaction.update',$transaction->id)}}" method="POST">
                @csrf
                <input type="hidden" name="status" value="{{\App\Models\UserTransaction::STATUS_CONFIRMED}}">
                <input type="hidden" name="opened_modal" value="manual_confirm">
                <input type="text" name="txt_id" value="{{old('txt_id')}}"
                       required
                       oninvalid="this.setCustomValidity('@lang('general.txt_id_required')')"
                       oninput="setCustomValidity('')"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="@lang('general.txt_id')">
                @error('txt_id')
                <span class="text-danger">{{$message}}</span>
                @enderror
                <br><br>
                <input type="text" name="amount" value="{{old('amount')}}"
                       required
                       oninvalid="this.setCustomValidity('@lang('general.amount_required')')"
                       oninput="setCustomValidity('')"
                       class="separator w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="@lang('general.amount')">
                @error('amount')
                <span class="text-danger">{{$message}}</span>
                @enderror

                <br><br>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    @lang('general.code')
                </label>
                <input type="text" name="code" value="{{old('code')}}"
                       required
                       oninvalid="this.setCustomValidity('@lang('general.code_required')')"
                       oninput="setCustomValidity('')"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="@lang('general.code')">
                @error('code')
                <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xs text-gray-500 dark:text-gray-400"></p>
                    <button
                        type="button"
                        class="text-xs font-medium text-primary dark:text-primary-dark hover:underline focus:outline-none resend-button"
                        data-timeleft="{{ $admin->getTokenTimeLeftInSecond(\App\Models\OtpCode::SECTION_WITHDRAW) }}"
                        data-url="{{ route('admin.otp.send',[$admin->id , 'section'=> \App\Models\OtpCode::SECTION_WITHDRAW]) }}"
                    >
                        <span class="button_text">@lang('auth.resend')</span>
                        <span class="timer hidden"></span>
                        <span
                            class="spinner hidden w-4 h-4 border-2 border-t-transparent border-primary dark:border-primary-dark rounded-full animate-spin"></span>
                    </button>
                </div>
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

    <!-- Approve Supplier Confirm Modal -->
    <div
        class="modal fixed inset-0 z-50 flex items-center justify-center bg-gray-200/40 dark:bg-gray-900/40 backdrop-blur-md hidden"
        data-modal-id="supplier_confirm">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6 relative">

            <button type="button" data-modal-close
                    class="absolute top-3 left-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">@lang('general.supplier_confirm')</h3>
            <form action="{{route('admin.reports.transaction.update',$transaction->id)}}" method="POST">
                @csrf
                <input type="hidden" name="status" value="{{\App\Models\UserTransaction::STATUS_CONFIRMED}}">
                <input type="hidden" name="supplier" value="{{true}}">
                <input type="hidden" name="opened_modal" value="supplier_confirm">

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    @lang('general.code')
                </label>
                <input type="text" name="code" value="{{old('code')}}"
                       required
                       oninvalid="this.setCustomValidity('@lang('general.code_required')')"
                       oninput="setCustomValidity('')"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="@lang('general.code')">
                @error('code')
                <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xs text-gray-500 dark:text-gray-400"></p>
                    <button
                        type="button"
                        class="text-xs font-medium text-primary dark:text-primary-dark hover:underline focus:outline-none resend-button"
                        data-timeleft="{{ $admin->getTokenTimeLeftInSecond(\App\Models\OtpCode::SECTION_WITHDRAW) }}"
                        data-url="{{ route('admin.otp.send',[$admin->id , 'section'=> \App\Models\OtpCode::SECTION_WITHDRAW]) }}"
                    >
                        <span class="button_text">@lang('auth.resend')</span>
                        <span class="timer hidden"></span>
                        <span
                            class="spinner hidden w-4 h-4 border-2 border-t-transparent border-primary dark:border-primary-dark rounded-full animate-spin"></span>
                    </button>
                </div>


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
    <!-- Reject Modal -->
    <div
        class="modal fixed inset-0 z-50 flex items-center justify-center bg-gray-200/40 dark:bg-gray-900/40 backdrop-blur-md hidden"
        data-modal-id="rejectModal">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6 relative">

            <button type="button" data-modal-close
                    class="absolute top-3 left-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">@lang('general.reject_transaction')</h3>
            <form action="{{route('admin.reports.transaction.update',$transaction->id)}}" method="POST">
                @csrf
                <input type="hidden" name="status" value="{{\App\Models\UserTransaction::STATUS_REJECT}}">
                <input type="hidden" name="opened_modal" value="rejectModal">
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    @lang('general.description')
                </label>
                <textarea name="description"
                          required
                          oninvalid="this.setCustomValidity('@lang('general.description_required')')"
                          oninput="setCustomValidity('')"
                          class="w-full h-40 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                          placeholder="@lang('general.description')">{{old('description')}}</textarea>
                @error('description')
                <span class="text-danger">{{$message}}</span>
                @enderror


                <br>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    @lang('general.code')
                </label>
                <input type="text" name="code" value="{{old('code')}}"
                       required
                       oninvalid="this.setCustomValidity('@lang('general.code_required')')"
                       oninput="setCustomValidity('')"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="@lang('general.code')">
                @error('code')
                <span class="text-danger">{{$message}}</span>
                @enderror
                <div class="flex items-center justify-between mt-2">
                    <p class="text-xs text-gray-500 dark:text-gray-400"></p>
                    <button
                        type="button"
                        class="text-xs font-medium text-primary dark:text-primary-dark hover:underline focus:outline-none resend-button"
                        data-timeleft="{{ $admin->getTokenTimeLeftInSecond(\App\Models\OtpCode::SECTION_WITHDRAW) }}"
                        data-url="{{ route('admin.otp.send',[$admin->id , 'section'=> \App\Models\OtpCode::SECTION_WITHDRAW]) }}"
                    >
                        <span class="button_text">@lang('auth.resend')</span>
                        <span class="timer hidden"></span>
                        <span
                            class="spinner hidden w-4 h-4 border-2 border-t-transparent border-primary dark:border-primary-dark rounded-full animate-spin"></span>
                    </button>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" data-modal-close
                            class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200">
                        @lang('general.cancel_button')
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white">
                        @lang('general.confirm')
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{asset('assets/js/dependencies/TimerButton.js')}}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.resend-button').forEach(button => {
                new ResendButton(button);
            });
        });
    </script>
    @if(session('open_modal') && $transaction->status == \App\Models\UserTransaction::STATUS_WAITING)
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modalId = "{{ session('open_modal') }}";
                const modal = document.querySelector(`[data-modal-id="${modalId}"]`);

                if (modal) {
                    modal.classList.remove('hidden');
                }
            });
        </script>
    @endif

@endsection


