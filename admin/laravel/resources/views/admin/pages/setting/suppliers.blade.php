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
                <a href="{{route('admin.setting.currency.management')}}"
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

        <!-- Admin form -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            {{--            step 1 form--}}
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">@lang('general.step_one')</h3>
                <!-- Supplier 1: Gate io -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($options as $key=>$value)
                        @php
                            $supplier = $suppliers->where('name',$key)->first();
                        @endphp
                        @continue($key == config('constants.option.hd_wallet') || $key == config('constants.option.bitycle') )
                        @switch($key)
                            @case(config('constants.option.bitycle'))
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{$value}}</h3>

                                    <form action="{{ route('admin.setting.supplier.store') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="name" value="{{$key}}">
                                        <div class="space-y-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    Bitycle lab <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="api_key"
                                                       {{--                                                   value="{{old('api_key',$supplier?->api_key)}}"--}}
                                                       placeholder="{{ Str::of( $supplier->api_key ?? '')->mask('*', 0) }} "
                                                       class="w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                @error('api_key')
                                                <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    Bitycle cap <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="secret_key"
                                                       {{--                                                   value="{{old('secret_key',$supplier?->secret_key)}}"--}}
                                                       placeholder="{{ Str::of( $supplier->secret_key ?? '')->mask('*', 0) }} "
                                                       class="w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                @error('secret_key')
                                                <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    @lang('general.withdraw_api_key') <span
                                                        class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="withdraw_api_key"
                                                       {{--                                                   value="{{old('withdraw_api_key',$supplier?->withdraw_api_key)}}"--}}
                                                       placeholder="{{ Str::of( $supplier->withdraw_api_key ?? '')->mask('*', 0) }} "
                                                       class="w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                @error('withdraw_api_key')
                                                <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    @lang('general.withdraw_secret_key') <span
                                                        class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="withdraw_secret_key"
                                                       {{--                                                   value="{{old('withdraw_secret_key',$supplier?->withdraw_secret_key)}}"--}}
                                                       placeholder="{{ Str::of( $supplier->withdraw_secret_key ?? '')->mask('*', 0) }} "
                                                       class="w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                @error('withdraw_secret_key')
                                                <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    @lang('general.start_nounce') <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="start_nounce"
                                                       value="{{old('start_nounce',$supplier?->start_nounce)}}"
                                                       class="separator w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                @error('start_nounce')
                                                <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    @lang('general.end_nounce') <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="end_nounce"
                                                       value="{{old('end_nounce',$supplier?->end_nounce)}}"
                                                       class="separator w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                @error('end_nounce')
                                                <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>

                                            <div class="pt-2">
                                                <button type="submit"
                                                        class="w-full flex justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm">
                                                    @lang('general.save')
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @break
                            @case(config('constants.option.manual'))
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">@lang('general.manual_and_hd_wallet')</h3>

                                    <form action="{{ route('admin.setting.supplier.store') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="name" value="{{$key}}">
                                        <div class="space-y-4">
                                            @php
                                                $apiKeyFields = [
                                                    ['provider' => 'tatum',      'option' => config('constants.option.tatum_api_key'),      'label' => trans('general.tatum_api_key')],
                                                    ['provider' => 'tron_scan',  'option' => config('constants.option.tron_scan_api_key'),  'label' => trans('general.tron_scan_api_key')],
                                                    ['provider' => 'ton_center', 'option' => config('constants.option.ton_center_api_key'), 'label' => trans('general.ton_center_api_key')],
                                                ];
                                            @endphp

                                            @foreach($apiKeyFields as $field)
                                                @php
                                                    $storedKey = (string) (optional($data)[$field['option']] ?? '');
                                                    $isSet = $storedKey !== '';
                                                    // Never render the secret itself; a short tail is enough to tell keys apart.
                                                    $maskedKey = $isSet
                                                        ? str_repeat('•', 8) . mb_substr($storedKey, -4)
                                                        : '';
                                                @endphp
                                                <div>
                                                    <div class="flex items-center justify-between mb-2 gap-2">
                                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                            {{ $field['label'] }} <span class="text-red-500">*</span>
                                                        </label>
                                                        @if($isSet)
                                                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">
                                                                @lang('general.api_key_is_set') · {{ $maskedKey }}
                                                            </span>
                                                        @else
                                                            <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                                                                @lang('general.api_key_is_not_set')
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <div class="flex gap-2">
                                                        <input type="text"
                                                               name="{{ $field['option'] }}"
                                                               data-api-key-input="{{ $field['provider'] }}"
                                                               autocomplete="off"
                                                               value="{{ old($field['option']) }}"
                                                               placeholder="{{ $isSet ? $maskedKey : '' }}"
                                                               class="flex-1 min-w-0 px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                        <button type="button"
                                                                data-api-key-test="{{ $field['provider'] }}"
                                                                data-api-key-label="{{ $field['label'] }}"
                                                                class="shrink-0 px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                            @lang('general.api_key_test_connection')
                                                        </button>
                                                        @if($field['provider'] === 'tatum')
                                                            {{-- Only Tatum exposes a chain list. --}}
                                                            <button type="button"
                                                                    data-api-key-networks="{{ $field['provider'] }}"
                                                                    class="shrink-0 px-3 py-2 text-sm font-medium rounded-lg border border-primary-300 dark:border-primary-700 text-primary-700 dark:text-primary-300 hover:bg-primary-50 dark:hover:bg-primary-900/30">
                                                                @lang('general.api_key_view_networks')
                                                            </button>
                                                            <button type="button"
                                                                    data-api-key-sync="{{ $field['provider'] }}"
                                                                    class="shrink-0 px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                                @lang('general.api_key_sync_networks')
                                                            </button>
                                                        @endif
                                                    </div>

                                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                        @lang('general.api_key_leave_blank_hint')
                                                    </p>

                                                    @error($field['option'])
                                                    <span class="text-red-500">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            @endforeach

                                            <div class="pt-2">
                                                <button type="submit"
                                                        class="w-full flex justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm">
                                                    @lang('general.save')
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @break

                            @case(config('constants.option.nobitex'))
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{$value}}</h3>

                                    <form action="{{ route('admin.setting.supplier.store') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="name" value="{{$key}}">
                                        <div class="space-y-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    @lang('general.api_key') <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="api_key"
                                                       {{--                                                   value="{{old('api_key',$supplier?->api_key)}}"--}}
                                                       placeholder="{{ Str::of( $supplier->api_key ?? '')->mask('*', 0) }} "
                                                       class="w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                @error('api_key')
                                                <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    @lang('general.secret_key') <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="secret_key"
                                                       placeholder="{{ Str::of($supplier->secret_key ?? '')->mask('*', 0) }}"
                                                       class="w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                @error('secret_key')
                                                <span class="text-red-500">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-span-2 md:col-span-1">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.irt_exchange_automatic')</label>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="hidden" value="{{config('constants.option.inactive')}}"
                                                           name="status">
                                                    <input type="checkbox" class="sr-only peer"
                                                           name="status"
                                                           value="{{config('constants.option.active')}}"
                                                           @if( $supplier?->status == config('constants.option.active'))
                                                               checked @endif>
                                                    <div
                                                        class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-green-600 transition-colors"></div>
                                                    <div
                                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-full"></div>
                                                </label>
                                                @error('status')
                                                <span class="text-danger">{{$message}}</span>
                                                @enderror
                                            </div>


                                            <div class="pt-2">

                                                <div class="flex gap-3">

                                                    <a href="{{ route('admin.setting.supplier.sync',['supplier_id'=> $supplier?->id]) }}"
                                                       class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border border-blue-500 text-blue-600 hover:bg-blue-500 hover:text-white transition-all duration-200 shadow-sm">

                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             class="w-5 h-5"
                                                             fill="none"
                                                             viewBox="0 0 24 24"
                                                             stroke="currentColor">
                                                            <path stroke-linecap="round"
                                                                  stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M4 4v6h6M20 20v-6h-6M20 8a8 8 0 00-14.9-3M4 16a8 8 0 0014.9 3"/>
                                                        </svg>

                                                        @lang('general.sync')
                                                    </a>

                                                    <a href="{{ route('admin.setting.supplier.network',['supplier_id'=> $supplier?->id]) }}"
                                                       class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border border-emerald-500 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-all duration-200 shadow-sm">

                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                             class="w-5 h-5"
                                                             fill="none"
                                                             viewBox="0 0 24 24"
                                                             stroke="currentColor">
                                                            <path stroke-linecap="round"
                                                                  stroke-linejoin="round"
                                                                  stroke-width="2"
                                                                  d="M17 20h5V4H2v16h5m10 0v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4m10 0H7"/>
                                                        </svg>

                                                        @lang('general.receive_networks')
                                                    </a>

                                                </div>

                                                <br>

                                                <button type="submit"
                                                        class="w-full flex justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm">
                                                    @lang('general.save')
                                                </button>

                                            </div>
                                        </div>
                                    </form>

                                    <div
                                        class="mt-3 p-4 rounded-lg bg-yellow-50 border border-yellow-300 dark:bg-yellow-900/20 dark:border-yellow-700">
                                        <div class="text-sm text-yellow-800 dark:text-yellow-200 leading-7">

                                            <strong>@lang('general.nobitex_wallet_notice_title')</strong>

                                            {{ trans('general.nobitex_wallet_notice_1') }}

                                            <br><br>

                                            <strong>
                                                {{ trans('general.nobitex_wallet_notice_2') }}
                                            </strong>

                                            <br><br>

                                            {{ trans('general.nobitex_wallet_notice_3') }}

                                        </div>
                                    </div>
                                </div>
                                @break
                            @case(config('constants.option.mexc'))
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ $value }}</h3>

                                    <form action="{{ route('admin.setting.supplier.store') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="name" value="{{ $key }}">
                                        <div class="space-y-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    @lang('general.api_key') <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="api_key"
                                                       placeholder="{{ Str::of($supplier->api_key ?? '')->mask('*', 0) }}"
                                                       class="w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                @error('api_key')
                                                <span class="text-red-500">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    @lang('general.secret_key') <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="secret_key"
                                                       placeholder="{{ Str::of($supplier->secret_key ?? '')->mask('*', 0) }}"
                                                       class="w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                @error('secret_key')
                                                <span class="text-red-500">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div
                                                class="p-4 rounded-lg bg-blue-50 border border-blue-100 dark:bg-blue-900/20 dark:border-blue-800 text-sm text-blue-700 dark:text-blue-300">
                                                @lang('general.mexc_supplier_hint')
                                            </div>

                                            <div class="pt-2">
                                                @if($supplier?->id)
                                                    <a href="{{ route('admin.setting.supplier.assets', ['supplier_id' => $supplier->id]) }}"
                                                       class="mb-3 w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border border-purple-500 text-purple-600 hover:bg-purple-500 hover:text-white transition-all duration-200 shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7L12 3 4 7m16 0v10l-8 4m8-14l-8 4m0 10L4 17V7m8 14V11M4 7l8 4"/>
                                                        </svg>
                                                        @lang('general.inventory')
                                                    </a>
                                                @endif

                                                <button type="submit"
                                                        class="w-full flex justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm">
                                                    @lang('general.save')
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @break
                            @case(config('constants.option.bitgo'))
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">

                                    <!-- Title -->
                                    <div class="mb-6">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                            @lang('general.setting') ({{$value}})
                                        </h3>
                                    </div>

                                    <!-- Form -->
                                    <form action="{{ route('admin.setting.supplier.store') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="name" value="{{config('constants.bitgo')}}">
                                        <!-- API KEY -->
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                @lang('general.api_key')<span class="text-red-500">*</span>
                                            </label>

                                            <div class="relative">
                                                <input
                                                    type="password"
                                                    id="apiKey"
                                                    name="{{config('constants.option.bitgo_access_token')}}"
                                                    class="text-xs w-full px-4 py-3 end-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition"
                                                    placeholder="@lang('general.enter_api_key')"
                                                >

                                                <!-- Show/Hide -->
                                                <button type="button"
                                                        onclick="toggleApiKey()"
                                                        class="text-sm absolute inset-y-0 end-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                    👁
                                                </button>
                                            </div>

                                            <!-- Masked hint -->
                                            @if(isset($supplier->api_key))
                                                <p class="text-xs text-gray-400">
                                                    @lang('general.saved')
                                                    : {{ Str::of($supplier->api_key)->mask('*', 4) }}
                                                </p>
                                            @endif

                                            @error(config('constants.option.bitgo_access_token'))
                                            <span class="text-red-500 text-sm">{{$message}}</span>
                                            @enderror
                                        </div>

                                        <!-- Info box -->
                                        <div
                                            class="text-xs mt-5 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800  text-blue-700 dark:text-blue-300">
                                            @lang('general.api_key_secure_note')
                                        </div>

                                        <!-- Submit -->
                                        <div class="mt-6 flex items-center justify-between">
                                            <button type="submit"
                                                    class="text-sm px-6 py-2.5 rounded-xl bg-primary-600 text-white font-medium hover:bg-primary-700 transition shadow">
                                                @lang('general.save')
                                            </button>
                                        </div>

                                    </form>
                                </div>
                                @break
                            @case(config('constants.option.cregis'))

                                @php
                                    $fields = [
                                        [
                                            'label' => __('general.api_key'),
                                            'name'  => config('constants.option.cregis_access_token'),
                                            'type'  => 'password',
                                            'placeholder' => __('general.enter_api_key'),
                                            'value' => $supplier->api_key ?? null,
                                        ],
                                        [
                                            'label' => __('general.project_no'),
                                            'name'  => config('constants.option.cregis_project_no'),
                                            'type'  => 'text',
                                            'placeholder' => __('general.enter_project_no'),
                                            'value' => $supplier->secret_key ?? null,
                                        ],
                                        [
                                            'label' => __('general.url'),
                                            'name'  => config('constants.option.cregis_base_url'),
                                            'type'  => 'text',
                                            'placeholder' => __('general.url'),
                                            'value' => $supplier->url ?? null,
                                        ],
                                        [
                                            'label' => __('general.cregis_team_base_url'),
                                            'name'  => config('constants.option.cregis_team_base_url'),
                                            'type'  => 'text',
                                            'placeholder' => __('general.cregis_team_base_url'),
                                            'value' => optional($data)[config('constants.option.cregis_team_base_url')] ?? null,
                                            'mask' => false,
                                        ],
                                        [
                                            'label' => __('general.cregis_team_api_key'),
                                            'name'  => config('constants.option.cregis_team_api_key'),
                                            'type'  => 'password',
                                            'placeholder' => __('general.cregis_team_api_key'),
                                            'value' => optional($data)[config('constants.option.cregis_team_api_key')] ?? null,
                                        ],
                                        [
                                            'label' => __('general.cregis_team_secret_key'),
                                            'name'  => config('constants.option.cregis_team_secret_key'),
                                            'type'  => 'password',
                                            'placeholder' => __('general.cregis_team_secret_key'),
                                            'value' => optional($data)[config('constants.option.cregis_team_secret_key')] ?? null,
                                        ],
                                        [
                                            'label' => __('general.cregis_callback_base_url'),
                                            'name'  => config('constants.option.cregis_callback_base_url'),
                                            'type'  => 'text',
                                            'placeholder' => __('general.cregis_callback_base_url_hint'),
                                            'value' => optional($data)[config('constants.option.cregis_callback_base_url')] ?? null,
                                            'mask' => false,
                                        ],
                                        [
                                            'label' => __('general.cregis_callback_ips'),
                                            'name'  => config('constants.option.cregis_callback_ips'),
                                            'type'  => 'text',
                                            'placeholder' => __('general.cregis_callback_ips_hint'),
                                            'value' => optional($data)[config('constants.option.cregis_callback_ips')] ?? null,
                                            'mask' => false,
                                        ],

                                    ];
                                @endphp

                                <div
                                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">

                                    <!-- Title -->
                                    <div class="mb-6">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                            @lang('general.setting') ({{ $value }})
                                        </h3>
                                    </div>

                                    <form action="{{ route('admin.setting.supplier.store') }}" method="POST">
                                        @csrf

                                        <input type="hidden" name="name" value="{{ config('constants.cregis') }}">

                                        @foreach($fields as $field)
                                            @php
                                                $fieldId = Str::slug($field['name']);
                                            @endphp

                                            <div class="space-y-2 mb-5">

                                                <label
                                                    for="{{ $fieldId }}"
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                                                >
                                                    {{ $field['label'] }}
                                                    <span class="text-red-500">*</span>
                                                </label>

                                                <div class="relative">

                                                    <input
                                                        type="{{ $field['type'] }}"
                                                        id="{{ $fieldId }}"
                                                        name="{{ $field['name'] }}"
                                                        value="{{ old($field['name'], ($field['mask'] ?? true) ? '' : ($field['value'] ?? '')) }}"
                                                        placeholder="{{ $field['placeholder'] }}"
                                                        class="text-xs w-full px-4 py-3 pe-10 border rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-primary-500
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white transition"
                                                    >

                                                    @if($field['type'] === 'password')
                                                        <button
                                                            type="button"
                                                            onclick="togglePassword('{{ $fieldId }}')"
                                                            class="text-sm absolute inset-y-0 end-3 flex items-center
                                   text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                                        >
                                                            👁
                                                        </button>
                                                    @endif

                                                </div>

                                                @if(isset($field['value']) && ($field['mask'] ?? true))
                                                    <p class="text-xs text-gray-400">
                                                        @lang('general.saved'):
                                                        {{ Str::of($field['value'])->mask('*', 4) }}
                                                    </p>
                                                @endif

                                                @error($field['name'])
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                                @enderror

                                            </div>
                                        @endforeach

                                        <!-- Info box -->
                                        <div class="text-xs mt-5 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20
                    border border-blue-100 dark:border-blue-800
                    text-blue-700 dark:text-blue-300">
                                            @lang('general.api_key_secure_note')
                                        </div>

                                        <!-- Submit -->
                                        <div class="mt-8 flex flex-col-reverse gap-3 border-t border-gray-200 dark:border-gray-700 pt-6
            sm:flex-row sm:items-center sm:justify-between">

                                            {{-- Secondary Actions --}}
                                            <div class="flex flex-col gap-3 sm:flex-row">

                                                <a href="{{ route('admin.setting.supplier.sync',['supplier_id'=> $supplier?->id]) }}"
                                                   class="inline-flex h-11 items-center justify-center gap-2 rounded-xl
                  border border-gray-300 bg-white px-5 text-sm font-medium
                  text-gray-700 shadow-sm transition-all
                  hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600
                  focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">

                                                    {{-- Sync Icon --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-5 w-5"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor">
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M4 4v6h6M20 20v-6h-6M20 8a8 8 0 00-14.9-3M4 16a8 8 0 0014.9 3"/>
                                                    </svg>

                                                    @lang('general.sync')
                                                </a>

                                                <a href="{{ route('admin.setting.supplier.inventory',['supplier_id'=> $supplier?->id]) }}"
                                                   class="inline-flex h-11 items-center justify-center gap-2 rounded-xl
                  border border-gray-300 bg-white px-5 text-sm font-medium
                  text-gray-700 shadow-sm transition-all
                  hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-600
                  focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">

                                                    {{-- Inventory Icon --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-5 w-5"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor">
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M20 7L12 3 4 7m16 0v10l-8 4m8-14l-8 4m0 10L4 17V7m8 14V11M4 7l8 4"/>
                                                    </svg>

                                                    @lang('general.inventory')
                                                </a>

                                                <a href="{{ route('admin.setting.supplier.network',['supplier_id'=> $supplier?->id]) }}"
                                                   class="inline-flex h-11 items-center justify-center gap-2 rounded-xl
                  border border-gray-300 bg-white px-5 text-sm font-medium
                  text-gray-700 shadow-sm transition-all
                  hover:border-emerald-500 hover:bg-emerald-50 hover:text-emerald-600
                  focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200">

                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="w-5 h-5"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor">
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M17 20h5V4H2v16h5m10 0v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4m10 0H7"/>
                                                    </svg>

                                                    @lang('general.receive_networks')
                                                </a>

                                            </div>

                                            {{-- Primary Action --}}
                                            <button
                                                type="submit"
                                                class="inline-flex h-11 items-center justify-center rounded-xl
               bg-primary-600 px-8 text-sm font-semibold text-white
               shadow-lg transition-all duration-200
               hover:bg-primary-700 hover:shadow-xl
               focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                                @lang('general.save')
                                            </button>

                                        </div>

                                    </form>
                                </div>

                                @break
                            @default
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
                                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">{{$value}}</h3>

                                    <form action="{{ route('admin.setting.supplier.store') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="name" value="{{$key}}">
                                        <div class="space-y-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    @lang('general.api_key') <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="api_key"
                                                       placeholder="{{ Str::of( $supplier->api_key ?? '')->mask('*', 0) }} "
                                                       class="w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                @error('api_key')
                                                <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                    @lang('general.secret_key') <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="secret_key"
                                                       placeholder="{{ Str::of( $supplier->secret_key ?? '')->mask('*', 0) }} "
                                                       class="w-full px-4 py-2 border rounded-lg focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                @error('secret_key')
                                                <span class="text-red-500">{{$message}}</span>
                                                @enderror
                                            </div>

                                            <div class="pt-2">
                                                <div class="flex gap-3">
                                                    <a href="{{ route('admin.setting.supplier.sync',['supplier_id'=> $supplier?->id]) }}"
                                                       class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border border-blue-500 text-blue-600 hover:bg-blue-500 hover:text-white transition-all duration-200 shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M20 20v-6h-6M20 8a8 8 0 00-14.9-3M4 16a8 8 0 0014.9 3"/>
                                                        </svg>
                                                        @lang('general.sync')
                                                    </a>

                                                    <a href="{{ route('admin.setting.supplier.network',['supplier_id'=> $supplier?->id]) }}"
                                                       class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border border-emerald-500 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-all duration-200 shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4m10 0H7"/>
                                                        </svg>
                                                        @lang('general.receive_networks')
                                                    </a>

                                                    @if(in_array($key, [\App\Models\SupplierExchange::SUPPLIER_GATE_IO, \App\Models\SupplierExchange::SUPPLIER_COINEX], true) && $supplier?->id)
                                                        <a href="{{ route('admin.setting.supplier.assets', ['supplier_id' => $supplier->id]) }}"
                                                           class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-lg border border-purple-500 text-purple-600 hover:bg-purple-500 hover:text-white transition-all duration-200 shadow-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7L12 3 4 7m16 0v10l-8 4m8-14l-8 4m0 10L4 17V7m8 14V11M4 7l8 4"/>
                                                            </svg>
                                                            @lang('general.inventory')
                                                        </a>
                                                    @endif
                                                </div>

                                                <br>

                                                <button type="submit"
                                                        class="w-full flex justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm">
                                                    @lang('general.save')
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                        @endswitch

                    @endforeach
                </div>
            </div>


            {{--step 2 form--}}
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">@lang('general.step_two')</h3>
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">

                    <form class="col-span-1" action="{{ route('admin.setting.supplier.store') }}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="">
                            <div class="mb-4 w-full">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.extra_fee')
                                    <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-5">
                                    <input name="extra_fee" required
                                           oninvalid="this.setCustomValidity('@lang('general.extra_fee_required')')"
                                           type="text"
                                           oninput="setCustomValidity('')"
                                           value="{{old('extra_fee',toCurrency($dataSupplier?->extra_fee))}}"
                                           class="separator w-full col-span-2 md:col-span-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @error('extra_fee')
                                    <span class="text-red-500">{{$message}}</span>
                                    @enderror
                                    <p class="text-yellow-500 col-span-2 md:col-span-1 mt-2 md:mt-0">@lang('general.extra_fee_description')</p>
                                </div>

                            </div>
                            <div class="mb-4 w-full">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.max_extra_fee_amount')
                                    <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-5">
                                    <input name="extra_fee_max_amount" required
                                           oninvalid="this.setCustomValidity('@lang('general.max_extra_fee_amount_required')')"
                                           type="text"
                                           oninput="setCustomValidity('')"
                                           value="{{old('extra_fee_max_amount',toCurrency($dataSupplier?->extra_fee_max_amount))}}"
                                           class="separator w-full col-span-2 md:col-span-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @error('extra_fee')
                                    <span class="text-red-500">{{$message}}</span>
                                    @enderror
                                    <p class="text-yellow-500 col-span-2 md:col-span-1 mt-2 md:mt-0">@lang('general.max_extra_fee_description')</p>
                                </div>

                            </div>
                            @if($dataSupplier)
                                <div class="pt-4 col-span-full">
                                    <button type="submit"
                                            class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                        @lang('general.save')
                                    </button>
                                </div>
                            @endif


                        </div>
                    </form>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">

                    <form class="col-span-1" action="{{ route('admin.setting.supplier.store') }}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="step" value="2">
                        <div class="">
                            <div class="mb-4 w-full">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.wallet_supplier')
                                    <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-5">
                                    <select @if(! $dataSupplier) disabled @endif id="supplier_type" name="supplier"
                                            required
                                            oninvalid="this.setCustomValidity('@lang('general.supplier_wallet_required')')"
                                            oninput="setCustomValidity('')"
                                            class="w-full col-span-2 md:col-span-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="{{null}}">{{trans('general.choose_option')}}</option>
                                        @foreach(\App\Models\SupplierExchange::getAllSupplier() as $key=>$value)
                                            <option
                                                @selected(old('supplier', $dataSupplier->supplier ?? null) == $key) value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('supplier')
                                    <span class="text-red-500">{{$message}}</span>
                                    @enderror
                                    <p class="text-yellow-500 col-span-2 md:col-span-1 mt-2 md:mt-0">@lang('general.wallet_supplier_description')</p>
                                </div>

                            </div>
                            <div class="mb-4 w-full">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.exchange_supplier')
                                    <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-2 gap-5">
                                    <select @if(!$dataSupplier) disabled @endif  name="exchange_supplier" required
                                            oninvalid="this.setCustomValidity('@lang('general.exchanger_required')')"
                                            oninput="setCustomValidity('')"
                                            class="w-full col-span-2 md:col-span-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="{{null}}">{{trans('general.choose_option')}}</option>
                                        @foreach(\App\Models\SupplierExchange::getAllSupplier() as $key=>$value)
                                            <option
                                                @selected(old('exchange_supplier', $dataSupplier->exchange_supplier ?? null) == $key) value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('exchange_supplier')
                                    <span class="text-red-500">{{$message}}</span>
                                    @enderror
                                    <p class="text-yellow-500 col-span-2 md:col-span-1 mt-2 md:mt-0">@lang('general.exchange_supplier_description')</p>
                                </div>

                            </div>
                            @if($dataSupplier)
                                <div class="pt-4 col-span-full">
                                    <button type="submit"
                                            class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                        @lang('general.save')
                                    </button>
                                </div>
                            @endif


                        </div>
                    </form>
                </div>
            </div>


        </div>
    </main>

    {{-- Result of the Tatum / TronScan / TonCenter connection test --}}
    <div id="apiKeyTestModal"
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="w-full max-w-md rounded-xl bg-white dark:bg-gray-800 shadow-xl border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">
                    @lang('general.api_key_test_result')
                </h3>
                <button type="button" data-api-key-modal-close
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl leading-none">&times;</button>
            </div>
            <div id="apiKeyTestModalBody" class="px-5 py-4 space-y-3 text-sm"></div>
            <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700 text-left">
                <button type="button" data-api-key-modal-close
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600">
                    @lang('general.api_key_close')
                </button>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function () {
            const modal = document.getElementById('apiKeyTestModal');

            if (!modal) {
                return;
            }

            const body = document.getElementById('apiKeyTestModalBody');
            const endpoint = @json(route('admin.setting.supplier.api.key.test'));
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

            const text = {
                ok: @json(trans('general.api_key_connection_ok')),
                failed: @json(trans('general.api_key_connection_failed')),
                requestFailed: @json(trans('general.api_key_request_failed')),
                detail: @json(trans('general.api_key_error_detail')),
                status: @json(trans('general.api_key_http_status')),
                quota: @json(trans('general.api_key_quota')),
                used: @json(trans('general.api_key_quota_used')),
                remaining: @json(trans('general.api_key_quota_remaining')),
                limit: @json(trans('general.api_key_quota_limit')),
                plan: @json(trans('general.api_key_plan')),
                testing: @json(trans('general.api_key_testing')),
                test: @json(trans('general.api_key_test_connection')),
                viewNetworks: @json(trans('general.api_key_view_networks')),
                syncNetworks: @json(trans('general.api_key_sync_networks')),
                mapped: @json(trans('general.api_key_networks_mapped')),
                notMapped: @json(trans('general.api_key_networks_not_mapped')),
                yours: @json(trans('general.api_key_networks_yours')),
                testnet: @json(trans('general.api_key_networks_testnet')),
                chainsWord: @json(trans('general.api_key_networks_count')),
            };

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            modal.querySelectorAll('[data-api-key-modal-close]').forEach(function (el) {
                el.addEventListener('click', closeModal);
            });

            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            function escapeHtml(value) {
                return String(value ?? '').replace(/[&<>"']/g, function (c) {
                    return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c];
                });
            }

            function formatNumber(value) {
                return Number(value).toLocaleString();
            }

            function render(label, result) {
                const ok = !!result.ok;
                const rows = [];

                rows.push(
                    '<div class="flex items-center gap-2">' +
                    '<span class="inline-block w-2.5 h-2.5 rounded-full ' + (ok ? 'bg-green-500' : 'bg-red-500') + '"></span>' +
                    '<span class="font-medium ' + (ok ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400') + '">' +
                    escapeHtml(result.message || (ok ? text.ok : text.failed)) +
                    '</span></div>'
                );

                rows.push('<div class="text-gray-500 dark:text-gray-400">' + escapeHtml(label) + '</div>');

                if (result.http_status) {
                    rows.push('<div><span class="text-gray-500 dark:text-gray-400">' + escapeHtml(text.status) + ':</span> ' + escapeHtml(result.http_status) + '</div>');
                }

                const quota = result.quota;

                if (quota) {
                    const percent = quota.percent === null || quota.percent === undefined ? 0 : quota.percent;
                    const barColor = percent >= 90 ? 'bg-red-500' : (percent >= 70 ? 'bg-yellow-500' : 'bg-green-500');

                    rows.push(
                        '<div class="pt-2 border-t border-gray-200 dark:border-gray-700">' +
                        '<div class="flex justify-between mb-1">' +
                        '<span class="text-gray-500 dark:text-gray-400">' + escapeHtml(text.quota) + (quota.plan ? ' · ' + escapeHtml(text.plan) + ': ' + escapeHtml(quota.plan) : '') + '</span>' +
                        '<span class="font-medium">' + escapeHtml(percent) + '%</span>' +
                        '</div>' +
                        '<div class="w-full h-2 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">' +
                        '<div class="h-2 ' + barColor + '" style="width:' + Math.min(100, Math.max(0, percent)) + '%"></div>' +
                        '</div>' +
                        '<div class="grid grid-cols-3 gap-2 mt-2 text-center">' +
                        '<div><div class="text-gray-500 dark:text-gray-400 text-xs">' + escapeHtml(text.used) + '</div><div class="font-medium">' + formatNumber(quota.used) + '</div></div>' +
                        '<div><div class="text-gray-500 dark:text-gray-400 text-xs">' + escapeHtml(text.remaining) + '</div><div class="font-medium">' + formatNumber(quota.remaining) + '</div></div>' +
                        '<div><div class="text-gray-500 dark:text-gray-400 text-xs">' + escapeHtml(text.limit) + '</div><div class="font-medium">' + formatNumber(quota.limit) + '</div></div>' +
                        '</div></div>'
                    );
                }

                if (result.detail) {
                    rows.push(
                        '<div class="pt-2 border-t border-gray-200 dark:border-gray-700">' +
                        '<div class="text-gray-500 dark:text-gray-400 mb-1">' + escapeHtml(ok ? '' : text.detail) + '</div>' +
                        '<pre class="whitespace-pre-wrap break-all text-xs bg-gray-100 dark:bg-gray-900 rounded p-2 max-h-40 overflow-auto">' + escapeHtml(result.detail) + '</pre>' +
                        '</div>'
                    );
                }

                body.innerHTML = rows.join('');
                openModal();
            }

            function renderNetworks(result) {
                const chains = result.chains || [];
                const rows = [];

                rows.push(
                    '<div class="text-gray-500 dark:text-gray-400">' +
                    escapeHtml(result.message || '') +
                    ' <span class="font-medium">(' + chains.length + ' ' + escapeHtml(text.chainsWord) + ')</span></div>'
                );

                if (result.unmapped && result.unmapped.length) {
                    rows.push(
                        '<div class="text-xs text-yellow-700 dark:text-yellow-400">' +
                        escapeHtml(text.notMapped) + ': ' + escapeHtml(result.unmapped.join(', ')) +
                        '</div>'
                    );
                }

                // Mapped chains first, then the rest; testnets last within each group.
                const sorted = chains.slice().sort(function (a, b) {
                    if (a.mapped !== b.mapped) return a.mapped ? -1 : 1;
                    if (a.testnet !== b.testnet) return a.testnet ? 1 : -1;
                    return String(a.chain).localeCompare(String(b.chain));
                });

                const items = sorted.map(function (c) {
                    const badge = c.mapped
                        ? '<span class="text-[11px] px-2 py-0.5 rounded-full bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300">' + escapeHtml(text.mapped) + '</span>'
                        : '<span class="text-[11px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">' + escapeHtml(text.notMapped) + '</span>';

                    const yours = (c.networks && c.networks.length)
                        ? '<div class="text-[11px] text-primary-600 dark:text-primary-400 mt-0.5">' +
                          escapeHtml(text.yours) + ': ' + escapeHtml(c.networks.join(', ')) + '</div>'
                        : '';

                    const testnet = c.testnet
                        ? ' <span class="text-[11px] text-gray-400">(' + escapeHtml(text.testnet) + ')</span>'
                        : '';

                    return '<div class="flex items-start justify-between gap-3 py-1.5 border-b border-gray-100 dark:border-gray-700 last:border-0">' +
                        '<div class="min-w-0"><div class="font-mono text-xs" dir="ltr">' + escapeHtml(c.chain) + testnet + '</div>' + yours + '</div>' +
                        '<div class="shrink-0">' + badge + '</div></div>';
                });

                rows.push('<div class="max-h-80 overflow-auto pt-1">' + items.join('') + '</div>');

                body.innerHTML = rows.join('');
                openModal();
            }

            document.querySelectorAll('[data-api-key-sync]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const provider = button.getAttribute('data-api-key-sync');
                    const original = button.textContent;

                    button.disabled = true;
                    button.textContent = text.testing;

                    fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({provider: provider, action: 'sync_networks'}),
                    })
                        .then(function (response) { return response.json(); })
                        .then(function (result) { render(text.syncNetworks, result); })
                        .catch(function (error) {
                            render(text.syncNetworks, {ok: false, message: text.requestFailed, detail: String(error)});
                        })
                        .finally(function () {
                            button.disabled = false;
                            button.textContent = original || text.syncNetworks;
                        });
                });
            });

            document.querySelectorAll('[data-api-key-networks]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const provider = button.getAttribute('data-api-key-networks');
                    const input = document.querySelector('[data-api-key-input="' + provider + '"]');
                    const typedKey = input ? input.value.trim() : '';
                    const original = button.textContent;

                    button.disabled = true;
                    button.textContent = text.testing;

                    fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({provider: provider, key: typedKey, action: 'networks'}),
                    })
                        .then(function (response) { return response.json(); })
                        .then(renderNetworks)
                        .catch(function (error) {
                            render(provider, {ok: false, message: text.requestFailed, detail: String(error)});
                        })
                        .finally(function () {
                            button.disabled = false;
                            button.textContent = original || text.viewNetworks;
                        });
                });
            });

            document.querySelectorAll('[data-api-key-test]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const provider = button.getAttribute('data-api-key-test');
                    const label = button.getAttribute('data-api-key-label') || provider;
                    const input = document.querySelector('[data-api-key-input="' + provider + '"]');
                    // An unsaved value in the box is tested as typed, so the admin can
                    // check a key before storing it.
                    const typedKey = input ? input.value.trim() : '';
                    const original = button.textContent;

                    button.disabled = true;
                    button.textContent = text.testing;

                    fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({provider: provider, key: typedKey}),
                    })
                        .then(function (response) {
                            return response.json().catch(function () {
                                return {ok: false, message: text.requestFailed, http_status: response.status};
                            });
                        })
                        .then(function (result) {
                            render(label, result);
                        })
                        .catch(function (error) {
                            render(label, {ok: false, message: text.requestFailed, detail: String(error)});
                        })
                        .finally(function () {
                            button.disabled = false;
                            button.textContent = original || text.test;
                        });
                });
            });
        })();

        function toggleApiKey() {
            const input = document.getElementById('apiKey');
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        const supplierType = document.getElementById('supplier_type');
        const apiKey = document.getElementById('api_key')
        const secretKey = document.getElementById('secret_key')
        const extraFee = document.getElementById('extra_fee')
        const startNounce = document.getElementById('start_nounce')
        const endNounce = document.getElementById('end_nounce')
        const withdrawApikey = document.getElementById('withdraw_api_key')
        const WithdrawSecretKey = document.getElementById('withdraw_secret_key')
        const apiKeyLabel = document.getElementById('api_key_label')
        const secretKeyLabel = document.getElementById('secret_key_label')
        const OPTIONS = {
            MANUAL: "{{ config('constants.option.manual') }}",
            BITYCLE: "{{ config('constants.option.bitycle') }}"
        };
        const defaultApiKeyText = "{{ trans('general.api_key') }}";
        const defaultSecretKeyText = "{{ trans('general.secret_key') }}";
        changeElements(supplierType)
        supplierType.addEventListener("change", function (event) {
            changeElements(event.currentTarget);
        });


        function changeElements(element) {
            if (!element) return;

            const show = element.value != "{{ config('constants.option.manual') }}";
            const bitycle = element.value == "{{ config('constants.option.bitycle') }}";
            if (element.value == "{{config('constants.option.bitycle')}}") {
                apiKeyLabel.innerHTML = 'Bitycle Lab <span class="text-red-500">*</span>';
                secretKeyLabel.innerHTML = 'Bitycle Cap <span class="text-red-500">*</span>';
            } else {
                apiKeyLabel.innerHTML = bitycle ? 'Bitycle Lab <span class="text-red-500">*</span>' : defaultApiKeyText;
                secretKeyLabel.innerHTML = bitycle ? 'Bitycle Cap <span class="text-red-500">*</span>' : defaultSecretKeyText;

            }


            apiKey.classList.toggle('hidden', !show);
            secretKey.classList.toggle('hidden', !show);
            extraFee.classList.toggle('hidden', !show);
            startNounce.classList.toggle('hidden', !bitycle);
            endNounce.classList.toggle('hidden', !bitycle);
            withdrawApikey.classList.toggle('hidden', !bitycle)
            WithdrawSecretKey.classList.toggle('hidden', !bitycle)


            apiKey.querySelector('input').required = show;
            secretKey.querySelector('input').required = show;
            extraFee.querySelector('input').required = show;
            startNounce.querySelector('input').required = bitycle;
            endNounce.querySelector('input').required = bitycle;
            withdrawApikey.querySelector('input').required = bitycle;
            WithdrawSecretKey.querySelector('input').required = bitycle;
        }
    </script>

@endsection
