@if (filled($record->supplier_request) || filled($record->supplier_response))
    @php
        $supplierStatus = $record->supplier_response['status'] ?? null;
        $supplierStatusLabel = ((int) $supplierStatus) === 1 ? __('general.confirmed') : __('general.rejected');
        $supplierStatusClass =
            ((int) $supplierStatus) === 1
                ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300';
    @endphp
    <div class="no-print space-y-4 mt-8">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="w-2 h-6 bg-primary-600 rounded-full"></span>
                @lang('general.supplier_exchange_data')
            </h3>
            @if (filled($record->supplier_response) && !is_null($supplierStatus))
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $supplierStatusClass }}">
                    {{ $supplierStatusLabel }}
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div
                    class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 bg-blue-50/60 dark:bg-blue-900/10 flex items-center justify-between">
                    <h4 class="text-sm font-bold text-blue-700 dark:text-blue-300">@lang('general.supplier_request')</h4>
                    <span class="text-[10px] uppercase tracking-wider font-bold text-blue-400">Request</span>
                </div>
                <div class="p-5">
                    @if (filled($record->supplier_request))
                        <div class="space-y-2">
                            @foreach ($record->supplier_request ?? [] as $key => $value)
                                <div
                                    class="flex items-start justify-between gap-4 py-2 border-b border-gray-100 dark:border-gray-700/70 last:border-b-0">
                                    <span
                                        class="text-xs font-semibold text-gray-500 shrink-0">{{ Str::headline($key) }}</span>
                                    <span class="text-xs font-mono text-gray-800 dark:text-gray-200 text-end break-all">
                                        @if (is_array($value))
                                            <code
                                                class="block text-[11px] bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-lg p-2 whitespace-pre-wrap">{{ json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</code>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <details class="mt-4 group">
                            <summary
                                class="cursor-pointer text-xs font-bold text-primary-600 dark:text-primary-400 hover:underline">
                                JSON
                            </summary>
                            <pre
                                class="mt-3 text-[11px] leading-relaxed whitespace-pre-wrap bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-xl p-4 overflow-x-auto text-gray-800 dark:text-gray-200">{{ json_encode($record->supplier_request, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                        </details>
                    @else
                        <span class="text-sm text-gray-400 italic">-</span>
                    @endif
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div
                    class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 bg-emerald-50/60 dark:bg-emerald-900/10 flex items-center justify-between">
                    <h4 class="text-sm font-bold text-emerald-700 dark:text-emerald-300">@lang('general.supplier_response')</h4>
                    <span class="text-[10px] uppercase tracking-wider font-bold text-emerald-400">Response</span>
                </div>
                <div class="p-5">
                    @if (filled($record->supplier_response))
                        @if (!empty($record->supplier_response['description']))
                            <div
                                class="mb-4 rounded-xl border border-red-100 dark:border-red-900/30 bg-red-50 dark:bg-red-900/10 p-4">
                                <p class="text-[10px] uppercase tracking-wider font-bold text-red-400 mb-1">Description
                                </p>
                                <p class="text-sm text-red-700 dark:text-red-300 break-all">
                                    {{ $record->supplier_response['description'] }}</p>
                            </div>
                        @endif

                        <div class="space-y-2">
                            @foreach ($record->supplier_response ?? [] as $key => $value)
                                @continue($key === 'response' && is_array($value))
                                <div
                                    class="flex items-start justify-between gap-4 py-2 border-b border-gray-100 dark:border-gray-700/70 last:border-b-0">
                                    <span
                                        class="text-xs font-semibold text-gray-500 shrink-0">{{ Str::headline($key) }}</span>
                                    <span class="text-xs font-mono text-gray-800 dark:text-gray-200 text-end break-all">
                                        @if ($key === 'status')
                                            <span
                                                class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold {{ ((int) $value) === 1 ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' }}">
                                                {{ ((int) $value) === 1 ? __('general.confirmed') : __('general.rejected') }}
                                            </span>
                                        @elseif(is_array($value))
                                            <code
                                                class="block text-[11px] bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-lg p-2 whitespace-pre-wrap">{{ json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</code>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        @if (!empty($record->supplier_response['response']) && is_array($record->supplier_response['response']))
                            <div
                                class="mt-5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-900/20 p-4">
                                <h5 class="text-xs uppercase tracking-wider font-bold text-gray-400 mb-3">Response
                                    Details</h5>
                                <div class="space-y-2">
                                    @foreach ($record->supplier_response['response'] as $key => $value)
                                        <div
                                            class="flex items-start justify-between gap-4 py-2 border-b border-gray-100 dark:border-gray-700/70 last:border-b-0">
                                            <span
                                                class="text-xs font-semibold text-gray-500 shrink-0">{{ Str::headline($key) }}</span>
                                            <span
                                                class="text-xs font-mono text-gray-800 dark:text-gray-200 text-end break-all">
                                                {{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <details class="mt-4 group">
                            <summary
                                class="cursor-pointer text-xs font-bold text-primary-600 dark:text-primary-400 hover:underline">
                                JSON
                            </summary>
                            <pre
                                class="mt-3 text-[11px] leading-relaxed whitespace-pre-wrap bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-xl p-4 overflow-x-auto text-gray-800 dark:text-gray-200">{{ json_encode($record->supplier_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                        </details>
                    @else
                        <span class="text-sm text-gray-400 italic">-</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
