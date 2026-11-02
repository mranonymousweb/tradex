@extends('admin.layout.master')

@section('title', trans('general.swap_setting'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.swap_setting')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.setting.index')}}"
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
                    <form action="{{ route('admin.setting.swap.store') }}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-2 gap-5">

                            <div class="col-span-2">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.swap_currency')
                                    <span class="text-red-500">*</span></label>
                                <select multiple name="currency_id[]"
                                        class="select2 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="{{'all'}}">@lang('general.choose_all')</option>
                                    @foreach($currencies as $key=>$value)
                                        <option value="{{ $key.','.$value }}">{{$value}}</option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>


                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.swap_method')</label>
                                <select name="{{ config('constants.option.swap_method') }}"
                                        id="swap_method"
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @foreach($swapMethods as $key => $label)
                                        <option value="{{ $key }}"
                                            @selected(old(config('constants.option.swap_method'), $options[config('constants.option.swap_method')] ?? config('constants.option.swap_method_international')) == $key)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error(config('constants.option.swap_method'))
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.swap_fee')</label>
                                <input type="text" name="{{config('constants.option.swap_fee')}}"
                                       value="{{old(config('constants.option.swap_fee'),toCurrency($options[config('constants.option.swap_fee')] ?? 0) ?? null)}}"
                                       class="separator w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.swap_fee')"
                                >
                                @error(config('constants.option.swap_fee'))
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.swap_fee_max')</label>
                                <input type="text" name="{{config('constants.option.swap_fee_max')}}"
                                       value="{{old(config('constants.option.swap_fee_max'),toCurrency($options[config('constants.option.swap_fee_max')] ?? 0) ?? null)}}"
                                       class="separator w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.swap_fee_max')"


                                >
                                @error(config('constants.option.swap_fee_max'))
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>


                            <div class="col-span-2 md:col-span-1 js-swap-custom-field">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.swap_decrease_from_currency_percentage')</label>
                                <input type="text"
                                       name="{{config('constants.option.swap_decrease_from_currency_percentage')}}"
                                       value="{{old(config('constants.option.swap_decrease_from_currency_percentage'),toCurrency($options[config('constants.option.swap_decrease_from_currency_percentage')] ?? 0) ?? null)}}"
                                       class="separator w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.swap_decrease_from_currency_percentage')"
                                >
                                @error(config('constants.option.swap_decrease_from_currency_percentage'))
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-span-2 md:col-span-1 js-swap-custom-field">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.swap_max_decrease_from_currency_amount')</label>
                                <input type="text"
                                       name="{{config('constants.option.swap_max_decrease_from_currency_amount')}}"
                                       value="{{old(config('constants.option.swap_max_decrease_from_currency_amount'),toCurrency($options[config('constants.option.swap_max_decrease_from_currency_amount')] ?? 0)  ?? null)}}"
                                       class="separator w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.swap_max_decrease_from_currency_amount')"


                                >
                                @error(config('constants.option.swap_max_decrease_from_currency_amount'))
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>


                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.swap_marketing_percentage')</label>
                                <input type="text" name="{{config('constants.option.swap_marketing_percentage')}}"
                                       value="{{old(config('constants.option.swap_marketing_percentage'),toCurrency($options[config('constants.option.swap_marketing_percentage')] ?? 0) ?? null)}}"
                                       class="separator w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.swap_marketing_percentage')"
                                >
                                @error(config('constants.option.swap_marketing_percentage'))
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.swap_without_admin')</label>
                                <input type="text" name="{{config('constants.option.swap_without_admin')}}"
                                       value="{{old(config('constants.option.swap_without_admin'),toCurrency($options[config('constants.option.swap_without_admin')] ?? 0) ?? null)}}"
                                       class="separator w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.swap_without_admin')"


                                >
                                @error(config('constants.option.swap_without_admin'))
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>


                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.swap_automatic_supplier')</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" value="{{config('constants.option.inactive')}}"
                                           name="{{config('constants.option.swap_automatic_supplier')}}">
                                    <input type="checkbox" class="sr-only peer"
                                           name="{{config('constants.option.swap_automatic_supplier')}}"
                                           value="{{config('constants.option.active')}}"
                                           @if(isset($options[config('constants.option.swap_automatic_supplier')]) && $options[config('constants.option.swap_automatic_supplier')] == config('constants.option.active'))
                                               checked @endif>
                                    <div
                                        class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-green-600 transition-colors"></div>
                                    <div
                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-full"></div>
                                </label>
                                @error(config('constants.option.swap_automatic_supplier'))
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.swap_min_amount')</label>
                                <input type="text" name="{{config('constants.option.swap_min_amount')}}"
                                       value="{{old(config('constants.option.swap_min_amount'),toCurrency($options[config('constants.option.swap_min_amount')] ?? 0) ?? null)}}"
                                       class="separator w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.swap_min_amount')"


                                >
                                @error(config('constants.option.swap_min_amount'))
                                <span class="text-danger">{{$message}}</span>
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


                <!-- search form -->
                @include('admin.pages.filter',['filters' => $filters,'back'=>route('admin.setting.swap.index')])

                <!-- Currency form -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.swap_currency')</h2>
                <div
                    class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="w-full overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">@lang('general.id')</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">@lang('general.currency')</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">@lang('general.name')</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">@lang('general.swap_min_amount')</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">@lang('general.swap_decrease_from_currency_percentage')</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">@lang('general.swap_max_decrease_from_currency_amount')</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">@lang('general.operation')</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($swapCurrencies as $swapCurrency)
                                @php
                                    $defaultDecreasePercentage = $options[config('constants.option.swap_decrease_from_currency_percentage')] ?? 0;
                                    $defaultMaxDecreaseAmount = $options[config('constants.option.swap_max_decrease_from_currency_amount')] ?? 0;
                                    $defaultMinAmount = $options[config('constants.option.swap_min_amount')] ?? 0;
                                @endphp
                                <tr data-swap-row>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-gray-100">
                                        {{ $swapCurrency->id }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($swapCurrency->currency && $swapCurrency->currency->icon)
                                            <img src="{{ asset($swapCurrency->currency->icon) }}"
                                                 alt="{{ $swapCurrency->currency->symbol }}"
                                                 class="h-6 w-6 inline-block rounded-full mr-1">
                                        @endif
                                        <span class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $swapCurrency->symbol ?? '-' }}
                                </span>
                                    </td>


                                    <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-gray-100">
                                        {{ $swapCurrency->name }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <input type="text"
                                               data-field="min_amount"
                                               value="{{ toCurrency($swapCurrency->min_amount ?? $defaultMinAmount) }}"
                                               class="separator w-28 px-2 py-1 text-sm border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="text"
                                               data-field="decrease_from_currency_percentage"
                                               value="{{ toCurrency($swapCurrency->decrease_from_currency_percentage ?? $defaultDecreasePercentage) }}"
                                               class="separator w-28 px-2 py-1 text-sm border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="text"
                                               data-field="max_decrease_from_currency_amount"
                                               value="{{ toCurrency($swapCurrency->max_decrease_from_currency_amount ?? $defaultMaxDecreaseAmount) }}"
                                               class="separator w-28 px-2 py-1 text-sm border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button"
                                                    data-id="{{ $swapCurrency->id }}"
                                                    class="px-3 py-1 text-sm rounded bg-primary-600 hover:bg-primary-700 text-white save-btn">
                                                @lang('general.save')
                                            </button>
                                            <form action="{{ route('admin.setting.swap.update', $swapCurrency->id) }}"
                                                  method="POST">
                                                @csrf
                                                <button type="button"
                                                        onclick="confirmAction(() => this.closest('form').submit())"
                                                        class="px-3 py-1 border rounded-lg text-sm font-medium text-white bg-danger dark:bg-danger hover:bg-danger dark:hover:bg-danger border-danger dark:border-danger">
                                                    @lang('general.delete')
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        @lang('general.no_data')
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $swapCurrencies->links('pagination::tailwind') }}
            </div>
        </div>
    </main>

