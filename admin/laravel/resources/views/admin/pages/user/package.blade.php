@extends('admin.layout.master')

@section('title', trans('general.package_details') . ' : ' . $user->getFullInfo())

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    @lang('general.package_details') : {{ $user->getFullInfo() }}
                </h2>
            </div>
            <div class="mt-4 md:mt-0 flex items-center gap-3">
                <a href="{{ route('admin.user.edit', $user) }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200">
                    @lang('master.back')
                </a>
            </div>
        </div>

        <!-- Package Details Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.status')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">
                        {{ $package->status ? __('general.active') : __('general.inactive') }}
                    </dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.withdrawal_max')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->withdrawal_max) }}</dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.withdrawal_min_fiat_by_usdt')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->withdrawal_min_fiat) }}</dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.deposit_max_fiat')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->deposit_max_fiat) }}</dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.deposit_min_fiat_by_usdt')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->deposit_min_fiat) }}</dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.fee_fiat')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->fee_fiat) }}%</dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.fiat_maker_fee')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->fiat_maker_fee) }}%</dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.fiat_maker_fee_max_by_usdt')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->fiat_maker_fee_max) }}</dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.fiat_taker_fee')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->fiat_taker_fee) }}%</dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.fiat_taker_fee_max_by_usdt')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->fiat_maker_fee_max) }}</dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.internal_transfer_fee')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{toCurrency( $package->internal_transfer_fee) }}
                        %
                    </dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.deposit_possible')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ $package->deposit ? __('general.active') : __('general.inactive') }}</dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.withdrawal')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ $package->withdrawal ? __('general.active') : __('general.inactive') }}</dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.currency_maker_fee')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->currency_maker_fee) }}
                        %
                    </dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.currency_maker_fee_max')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->currency_maker_fee_max) }}</dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.currency_taker_fee')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->currency_taker_fee) }}
                        %
                    </dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.currency_taker_fee_max')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->currency_taker_fee_max) }}</dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.amount_to_grant')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->amount_to_grant) }}</dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.marketing_profit')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->marketing_profit) }}%
                    </dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.currency_min_deposit')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->currency_min_deposit) }}
                    </dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.currency_min_withdrawal')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->currency_min_withdrawal) }}
                    </dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.withdraw_first_time_limit')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->withdraw_first_time_limit) }}
                    </dd>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">@lang('general.withdraw_time_limit')</dt>
                    <dd class="text-base text-gray-900 dark:text-white">{{ toCurrency($package->withdraw_time_limit) }}
                    </dd>
                </div>

                <div class="flex items-center py-3 border-b border-gray-200 dark:border-gray-700">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 w-1/4">
                        @lang('general.package')
                    </dt>
                    <dd class="text-base text-gray-900 dark:text-white w-3/4">
                        <select id="package_input" name="package_id"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach($packages as $pkg)
                                <option
                                    @selected(old('package_id', $user->package_id) == $pkg->id)
                                    value="{{ $pkg->id }}">
                                    {{ $pkg->name }}
                                </option>
                            @endforeach
                        </select>
                    </dd>
                </div>
            </dl>
        </div>
    </main>
@endsection

@section('scripts')
    <script>

        const packageInput = document.getElementById('package_input')
        packageInput.addEventListener('change', async (event) => {
            await connectServer({'package_id' : event.currentTarget.value});
        })

        async function connectServer(data) {
            const url = "{{route('admin.user.details.package.update', $user->id)}}";
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
                    mboxError();
                    return;
                }
                mboxSuccess();

            } catch (error) {
                console.error('Fetch error:', error.message);
            }
        }
    </script>
@endsection
