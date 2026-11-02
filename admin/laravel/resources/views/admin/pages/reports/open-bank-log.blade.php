@extends('admin.layout.master')

@section('title', trans('general.open_bank_transactions'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.open_bank_transactions')</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.excel.openBank', request()->query()) }}"
                    class="text-white inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-gray-900 dark:text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 16.5V3m0 13.5 4.5-4.5M12 16.5l-4.5-4.5M3.75 19.5h16.5" />
                    </svg>
                    <span>@lang('general.download_excel')</span>
                </a>
            </div>
        </div>
        @include('admin.pages.filter', [
            'filters' => $filters,
            'back' => route('admin.reports.open.bank.logs'),
        ])
        <div
            class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="dark:bg-gray-800 dark:border-gray-700 w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="">
                        <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                            <th class="table-header py-4 font-medium">@lang('general.id')</th>
                            <th class="table-header py-4 font-medium">@lang('general.user')
                            <th class="table-header py-4 font-medium">@lang('general.txt_id')</th>
                            <th class="table-header py-4 font-medium">@lang('general.source_address')</th>
                            <th class="table-header py-4 font-medium">@lang('general.amount')</th>
                            <th class="table-header py-4 font-medium">@lang('general.created_at') </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                        @forelse($transactions as $transaction)
                            <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    <span>{{ $transaction->id }}</span>
                                </td>
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    @if ($transaction->user)
                                        <a
                                            href="{{ route('admin.user.edit', $transaction?->user) }}">{{ $transaction?->user->getFullInfo() ?? '' }}</a>
                                    @else
                                        <button type="button" data-transaction_id="{{ $transaction->id }}"
                                            class="modal-trigger w-32 px-4 py-2 text-sm font-medium rounded-lg bg-green-600 text-white hover:bg-green-700 shadow-sm dark:bg-primary-500 dark:hover:bg-green-600"
                                            data-modal-target="assignUser">
                                            @lang('general.assign')
                                        </button>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    <button type="button" data-extra="{{ $transaction->transaction_trace }}"
                                        class="extra-data w-32  px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                        @lang('general.see')
                                    </button>
                                </td>
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    <button type="button" data-extra="{{ $transaction->source_account }}"
                                        class="extra-data w-32  px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                        @lang('general.see')
                                    </button>
                                </td>
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    <span>{{ toCurrency($transaction->amount) }}</span>
                                </td>
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    <span>{{ $transaction->getCreateAt(true) }}</span>
                                </td>

                            </tr>
                        @empty
                            <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                                <td class="whitespace-nowrap" colspan="6">
                                    <span> @lang('general.not_found')</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            {{ $transactions->links('pagination::tailwind') }}
        </div>
    </main>
@endsection


@section('modal')
    <div class="modal fixed inset-0 z-50 flex items-center justify-center bg-gray-200/40 dark:bg-gray-900/40 backdrop-blur-md hidden"
        data-modal-id="assignUser">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6 relative">

            <button type="button" data-modal-close
                class="absolute top-3 left-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                @lang('general.approve_transaction')
            </h3>

            <form action="{{ route('admin.reports.open.bank.log.update') }}" method="POST">
                @csrf
                <input id="log_id" type="hidden" name="log_id" value="">

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    @lang('general.user')
                </label>
                <select name="user_id" required oninvalid="this.setCustomValidity('@lang('general.user_required')')"
                    oninput="setCustomValidity('')"
                    class="select2 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach ($users as $key => $value)
                        <option @selected(in_array($key, old('user_id') ?? [])) value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                @error('user_id')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" data-modal-close
                        class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200">
                        @lang('general.cancel_button')
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white">
                        @lang('general.confirm')
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection



@section('scripts')
    <script>
        document.querySelectorAll('.extra-data').forEach((button) => {
            button.addEventListener('click', (event) => {
                mboxNotice(event.currentTarget.dataset.extra)
            })
        })
        let log_btn = document.getElementById('log_id')
        document.querySelectorAll('.modal-trigger').forEach((button) => {
            button.addEventListener('click', (event) => {
                let btn = event.currentTarget;
                log_btn.value = btn.dataset.transaction_id
            })
        })
    </script>
@endsection