@endsection
@section('scripts')
    <script>
        const swapMethodSelect = document.getElementById('swap_method');
        const internationalMethodValue = "{{ config('constants.option.swap_method_international') }}";

        function toggleSwapCustomFields() {
            const isInternational = !swapMethodSelect || swapMethodSelect.value === internationalMethodValue;
            document.querySelectorAll('.js-swap-custom-field').forEach((field) => {
                field.classList.toggle('hidden', !isInternational);
            });
        }

        if (swapMethodSelect) {
            swapMethodSelect.addEventListener('change', toggleSwapCustomFields);
            toggleSwapCustomFields();
        }

        document.querySelectorAll(".save-btn").forEach((btn) => {
            btn.addEventListener("click", (e) => {
                const row = btn.closest('[data-swap-row]');
                const swapId = btn.dataset.id;
                const payload = {};

                row.querySelectorAll('[data-field]').forEach((input) => {
                    payload[input.dataset.field] = String(input.value || '').replace(/,/g, '');
                });

                const route = "{{ route('admin.setting.swap.update', ':id') }}".replace(':id', swapId);
                confirmAction().then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(route, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "X-Requested-With": "XMLHttpRequest"
                                },
                                body: JSON.stringify(payload)
                            });

                            if (response.ok) {
                                mboxSuccess();
                            } else {
                                mboxError();
                            }
                        } catch (err) {
                            mboxError();
                        }
                    }
                });
            });
        });
    </script>

@endsection
