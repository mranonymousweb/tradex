@extends('admin.layout.master')

@section('title', trans('setting.futures'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-background-dark">
        @include('admin.pages.future.page-header')

        <div class="space-y-6 max-w-full mx-auto">

            <section class="bg-white dark:bg-card-dark rounded-xl shadow-soft border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">@lang('general.future.global_config')</h3>
                </div>

                <form id="future-options-form" action="{{ route('admin.option.save') }}" method="post" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        {{-- Group 1: Interest & Fees --}}
                        <div class="space-y-2 md:col-span-2 lg:col-span-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="lg:col-span-4 border-b border-gray-50 dark:border-gray-800">
                                <span class="text-xs font-semibold uppercase tracking-wider text-primary-600">@lang('general.future.rates_and_fees')</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang('general.future.interest_rate') <span class="text-red-500">*</span></label>
                                <input required type="text" name="{{ config('constants.option.future_interest_rate') }}" value="{{ old(config('constants.option.future_interest_rate'), $options[config('constants.option.future_interest_rate')] ?? '') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang('general.future.long_fee_percentage') %</label>
                                <input type="text" required name="{{ config('constants.option.future_long_position_fee') }}" value="{{ old(config('constants.option.future_long_position_fee'), $options[config('constants.option.future_long_position_fee')] ?? '') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang('general.future.short_fee_percentage') %</label>
                                <input type="text" required name="{{ config('constants.option.future_short_position_fee') }}" value="{{ old(config('constants.option.future_short_position_fee'), $options[config('constants.option.future_short_position_fee')] ?? '') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang('general.future.periodic_profit_percentage') %</label>
                                <input type="text" required name="{{ config('constants.option.future_periodic_profit') }}" value="{{ old(config('constants.option.future_periodic_profit'), $options[config('constants.option.future_periodic_profit')] ?? '') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        {{-- Group 2: Periods (stored as UTC, shown in browser timezone) --}}
                        <div class="space-y-2 md:col-span-2 lg:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-3 border-b border-gray-50 dark:border-gray-800">
                                <span class="text-xs font-semibold uppercase tracking-wider text-primary-600">@lang('general.future.timing_periods')</span>
                            </div>

                            @foreach(['first', 'second', 'third'] as $period)
                                @php $field = config("constants.option.future_{$period}_period"); @endphp
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang("general.future.{$period}_period")</label>
                                    <input
                                        type="time"
                                        required
                                        name="{{ $field }}"
                                        value="{{ old($field, $options[$field] ?? '') }}"
                                        data-utc-time
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                                    >
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4 pt-2 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg shadow-md transition-all">
                            @lang('general.save')
                        </button>
                    </div>
                </form>
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Leverage Table --}}
                <div class="lg:col-span-2 bg-white dark:bg-card-dark rounded-xl shadow-soft border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <table class="w-full text-sm text-start">
                        <thead class="bg-gray-50 dark:bg-gray-800 text-gray-600 dark:text-gray-300 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4 font-bold text-start">@lang('general.future.leverage')</th>
                            <th class="px-6 py-4 font-bold text-center">@lang('general.future.long_percentage')</th>
                            <th class="px-6 py-4 font-bold text-end">@lang('general.future.short_percentage')</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($long as $key => $longValue)
                            @php
                                $leverage = (int) str_replace('leverage_', '', $key);
                                $shortValue = $short["leverage_$leverage"] ?? '-';
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white text-start">{{ $leverage }}x</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-green-600 dark:text-green-400 font-medium">{{ $longValue }}%</span>
                                </td>
                                <td class="px-6 py-4 text-end">
                                    <span class="text-red-600 dark:text-red-400 font-medium">{{ $shortValue }}%</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-400">@lang('general.no_data_available')</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- File Upload Card --}}
                <div class="lg:col-span-1 bg-white dark:bg-card-dark rounded-xl shadow-soft border border-gray-100 dark:border-gray-700 p-6 h-fit">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">@lang('general.future.update_fees_via_file')</h3>

                    <form action="{{ route('admin.future.update.fee') }}" method="post" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="p-4 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-lg text-center hover:border-primary-500 transition-colors" id="drop-zone">
                            <input required type="file" name="file" id="file-upload" class="hidden" onchange="updateFileName()">
                            <label for="file-upload" class="cursor-pointer flex flex-col items-center">
                                {{-- Icon Change based on state --}}
                                <div id="upload-icon-container">
                                    <svg class="w-10 h-10 text-gray-400 mb-2" id="upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>

                                {{-- Dynamic Text --}}
                                <span id="file-name-text" class="text-sm text-gray-500 font-medium">@lang('general.select_csv_xlsx')</span>
                                <span id="file-size-info" class="text-xs text-gray-400 mt-1 hidden"></span>
                            </label>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 shadow-md transition-all font-semibold">
                            @lang('general.upload_and_process')
                        </button>

                        <a href="{{ route('admin.future.download.config') }}" class="flex items-center justify-center text-sm text-primary-600 hover:text-primary-700 font-medium">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            @lang('general.future.download_config_file')
                        </a>
                    </form>
                </div>

            </section>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        function updateFileName() {
            const input = document.getElementById('file-upload');
            const fileNameText = document.getElementById('file-name-text');
            const fileSizeInfo = document.getElementById('file-size-info');
            const iconContainer = document.getElementById('upload-icon-container');
            const dropZone = document.getElementById('drop-zone');

            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Update the text to the filename
                fileNameText.innerText = file.name;
                fileNameText.classList.remove('text-gray-500');
                fileNameText.classList.add('text-primary-600', 'dark:text-primary-400');

                // Show file size
                const sizeInMb = (file.size / (1024 * 1024)).toFixed(2);
                fileSizeInfo.innerText = `${sizeInMb} MB`;
                fileSizeInfo.classList.remove('hidden');

                // Change UI to "Success/Selected" state
                dropZone.classList.add('border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/10');
                iconContainer.innerHTML = `
            <svg class="w-10 h-10 text-primary-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        `;
            }
        }

        function padTimePart(value) {
            return String(value).padStart(2, '0');
        }

        function parseTimeParts(time) {
            if (!time || typeof time !== 'string') {
                return null;
            }

            const match = time.trim().match(/^(\d{1,2}):(\d{2})(?::(\d{2}))?$/);
            if (!match) {
                return null;
            }

            return {
                hours: Number(match[1]),
                minutes: Number(match[2]),
                seconds: Number(match[3] || 0),
            };
        }

        function formatTimeValue(hours, minutes) {
            return `${padTimePart(hours)}:${padTimePart(minutes)}`;
        }

        // DB value is UTC; convert to the browser/system timezone for display.
        function utcTimeToLocal(utcTime) {
            const parts = parseTimeParts(utcTime);
            if (!parts) {
                return utcTime;
            }

            const date = new Date();
            date.setUTCHours(parts.hours, parts.minutes, parts.seconds, 0);

            return formatTimeValue(date.getHours(), date.getMinutes());
        }

        // Input value is local; convert to UTC before saving.
        function localTimeToUtc(localTime) {
            const parts = parseTimeParts(localTime);
            if (!parts) {
                return localTime;
            }

            const date = new Date();
            date.setHours(parts.hours, parts.minutes, parts.seconds, 0);

            return formatTimeValue(date.getUTCHours(), date.getUTCMinutes());
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('future-options-form');
            const timeInputs = document.querySelectorAll('[data-utc-time]');

            timeInputs.forEach(function (input) {
                if (input.value) {
                    input.value = utcTimeToLocal(input.value);
                }
            });

            if (!form) {
                return;
            }

            form.addEventListener('submit', function () {
                timeInputs.forEach(function (input) {
                    if (input.value) {
                        input.value = localTimeToUtc(input.value);
                    }
                });
            });
        });
    </script>
@endsection
