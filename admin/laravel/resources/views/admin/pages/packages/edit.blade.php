@extends('admin.layout.master')

@section('title', trans('general.edit_package'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.edit_package')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.packages.index')}}"
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
        <!-- Currency form -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.packages.update',$package) }}" method="post"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-3 gap-5">
                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.name')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{old('name',$package->name)}}"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.package_name_required')')"
                                       oninput="setCustomValidity('')"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.name')">
                                @error('name')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.withdrawal')
                                    <span class="text-red-500">*</span></label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" value="0">
                                    <input type="checkbox" class="sr-only peer" name="withdrawal" value="1"
                                           @if($package->withdrawal) checked @endif>
                                    <div
                                        class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-green-600 transition-colors"></div>
                                    <div
                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-full"></div>
                                </label>
                                @error('withdrawal')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.deposit_allowed')
                                    <span class="text-red-500">*</span></label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" value="0">
                                    <input type="checkbox" name="deposit" class="sr-only peer" value="1"
                                           @if($package->deposit) checked @endif>
                                    <div
                                        class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-green-600 transition-colors"></div>
                                    <div
                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-full"></div>
                                </label>
                                @error('deposit')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>


                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.internal_transfer_fee')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="internal_transfer_fee"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.internal_transfer_fee')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('internal_transfer_fee', $package->internal_transfer_fee)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('internal_transfer_fee') <span
                                    class="text-danger">{{ $message }}</span> @enderror
                            </div>


                            @if($package->id > 3)
                                <div class="col-span-3 md:col-span-1">
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.status')
                                        <span class="text-red-500">*</span></label>
                                    <select name="status"
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        @foreach(\App\Models\Package::statuses()   as $key => $status )
                                            <option
                                                value="{{ $key }}" @selected(old('status',$package->status) == $key)>{!! $status !!}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <span class="text-red-500">{{$message}}</span>
                                    @enderror

                                </div>
                            @endif
                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.marketing_profit')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="marketing_profit"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.marketing_profit_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('marketing_profit', $package->marketing_profit)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('marketing_profit') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.withdrawal_min_fiat_by_usdt')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="withdrawal_min_fiat"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.withdrawal_min_fiat_by_usdt_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('withdrawal_min_fiat', $package->withdrawal_min_fiat)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('withdrawal_min_fiat') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.fee_fiat')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="fee_fiat"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.fee_fiat_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('fee_fiat', $package->fee_fiat)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('fee_fiat') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.deposit_max_fiat')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="deposit_max_fiat"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.deposit_max_fiat_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('deposit_max_fiat', $package->deposit_max_fiat)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('deposit_max_fiat') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.deposit_min_fiat_by_usdt')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="deposit_min_fiat"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.deposit_max_fiat_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('deposit_min_fiat', $package->deposit_min_fiat)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('deposit_min_fiat') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.currency_maker_fee')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="currency_maker_fee"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.currency_maker_fee_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('currency_maker_fee', $package->currency_maker_fee)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('currency_maker_fee') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.currency_maker_fee_max')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="currency_maker_fee_max"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.currency_maker_fee_max_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('currency_maker_fee_max', $package->currency_maker_fee_max)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('currency_maker_fee_max') <span
                                    class="text-danger">{{ $message }}</span> @enderror
                            </div>



                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.currency_taker_fee')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="currency_taker_fee"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.currency_taker_fee_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('currency_taker_fee', $package->currency_taker_fee)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('currency_taker_fee') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.currency_taker_fee_max')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="currency_taker_fee_max"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.currency_taker_fee_max_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('currency_taker_fee_max', $package->currency_taker_fee_max)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('currency_taker_fee_max') <span
                                    class="text-danger">{{ $message }}</span> @enderror
                            </div>


                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.fiat_taker_fee') (%)
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="fiat_taker_fee"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.fiat_taker_fee_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('fiat_taker_fee', $package->fiat_taker_fee)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('fiat_taker_fee') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.fiat_taker_fee_max_by_usdt')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="fiat_taker_fee_max"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.fiat_taker_fee_max_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('fiat_taker_fee_max', $package->fiat_taker_fee_max)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('fiat_taker_fee_max') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.fiat_maker_fee') (%)
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="fiat_maker_fee"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.fiat_maker_fee_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('fiat_maker_fee', $package->fiat_maker_fee)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('fiat_maker_fee') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.fiat_maker_fee_max_by_usdt')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="fiat_maker_fee_max"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.fiat_maker_fee_max_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('fiat_maker_fee_max', $package->fiat_maker_fee_max)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('fiat_maker_fee_max') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>



                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.max_withdraw_by_usdt')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="withdrawal_max"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.currency_taker_fee_max_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('withdrawal_max', $package->withdrawal_max)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('withdrawal_max') <span
                                    class="text-danger">{{ $message }}</span> @enderror
                            </div>


                            @if($package->id > 3)
                                <div class="col-span-3 md:col-span-1">
                                    <label
                                        class="separator block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                        @lang('general.amount_to_grant')
                                        <span class="text-red-500">*</span>
                                        <button type="button"
                                                onclick="mboxNotice('{{trans('general.amount_to_grant_description')}}')"
                                                class="text-yellow-500 hover:text-yellow-600 dark:text-yellow-400"
                                                title="@lang('general.guide')">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="w-5 h-5"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M12 9v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                                            </svg>
                                        </button>

                                    </label>

                                    <input type="text" name="amount_to_grant"
                                           required
                                           oninvalid="this.setCustomValidity('@lang('general.amount_to_grant_required')')"
                                           oninput="setCustomValidity('')"
                                           value="{{ toCurrency(old('amount_to_grant', $package->amount_to_grant)) }}"
                                           class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @error('amount_to_grant')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.currency_min_deposit')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="currency_min_deposit"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.currency_min_deposit_required')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('currency_min_deposit', $package->currency_min_deposit)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('currency_min_deposit')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.currency_min_withdrawal')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="currency_min_withdrawal"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.currency_min_withdrawalـrequired')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('currency_min_withdrawal', $package->currency_min_withdrawal)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('currency_min_withdrawal')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.withdraw_first_time_limit')
                                    <span class="text-red-500">*</span></label>
                                <input type="number" name="withdraw_first_time_limit"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.withdraw_first_time_limit')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('withdraw_first_time_limit', $package->withdraw_first_time_limit)) }}"
                                       class="separator w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('withdraw_first_time_limit')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-span-3 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.withdraw_time_limit')
                                    <span class="text-red-500">*</span></label>
                                <input type="number" name="withdraw_time_limit"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.withdraw_time_limit')')"
                                       oninput="setCustomValidity('')"
                                       value="{{ toCurrency(old('withdraw_time_limit', $package->withdraw_time_limit)) }}"
                                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                @error('withdraw_time_limit')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                    class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                @lang('general.save')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

@endsection

