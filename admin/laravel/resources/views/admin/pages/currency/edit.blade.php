@extends('admin.layout.master')

@section('title', trans('general.edit_currency'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <!-- Header Page -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 dark:border-gray-800 pb-5">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">@lang('general.edit_currency')</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $currency->name }} ({{ $currency->symbol }})</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.currency.index') }}"
                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    @lang('master.back')
                </a>
                @if($currency->symbol == \App\Models\Currency::SYMBOL_IRT)
                    <a href="{{route('admin.irt.index')}}"
                       class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.591 1.044c1.522-.878 3.366.966 2.488 2.488a1.724 1.724 0 001.044 2.591c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.044 2.591c.878 1.522-.966 3.366-2.488 2.488a1.724 1.724 0 00-2.591 1.044c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.591-1.044c-1.522.878-3.366-.966-2.488-2.488a1.724 1.724 0 00-1.044-2.591c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.044-2.591c-.878-1.522.966-3.366 2.488-2.488.922.532 2.165.21 2.591-1.044z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        @lang('general.setting')
                    </a>
                @endif
            </div>
        </div>

        <!-- Main Form Container -->
        <div class="space-y-6">
            <!-- Basic Info Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">@lang('general.basic_information')</h3>
                </div>

                <form action="{{ route('admin.currency.update',$currency) }}" method="post" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Symbol -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                @lang('general.symbol') <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="symbol" value="{{old('symbol',$currency->symbol)}}"
                                   class="w-full px-3.5 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all"
                                   placeholder="@lang('general.symbol')">
                            @error('symbol')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{$message}}</p>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                @lang('general.name') <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{old('name',$currency->name)}}"
                                   class="w-full px-3.5 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all"
                                   placeholder="@lang('general.name')">
                            @error('name')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{$message}}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                @lang('general.type') <span class="text-red-500">*</span>
                            </label>
                            <select name="type"
                                    class="w-full px-3.5 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all">
                                @foreach(\App\Models\Currency::getType() as $key=>$value)
                                    <option @selected(old('type', $currency->type ?? null) == $key) value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('type')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{$message}}</p>
                            @enderror
                        </div>

                        <!-- Icon Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">@lang('general.icon')</label>
                            <input type="file" name="icon"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 dark:file:bg-gray-700 dark:file:text-gray-200 border border-gray-300 rounded-lg dark:border-gray-600 p-1 bg-white dark:bg-gray-700">
                            @error('icon')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{$message}}</p>
                            @enderror
                        </div>

                        <!-- Rank -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">@lang('general.rank')</label>
                            <input type="number" name="rank" value="{{old('rank',$currency->rank)}}"
                                   class="w-full px-3.5 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all"
                                   placeholder="@lang('general.rank')">
                            @error('rank')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{$message}}</p>
                            @enderror
                        </div>

                        <!-- Link -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">@lang('general.link')</label>
                            <input type="text" name="link" value="{{old('link',$currency->link)}}"
                                   class="w-full px-3.5 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all">
                            @error('link')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{$message}}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                @lang('general.status') <span class="text-red-500">*</span>
                            </label>
                            <select name="status"
                                    class="w-full max-w-md px-3.5 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all">
                                <option value="" @selected(old('status') === null)>{{ __('general.select') }} ({{ __('general.status') }})</option>
                                @foreach($statues as $key => $status)
                                    <option value="{{ $key }}" @selected(old('status',$currency->status) == $key)>{!! strip_tags($status) !!}</option>
                                @endforeach
                            </select>
                            @error('status')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{$message}}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions Separation Zone -->
                    <div class="mt-6 pt-5 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600 focus:ring-4 focus:ring-primary-500/20 transition-all duration-150">
                            @lang('general.save')
                        </button>
                    </div>
                </form>
            </div>

            {{-- Crypto Configuration Network --}}
            @if($currency->type == config('constants.currency.crypto'))
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">@lang('general.networking_data')</h3>
                    </div>

                    <div class="p-6 space-y-6">
                        @foreach($networks as $network)
                            @php $nc = $networkCurrencies[$network->id] ?? null; @endphp

                            <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 bg-white dark:bg-gray-900 shadow-sm transition-all hover:border-gray-300 dark:hover:border-gray-600">
                                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-gray-700/50">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-gray-100 dark:bg-gray-700 text-sm font-bold text-gray-800 dark:text-gray-200">
                                            #
                                        </span>
                                        <h4 class="text-base font-semibold text-gray-900 dark:text-white">{{ $network?->symbol }}</h4>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                                    {{-- Fee --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">@lang('general.fee')</label>
                                        <input type="text" value="{{ toCurrency($nc?->fee) }}" data-network_id="{{ $network->id }}"
                                               class="network-input separator w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500/20" data-field="fee">
                                    </div>

                                    {{-- Min Deposit --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">@lang('general.min_deposit')</label>
                                        <input type="text" value="{{ toCurrency($nc?->min_deposit) }}"
                                               class="network-input separator w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500/20"
                                               data-network_id="{{ $network->id }}" data-field="min_deposit">
                                    </div>

                                    {{-- Min Withdraw --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">@lang('general.min_withdraw')</label>
                                        <input type="text" value="{{ toCurrency($nc?->min_withdraw) }}"
                                               class="network-input separator w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500/20"
                                               data-field="min_withdraw" data-network_id="{{ $network->id }}">
                                    </div>

                                    {{-- Status --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">@lang('general.status')</label>
                                        <select class="network-input w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500/20" data-field="status" data-network_id="{{ $network->id }}">
                                            @foreach(\App\Models\NetworkCurrency::getStatus() as $key=>$value)
                                                <option value="{{ $key }}" @if($nc && $nc->status == $key) selected @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Withdrawable Toggle --}}
                                    <div class="flex flex-col justify-center">
    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
        @lang('general.is_withdraw_able')
    </span>

                                        <label class="relative inline-flex items-center cursor-pointer select-none">
                                            <input type="hidden" class="network-input" value="0">

                                            <input
                                                type="checkbox"
                                                class="network-input sr-only peer"
                                                data-field="is_withdraw_able"
                                                data-network_id="{{ $network->id }}"
                                                value="1"
                                                @if($nc && $nc->is_withdraw_able) checked @endif
                                            >

                                            <div class="relative w-10 h-5 bg-gray-200 rounded-full
                    dark:bg-gray-700
                    peer-focus:outline-none
                    peer-checked:bg-green-600
                    after:content-['']
                    after:absolute
                    after:top-[2px]
                    after:left-[2px]
                    after:w-4
                    after:h-4
                    after:bg-white
                    after:border
                    after:border-gray-300
                    after:rounded-full
                    after:transition-all
                    peer-checked:after:translate-x-full
                    peer-checked:after:border-white">
                                            </div>
                                        </label>
                                    </div>


                                    {{-- Depositable Toggle --}}
                                    <div class="flex flex-col justify-center">
    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">
        @lang('general.is_deposit_able')
    </span>

                                        <label class="relative inline-flex items-center cursor-pointer select-none">
                                            <input type="hidden" class="network-input" value="0">

                                            <input
                                                type="checkbox"
                                                class="network-input sr-only peer"
                                                data-network_id="{{ $network->id }}"
                                                data-field="is_deposit_able"
                                                value="1"
                                                @if($nc && $nc->is_deposit_able) checked @endif
                                            >

                                            <div class="relative w-10 h-5 bg-gray-200 rounded-full
                    dark:bg-gray-700
                    peer-focus:outline-none
                    peer-checked:bg-green-600
                    after:content-['']
                    after:absolute
                    after:top-[2px]
                    after:left-[2px]
                    after:w-4
                    after:h-4
                    after:bg-white
                    after:border
                    after:border-gray-300
                    after:rounded-full
                    after:transition-all
                    peer-checked:after:translate-x-full
                    peer-checked:after:border-white">
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Save Network Button Zone --}}
                                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/50 flex justify-end">
                                    <button type="button" data-network_id="{{$network->id}}" data-currency_id="{{$currency->id}}"
                                            class="save-network-btn inline-flex items-center justify-center px-4 py-1.5 text-xs font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm transition-colors duration-150">
                                        @lang('general.save')
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Voucher Fields Variant Layout --}}
            @if($currency->type == config('constants.currency.voucher'))
                <!-- Configuration Details Panel -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">@lang('general.details')</h3>
                    </div>

                    <div class="p-6">
                        <div class="mb-4 p-4 rounded-lg bg-blue-50 border border-blue-100 text-blue-800 dark:bg-blue-950/40 dark:border-blue-900/50 dark:text-blue-300 flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 shrink-0 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm">@lang('general.price_difference_note')</p>
                        </div>

                        <form action="{{route('admin.currency.voucher.save.option',$currency)}}" method="POST" class="space-y-5">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                {{-- API Key Input with Eye Mask Toggle --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">@lang('general.api_key')</label>
                                    <div class="relative rounded-lg shadow-sm">
                                        <input type="password" id="api_key_field" name="{{config('constants.currency.options.api_key')}}"
                                               value="{{optional($voucherOptions)[config('constants.currency.options.api_key')]}}"
                                               class="w-full pl-3.5 pr-10 py-2 border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500/20 font-mono tracking-wider text-sm">
                                        <button type="button" onclick="toggleSecretField('api_key_field', this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 icon-eye" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Secret Key Input with Eye Mask Toggle --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">@lang('general.secret_key')</label>
                                    <div class="relative rounded-lg shadow-sm">
                                        <input type="password" id="secret_key_field" name="{{config('constants.currency.options.secret_key')}}"
                                               value="{{optional($voucherOptions)[config('constants.currency.options.secret_key')]}}"
                                               class="w-full pl-3.5 pr-10 py-2 border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500/20 font-mono tracking-wider text-sm">
                                        <button type="button" onclick="toggleSecretField('secret_key_field', this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 icon-eye" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">@lang('general.percentage_difference_relative_usdt')</label>
                                    <input type="text" name="{{config('constants.currency.options.percentage_difference')}}"
                                           value="{{optional($voucherOptions)[config('constants.currency.options.percentage_difference')]}}"
                                           class=" w-full px-3.5 py-2 border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500/20">
                                </div>

{{--                                <div>--}}
{{--                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">@lang('general.fee')</label>--}}
{{--                                    <input type="text" name="{{config('constants.currency.options.withdraw_fee')}}"--}}
{{--                                           value="{{optional($voucherOptions)[config('constants.currency.options.withdraw_fee')]}}"--}}
{{--                                           class="separator w-full px-3.5 py-2 border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500/20">--}}
{{--                                </div>--}}
                            </div>

                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                                <button type="submit" class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm transition-colors duration-150">
                                    @lang('general.save')
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Asset Validation Panel -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">@lang('general.assets')</h3>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">@lang('general.balance')</label>
                                <input type="text" disabled value="{{optional($voucherOptions)[config('constants.currency.options.balance')] ?? 0}}"
                                       class="w-full px-3.5 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-500 dark:bg-gray-700/50 dark:border-gray-600 dark:text-gray-400 cursor-not-allowed font-mono">
                            </div>

                            <div>
                                <span class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.connection_status')</span>
                                @if(isset($voucherOptions[config('constants.currency.options.connection_status')]) && $voucherOptions[config('constants.currency.options.connection_status')] == true)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-950/50 dark:text-green-300">
                                        <span class="h-2 w-2 rounded-full bg-green-600 dark:bg-green-400"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-950/50 dark:text-red-300">
                                        <span class="h-2 w-2 rounded-full bg-red-600 dark:bg-red-400"></span> Disconnected
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                            <a href="{{route('admin.currency.voucher.update.balance',$currency)}}"
                               class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm transition-colors duration-150">
                                @lang('general.update')
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.save-network-btn').forEach(button => {
                button.addEventListener('click', async (event) => {
                    const data = fetchData(event);
                    await connectServer(data);
                });
            });
        });

        // Toggles input visualization profile masking safely
        function toggleSecretField(fieldId, buttonEl) {
            const field = document.getElementById(fieldId);
            if (!field) return;

            if (field.type === 'password') {
                field.type = 'text';
                buttonEl.classList.add('text-primary-600', 'dark:text-primary-400');
            } else {
                field.type = 'password';
                buttonEl.classList.remove('text-primary-600', 'dark:text-primary-400');
            }
        }

        async function connectServer(data) {
            const url = "{{route('admin.currency.networking')}}";
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    if (typeof mboxError === 'function') mboxError();
                    return;
                }
                if (typeof mboxSuccess === 'function') mboxSuccess();
            } catch (error) {
                console.error('Fetch operations failed:', error.message);
            }
        }

        function fetchData(event) {
            const button = event.currentTarget;
            const currency_id = button.dataset.currency_id;
            const network_id = button.dataset.network_id;
            const inputs = document.querySelectorAll(`[data-network_id="${network_id}"]`);

            const data = {
                network_id,
                currency_id,
            };

            inputs.forEach(input => {
                const field = input.dataset.field;
                if (!field) return;

                if (input.type === 'checkbox') {
                    data[field] = input.checked ? 1 : 0;
                } else {
                    data[field] = input.value;
                }
            });
            return data;
        }
    </script>
@endsection
