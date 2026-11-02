@extends('admin.layout.master')

@section('title', trans('general.wallets'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.wallets')</h2>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.reports.excel.wallet') }}"
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
        @include('admin.pages.filter', ['filters' => $filters, 'back' => route('admin.reports.wallets')])
        <div
            class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="dark:bg-gray-800 dark:border-gray-700 w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="">
                        <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                            <th class="table-header py-4 font-medium">@lang('general.user')
                            <th class="table-header py-4 font-medium">@lang('general.address')</th>
                            <th class="table-header py-4 font-medium">@lang('general.network')</th>
                            <th class="table-header py-4 font-medium">@lang('general.currency')</th>
                            <th class="table-header py-4 font-medium">@lang('general.tag')</th>
                            <th class="table-header py-4 font-medium">@lang('general.wallet_supplier')</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                        @forelse($wallets as $wallet)
                            @if ($wallet?->wallets?->count())
                                @foreach ($wallet->wallets as $userWallet)
                                    <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                                        <td class="whitespace-nowrap max-xl:min-w-70">
                                            <a
                                                href="{{ route('admin.user.edit', $userWallet?->user) }}">{{ $userWallet?->user->getFullInfo() ?? '' }}</a>
                                        </td>
                                        <td class="whitespace-nowrap max-xl:min-w-70">
                                            <button type="button" data-extra="{{ $userWallet->address }}"
                                                class="extra-data w-32  px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                                @lang('general.see')
                                            </button>
                                        </td>
                                        <td class="whitespace-nowrap max-xl:min-w-70">
                                            {{ $userWallet->network->symbol }}
                                        </td>
                                        <td class="whitespace-nowrap max-xl:min-w-70">
                                            {{ $userWallet->symbol }}
                                        </td>
                                        <td class="whitespace-nowrap max-xl:min-w-70">
                                            {{ $userWallet->tag }}
                                        </td>
                                        <td class="whitespace-nowrap max-xl:min-w-70">
                                            {{ \App\Models\WalletRepository::getSuppliers($wallet->supplier) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        {{ '--' }}
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        <button type="button" data-extra="{{ $wallet->address }}"
                                            class="extra-data w-32  px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                            @lang('general.see')
                                        </button>
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        {{ $wallet->symbol }}
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        {{ '--' }}
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        {{ $wallet->tag }}
                                    </td>
                                    <td class="whitespace-nowrap max-xl:min-w-70">
                                        {{ \App\Models\WalletRepository::getSuppliers($wallet->supplier) }}
                                    </td>
                                </tr>
                            @endif
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
            {{ $wallets->links('pagination::tailwind') }}
        </div>
    </main>
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
