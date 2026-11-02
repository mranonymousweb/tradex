@extends('admin.layout.master')

@section('title', trans('general.assets'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- Header Page -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span>@lang('general.assets'):</span>
                    <span class="text-primary-600 dark:text-primary-400 font-medium">{{$user->getFullInfo()}}</span>
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.user.edit',$user) }}"
                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    @lang('master.back')
                </a>
                <a href="{{ route('admin.user.details.assets.refresh',$user) }}"
                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.253 8H18"/>
                    </svg>
                    @lang('general.refresh_asset')
                </a>
            </div>
        </div>

        @include('admin.pages.filter',['filters' => $filters,'back'=>route('admin.user.details.assets',$user)])

        <div
            class="mt-6 bg-white w-full dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3.5 text-start   font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">@lang('general.currency')</th>
                        <th scope="col"
                            class="px-4 py-3.5 text-center  font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">@lang('general.general_balance')</th>
                        <th scope="col"
                            class="px-4 py-3.5 text-center  font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">@lang('general.future_balance')</th>
                        <th scope="col"
                            class="px-4 py-3.5 text-center  font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">@lang('general.blocked_balance')</th>
                        <th scope="col"
                            class="px-4 py-3.5 text-center  font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">@lang('general.margin_balance')</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($assets as $asset)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                            <td class="px-6 py-4 text-start whitespace-nowrap align-middle">
                                <x-admin.currency :currency="$asset->currency"/>
                            </td>

                            <!-- General Balance -->
                            <td class="px-6 py-4 text-center whitespace-nowrap align-middle">
                                <div class="inline-flex items-center gap-1.5 dynamic-input-group">
                                    <input type="text" name="balance"
                                           data-id="{{ $asset->id }}"
                                           value="{{ toCurrency($asset->balance ?? 0) }}"
                                           class="separator separator-signed w-44 px-3 py-1.5 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition duration-150">
                                    {{--                                @can('asset_create')--}}
                                    <button type="button"
                                            data-modal-target="confirm"
                                            data-id="{{ $asset->id }}"
                                            data-key="balance"
                                            data-symbol="{{ $asset->currency->symbol }}"
                                            data-current="{{ $asset->balance }}"
                                            class="modal-trigger px-3 py-1 text-sm rounded bg-primary-600 hover:bg-primary-700 text-white save-btn">
                                        @lang('general.change')
                                    </button>
                                    {{--                                @endcan--}}
                                </div>
                            </td>

                            <!-- Future Balance -->
                            <td class="px-6 py-4 text-center whitespace-nowrap align-middle dynamic-input-group">
                                <div class="inline-flex items-center gap-1.5">
                                    <input type="text" name="feature_balance"
                                           data-id="{{ $asset->id }}"
                                           value="{{ toCurrency($asset->feature_balance ?? 0) }}"
                                           class="separator separator-signed w-44 px-3 py-1.5 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition duration-150">
                                    {{--                                @can('asset_create')--}}
                                    <button type="button"
                                            data-modal-target="confirm"
                                            data-id="{{ $asset->id }}"
                                            data-key="feature_balance"
                                            data-symbol="{{ $asset->currency->symbol }}"
                                            data-current="{{ $asset->feature_balance }}"
                                            class="modal-trigger px-3 py-1 text-sm rounded bg-primary-600 hover:bg-primary-700 text-white save-btn">
                                        @lang('general.change')
                                    </button>
                                    {{--                                @endcan--}}
                                </div>
                            </td>

                            <!-- Blocked Balance -->
                            <td class="px-6 py-4 text-center whitespace-nowrap align-middle dynamic-input-group">
                                <div class="inline-flex items-center gap-1.5">
                                    <input type="text" name="block_balance"
                                           data-id="{{ $asset->id }}"
                                           value="{{ toCurrency($asset->block_balance ?? 0) }}"
                                           class="separator separator-signed w-44 px-3 py-1.5 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition duration-150">
                                    {{--                                @can('asset_create')--}}
                                    <button type="button"
                                            data-modal-target="confirm"
                                            data-id="{{ $asset->id }}"
                                            data-key="block_balance"
                                            data-symbol="{{ $asset->currency->symbol }}"
                                            data-current="{{ $asset->block_balance }}"
                                            class="modal-trigger px-3 py-1 text-sm rounded bg-primary-600 hover:bg-primary-700 text-white save-btn">
                                        @lang('general.change')
                                    </button>
                                    {{--                                @endcan--}}
                                </div>
                            </td>

                            <!-- Margin Balance -->
                            <td class="px-6 py-4 text-center whitespace-nowrap align-middle dynamic-input-group">
                                <div class="inline-flex items-center gap-1.5">
                                    <input type="text" name="margin_balance"
                                           data-id="{{ $asset->id }}"
                                           value="{{ toCurrency($asset->margin_balance ?? 0) }}"
                                           class="separator separator-signed w-44 px-3 py-1.5 text-sm font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white transition duration-150">
                                    {{--                                @can('asset_create')--}}
                                    <button type="button"
                                            data-modal-target="confirm"
                                            data-id="{{ $asset->id }}"
                                            data-key="margin_balance"
                                            data-symbol="{{ $asset->currency->symbol }}"
                                            data-current="{{ $asset->margin_balance }}"
                                            class="modal-trigger px-3 py-1 text-sm rounded bg-primary-600 hover:bg-primary-700 text-white save-btn">
                                        @lang('general.change')
                                    </button>
                                    {{--                                @endcan--}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400 font-medium">
                                @lang('general.no_data')
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>

            @if($assets->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/20">
                    {{ $assets->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </main>
@endsection
@section('modal')
    <!-- Approve manual Confirm Modal -->
    <div
        class="modal fixed inset-0 z-50 flex items-center justify-center bg-gray-200/40 dark:bg-gray-900/40 backdrop-blur-md hidden"
        data-modal-id="confirm">

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg p-6 relative">

            <button type="button"
                    data-modal-close
                    class="absolute top-3 left-3 text-gray-400 hover:text-red-500">
                ✕
            </button>

            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-5">
                @lang('general.change_balance')
            </h3>

            <form id="assetUpdateForm" method="POST">
                @csrf

                <input type="hidden" name="key" id="modal-key">

                <div class="space-y-4">

                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">

                        <div class="flex justify-between mb-2">
                            <span>@lang('general.currency')</span>
                            <span id="modal-symbol"></span>
                        </div>

                        <div class="flex justify-between">
                            <span>@lang('general.current_balance')</span>
                            <span id="modal-current-balance"
                                  class="font-bold text-primary-600"></span>
                        </div>

                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium">
                            @lang('general.new_balance')
                        </label>

                        <input type="text"
                               name="value"
                               id="modal-value"
                               class="separator separator-signed w-full px-4 py-3 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium">
                            @lang('general.description')
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="w-full px-4 py-3 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="@lang('general.description')"
                            required></textarea>
                    </div>

                    <div
                        class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-300 dark:border-yellow-700 rounded-lg p-3 text-sm">
                        ⚠️ @lang('general.balance_change_warning')
                    </div>

                    <button type="submit"
                            class="w-full py-3 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-semibold">
                        @lang('general.confirm')
                    </button>

                </div>

            </form>

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.save-btn').forEach(button => {

                button.addEventListener('click', function () {

                    const assetId = this.dataset.id;
                    const key = this.dataset.key;
                    const symbol = this.dataset.symbol;
                    const current = this.dataset.current;

                    document.getElementById('modal-key').value = key;

                    document.getElementById('modal-symbol').innerText = symbol;

                    document.getElementById('modal-current-balance').innerText =
                        Number(current).toLocaleString();

                    const input = this.closest('td').querySelector('input');

                    document.getElementById('modal-value').value = input.value;
                    document.getElementById('assetUpdateForm').action = `/admin/user/details/assets/update/${assetId}`;
                });

            });

        });
    </script>
@endsection
