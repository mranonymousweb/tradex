@extends('admin.layout.master')

@section('title', trans('general.swap_detail'))


@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.swap_detail')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.swaps.index')}}"
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
                    <dd class="text-base text-gray-900 dark:text-white">{{ $swap->id }}</dd>
                </div>

                <!-- User -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.user') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        <a href="{{ route('admin.user.edit', $swap->user) }}" class="text-primary-600 hover:underline">
                            {{ $swap->user?->getFullInfo() ?? '-' }}
                        </a>
                    </dd>
                </div>

                <!-- fromCurrencyAmount -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.from_currency_amount') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ toCurrency($swap->from_amount) ?? '' }} ({{ $swap->from_currency_symbol ?? '' }})
                    </dd>
                </div>

                <!-- toCurrencyAmount -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.to_currency_amount') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ toCurrency($swap->to_amount) ?? '' }} ({{ $swap->to_currency_symbol ?? '' }})
                    </dd>
                </div>

                <!-- fromCurrencyPrice -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.from_currency_price') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ toCurrency($swap->from_price)}} ({{ 'USDT' }})
                    </dd>
                </div>


                <!-- toCurrencyPrice -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.to_currency_price') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ toCurrency($swap->to_price)}} ({{ 'USDT' }})
                    </dd>
                </div>

                <!-- to Final -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.to_currency_final_after_fee') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ toCurrency($swap->to_final) ?? '' }} ({{ $swap->to_currency_symbol ?? '' }})
                    </dd>
                </div>


                <!-- Status -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.status') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ \App\Models\Swap::getStatus($swap->status) }}
                    </dd>
                </div>

                <!-- Txt ID -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700  last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.supplier_txt_id') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ $swap->txt_id ?? '-' }}
                    </dd>
                </div>

                <!-- Created At -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.created_at') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ $swap->getCreateAt() }}
                    </dd>
                </div>

                <!-- Fee -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.fee') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ toCurrency($swap->fee) ?? 0 }} ({{ $swap->to_currency_symbol ?? '' }})
                    </dd>
                </div>


                <!-- toAmountBySupplier -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.to_amount_by_supplier') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ toCurrency( $swap->to_amount_by_supplier) ?? '-' }} ({{ $swap->to_currency_symbol ?? '' }})
                    </dd>
                </div>

                <!-- Usdt Price when Swap done -->
                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.usdt_price_transaction') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ toCurrency( $swap?->usdt_price) ?? '-' }}
                    </dd>
                </div>

                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.swap_method')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        @if($swap->swap_metod === \App\Models\Option::SWAP_METHOD_INNER)
                            @lang('general.swap_method_inner')
                        @elseif($swap->swap_metod === \App\Models\Option::SWAP_METHOD_INTERNATIONAL)
                            @lang('general.swap_method_international')
                        @else
                            {{ $swap->swap_metod ?? '-' }}
                        @endif
                    </dd>
                </div>

                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.usdt_irt_sell_price')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ filled($swap->usdt_irt_sell_price) ? toCurrency($swap->usdt_irt_sell_price) : '-' }}
                    </dd>
                </div>

                <div
                    class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.usdt_irt_buy_price')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ filled($swap->usdt_irt_buy_price) ? toCurrency($swap->usdt_irt_buy_price) : '-' }}
                    </dd>
                </div>

                <!-- Description -->
                <div
                    class="flex items-center justify-between py-3 pl-8 border-b border-gray-200 dark:border-gray-700  last:border-b-0">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ trans('general.description') }}</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ $swap->description ?? '-' }}
                    </dd>
                </div>

            </dl>

            @include('admin.partial.supplier-exchange-data', ['record' => $swap])

            @if($swap->status == \App\Models\Swap::STATUS_WAITING || $swap->status == \App\Models\Swap::STATUS_WAITING_BY_SUPPLIER || $swap->status == \App\Models\Swap::STATUS_REJECTED_BY_SUPPLIER )
                <div class="mt-6 flex justify-start gap-5">
                    <!-- Approve manual Confirm Button -->
                    <button type="button"
                            class="modal-trigger inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-green-500 text-white hover:bg-green-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-green-400"
                            data-modal-target="manual_confirm">
                        <i class="fa fa-check mr-2"></i> @lang('general.manual_confirm')
                    </button>


                    @if($swap->to_currency_symbol != \App\Models\Currency::SYMBOL_IRT && $swap->from_currency_symbol != \App\Models\Currency::SYMBOL_IRT)
                        <!-- Approve Supplier Confirm Button -->
                        <button type="button"
                                class="modal-trigger inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-blue-500 text-white hover:bg-green-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-green-400"
                                data-modal-target="supplier_confirm">
                            <i class="fa fa-check mr-2"></i> @lang('general.supplier_confirm')
                        </button>
                    @endif

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
            <form action="{{route('admin.swaps.update',$swap->id)}}" method="POST">
                @csrf
                <input type="hidden" name="status" value="{{\App\Models\Swap::STATUS_ACCEPTED_BY_SUPPLIER}}">
                <input type="hidden" name="supplier" value="{{true}}">
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
            <form action="{{route('admin.swaps.update',$swap->id)}}" method="POST">
                @csrf
                <input type="hidden" name="status" value="{{\App\Models\Swap::STATUS_ACCEPTED_BY_SITE}}">
                <input type="hidden" name="supplier" value="{{false}}">
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    @lang('general.txt_id')
                </label>
                <input type="text" name="txt_id" value="{{old('txt_id')}}"
                       required
                       oninvalid="this.setCustomValidity('@lang('general.txt_id_required')')"
                       oninput="setCustomValidity('')"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="@lang('general.txt_id')">
                @error('txt_id')
                <span class="text-danger">{{$message}}</span>
                @enderror
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
            <form action="{{route('admin.swaps.update',$swap->id)}}" method="POST">
                @csrf
                <input type="hidden" name="status" value="{{\App\Models\Swap::STATUS_REJECTED_BY_SITE}}">
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


