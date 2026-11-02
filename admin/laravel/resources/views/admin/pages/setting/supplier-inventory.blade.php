@extends('admin.layout.master')

@section('title', trans('general.supplier_management'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.supplier_management')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.setting.supplier')}}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow">

            <div class="p-5 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

                <h3 class="font-semibold">
                    @lang('general.wallets')
                </h3>

                <form action="{{ route('admin.setting.supplier.wallet.balance',$supplier) }}" method="POST"
                      class="flex flex-wrap items-end gap-2">
                    @csrf

                    <div class="flex flex-col gap-1">
                        <label class="text-xs text-gray-500 dark:text-gray-400">@lang('general.wallet_id')</label>
                        <input
                            type="text"
                            name="wallet_id"
                            value="{{ old('wallet_id', $savedWalletId) }}"
                            placeholder="@lang('general.enter_wallet_id')"
                            class="w-72 rounded-lg border border-gray-300 dark:border-gray-600
                            bg-white dark:bg-gray-700 px-3 py-2 text-sm
                            focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            required
                        >
                        @error('wallet_id')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        {{ $assets->isEmpty() ? __('general.get_balance') : __('general.refresh') }}
                    </button>
                </form>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="bg-gray-50 dark:bg-gray-700">

                    <tr>

                        <th class="px-6 py-3 text-start">@lang('general.wallet_id')</th>
                        <th class="px-6 py-3 text-center">@lang('general.symbol')</th>
                        <th class="px-6 py-3 text-center">@lang('general.network')</th>
                        <th class="px-6 py-3 text-center">@lang('general.balance')</th>
                        <th class="px-6 py-3 text-end">@lang('general.updated')</th>
                        <th class="px-6 py-3 text-end">@lang('general.operation')</th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach($assets as $asset)

                        <tr class="border-t">

                            <td class="px-6 py-4 text-start">

                                <div class="flex items-center gap-3">

                                    <code class="text-sm">
                                        {{ Str::limit($asset->wallet_id, 40) }}
                                    </code>

                                    <span class="sr-only" data-full-address>{{ $asset->wallet_id }}</span>

                                    <button
                                        type="button"
                                        class="copy-wallet-address text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition"
                                        title="Copy wallet ID"
                                        aria-label="Copy wallet ID"
                                    >
                                        📋
                                    </button>

                                </div>

                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ $asset->symbol ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ strtoupper($asset->networkModel?->symbol ?? $asset->network ?? '—') }}
                            </td>

                            <td class="px-6 py-4 text-center">

                                {{ number_format($asset->balance,8) }}

                            </td>

                            <td class="px-6 py-4 text-end">

                                {{ $asset->updated_at->diffForHumans() }}

                            </td>

                            <td class="px-6 py-4 text-end">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.setting.supplier.wallet.balance', $supplier) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="wallet_id" value="{{ $asset->wallet_id }}">
                                        <button
                                            type="submit"
                                            class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-700">
                                            @lang('general.refresh')
                                        </button>
                                    </form>

                                    <button
                                        type="button"
                                        onclick="mboxDelete('{{ route('admin.setting.supplier.asset.destroy', $asset) }}')"
                                        class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700">
                                        @lang('general.delete')
                                    </button>
                                </div>
                            </td>


                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

        <form id="delete-form" method="POST" style="display:none">
            @csrf
            @method('DELETE')
        </form>

    </main>
@endsection
@section('scripts')
    <script>
        document.querySelectorAll('.copy-wallet-address').forEach((button) => {
            button.addEventListener('click', async () => {
                const address = button.closest('div')?.querySelector('[data-full-address]')?.textContent?.trim();

                if (!address) {
                    return;
                }

                const copy = async () => {
                    if (navigator.clipboard?.writeText) {
                        await navigator.clipboard.writeText(address);
                        return;
                    }

                    const textarea = document.createElement('textarea');
                    textarea.value = address;
                    textarea.setAttribute('readonly', '');
                    textarea.style.position = 'fixed';
                    textarea.style.left = '-9999px';
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                };

                try {
                    await copy();
                    const originalTitle = button.title;
                    button.title = 'Copied!';
                    setTimeout(() => {
                        button.title = originalTitle;
                    }, 2000);
                } catch (error) {
                    console.error('Copy failed:', error);
                }
            });
        });
    </script>
@endsection
