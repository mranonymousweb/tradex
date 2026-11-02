@extends('admin.layout.master')

@section('title', trans('general.wattles_management'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-dark dark:text-light">@lang('general.wattles_management')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.setting.currency.management')}}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-light hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>
        @include('admin.pages.filter',['filters' => $filters,'back'=>route('admin.wallet.repository.index')])
        <div
                class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="dark:bg-gray-800 dark:border-gray-700 w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="">
                    <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                        <th class="table-header py-4 font-medium">@lang('general.network')</th>
                        <th class="table-header py-4 font-medium">@lang('general.wallet_adders')</th>
                        <th class="table-header py-4 font-medium">@lang('general.tag')</th>
                        <th class="table-header py-4 font-medium">@lang('general.wallet_supplier')</th>
                        <th class="table-header py-4 font-medium">@lang('general.type')</th>
                        <th class="table-header py-4 font-medium">@lang('general.operation')</th>
                    </tr>
                    </thead>
                    <tbody
                            class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                    @forelse($networks as $network)

                        @php
                            /**
                             * Current wallet provider.
                             */
                            $walletProvider = $network->providers->first();

                            /**
                             * Supplier network configuration for current provider.
                             */
                            $supplierNetwork = $network->suppliers->where('supplier_id', optional($walletProvider)->supplier_id)->first() ?? $network;

                            /**
                             * Current wallet supplier.
                             */
                            $walletSupplier = optional($walletProvider?->supplier);

                            /**
                             * Does this supplier require dedicated wallets?
                             */
                            $isDedicatedSupplier = in_array($walletSupplier->name,\App\Models\WalletRepository::dedicateSuppliers());
                            $isCregisTagNetwork = \App\Services\SupplierExchange\Suppliers\Cregis::isTagNetwork($network->symbol);
                            $showManualAddress = $isCregisTagNetwork || ! ($isDedicatedSupplier && ! $supplierNetwork?->has_tag);

                            $wallet = $network->wallets->where('supplier_id',$walletSupplier->id)->first();
                        @endphp

                        <form action="{{ route('admin.wallet.repository.update',$network) }}"
                              method="POST"
                              enctype="multipart/form-data">

                            @csrf

                            <input type="hidden"
                                   name="provider_id"
                                   value="{{ $walletProvider?->id }}">

                            <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16">

                                {{-- Network --}}
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    <div class="flex flex-col">
                                      <span>
                                     ({{ $network->symbol }})
                                   </span>
                                        @if($supplierNetwork)
                                            <span
                                                    class="text-xs text-primary-500 mt-1">{{ $supplierNetwork->symbol }}</span>
                                        @endif
                                        <a href="{{ route('admin.reports.wallets',['network'=>$network->symbol]) }}"
                                           class="text-primary-500 hover:text-primary-700 text-xs underline mt-1">

                                            @lang('general.wallets')

                                        </a>

                                    </div>

                                </td>

                                {{-- Wallet Address --}}
                                <td class="whitespace-nowrap max-xl:min-w-70 items-center p-2">

                                    @if(! $showManualAddress)

                                        <input
                                                type="file"
                                                name="address"
                                                data-id="{{ $network->id }}"
                                                data-tag="{{ $supplierNetwork?->has_tag ? 1 : 0 }}"
                                                accept=".txt"
                                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">

                                    @else

                                        <input
                                                required
                                                type="text"
                                                name="address"
                                                data-id="{{ $network->id }}"
                                                data-tag="{{ $supplierNetwork?->has_tag ? 1 : 0 }}"
                                                value="{{ old('address',$wallet?->address) }}"
                                                class="wallet-address w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                placeholder="@lang('general.wallet_adders')">

                                    @endif

                                </td>

                                {{-- Tag --}}
                                <td class="whitespace-nowrap max-xl:min-w-70 items-center p-2">
                                    <div class="tag-wrapper" data-id="{{ $network->id }}" data-has-tag="{{ $supplierNetwork?->has_tag ? 1 : 0 }}">

                                        @if($isCregisTagNetwork)

                                            <span class="auto-note ml-2 text-sm text-green-500">
                        @lang('setting.automatic')
                    </span>

                                        @elseif($supplierNetwork?->has_tag && $wallet?->type == config('constants.wallet_type_share'))

                                            <input
                                                    type="text"
                                                    name="tag"
                                                    value="{{ old('tag',$wallet?->tag) }}"
                                                    data-id="{{ $network->id }}"
                                                    class="w-42 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                    placeholder="@lang('general.tag')">

                                        @elseif($supplierNetwork?->has_tag && $wallet?->type == config('constants.wallet_type_dedicate'))

                                            <span class="auto-note ml-2 text-sm text-green-500">
                        @lang('setting.automatic')
                    </span>

                                        @else

                                            ---

                                    @endif

                                </td>

                                {{-- Wallet Provider --}}
                                <td class="whitespace-nowrap max-xl:min-w-70 items-center p-2">

                                    <select
                                            data-id="{{ $network->id }}"
                                            name="supplier"
                                            data-id="{{ $network->id }}"
                                            data-has-tag="{{ $supplierNetwork?->has_tag ? 1 : 0 }}"
                                            class="supplier w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            data-network-symbol="{{ $network->symbol }}">

                                        @if(!$walletSupplier)
                                            <option value="">
                                                -----
                                            </option>
                                        @endif

                                        @foreach($suppliers as $supplier)

                                            <option
                                                    value="{{ $supplier->id }}"
                                                    data-name="{{ $supplier->name }}"
                                                    data-dedicated="{{ in_array($supplier->name,\App\Models\WalletRepository::dedicateSuppliers()) ? 1 : 0 }}"
                                                    @selected(old('supplier',$walletSupplier->id)==$supplier->id)>

                                                {{ $supplier->name }}

                                            </option>

                                        @endforeach

                                    </select>

                                </td>

                                {{-- Wallet Type --}}
                                <td class="whitespace-nowrap max-xl:min-w-70 items-center p-2">

                                    {{ $wallet?->getType() }}

                                </td>

                                {{-- Action --}}
                                <td class="whitespace-nowrap max-xl:min-w-70">

                                    <button
                                            type="submit"
                                            class="px-3 py-1 border rounded-lg text-sm font-medium text-white bg-primary-600 hover:bg-primary-500">

                                        @lang('general.save')

                                    </button>

                                </td>

                            </tr>

                        </form>

                    @empty

                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16">

                            <td colspan="6">

                                @lang('general.not_found')

                            </td>

                        </tr>

                    @endforelse

                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            {{ $networks->withQueryString()->links('pagination::tailwind') }}
        </div>

    </main>
@endsection
@section('scripts')
    <script>

        document.querySelectorAll('.supplier').forEach(select => {

            select.addEventListener('change', function () {

                const id = this.dataset.id;

                const option = this.options[this.selectedIndex];

                const supplierName = option.dataset.name;
                const isDedicated = option.dataset.dedicated === '1';

                const hasTag = this.dataset.hasTag === '1';

                const networkSymbol = (this.dataset.networkSymbol || '').toUpperCase();
                const isCregisTagNetwork = supplierName === 'cregis'
                    && ['TON', 'XRP'].includes(networkSymbol);

                const addressInput = document.querySelector(
                    `input[name="address"][data-id="${id}"]`
                );

                const tagWrapper = document.querySelector(
                    `.tag-wrapper[data-id="${id}"]`
                );

                if (!addressInput || !tagWrapper) {
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Address Input
                |--------------------------------------------------------------------------
                */

                if (isDedicated && !hasTag && !isCregisTagNetwork) {

                    addressInput.type = 'file';
                    addressInput.accept = '.txt';
                    addressInput.value = '';

                    if (supplierName === 'cregis') {
                        addressInput.removeAttribute('required');
                    } else {
                        addressInput.setAttribute('required', 'required');
                    }

                } else {

                    addressInput.type = 'text';
                    addressInput.accept = '';
                    addressInput.setAttribute('required', 'required');
                    addressInput.placeholder = '@lang("general.wallet_adders")';

                }

                /*
                |--------------------------------------------------------------------------
                | Tag Section
                |--------------------------------------------------------------------------
                */

                if (isCregisTagNetwork) {

                    tagWrapper.innerHTML = `
                <span class="auto-note text-sm text-green-500">
                    @lang('setting.automatic')
                    </span>
`;
                    return;

                }

                if (!hasTag) {

                    tagWrapper.innerHTML = '<span class="text-gray-400">---</span>';
                    return;

                }

                if (isDedicated) {

                    tagWrapper.innerHTML = `
                <span class="auto-note text-sm text-green-500">
                    @lang('setting.automatic')
                    </span>
`;

                } else {

                    const oldTag =
                        tagWrapper.querySelector('input')?.value ?? '';

                    tagWrapper.innerHTML = `
                <input
                    type="text"
                    name="tag"
                    value="${oldTag}"
                    data-id="${id}"
                    class="wallet-tag w-42 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    placeholder="@lang('general.tag')"
                >
            `;

                }

            });

        });

    </script>
@endsection



