@extends('admin.layout.master')

@section('title', $title)

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $title }}</h2>
            <a href="{{ $parentBack }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200">
                @lang('master.back')
            </a>
        </div>

        @include('admin.pages.filter', ['filters' => $filters, 'back' => $back])

        @php
            $patternLabelByKey = collect(config('patterns.user', []))
                ->merge(config('patterns.admin', []))
                ->pluck('label', 'key');

            $supplierLabels = [
                \App\Models\Option::SMS_SUPPLIER_MELI_PAYAMAK => __('general.meli_payamak'),
                \App\Models\Option::SMS_SUPPLIER_LEMON_SMS => __('general.lemon_sms'),
                'sms_to' => __('general.plugin_sms_to_setting'),
                'smtp' => __('general.email_setting'),
                'mail' => __('general.email_setting'),
            ];
        @endphp

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 text-start">#</th>
                        <th class="px-4 py-3 text-start">@lang('general.status')</th>
                        <th class="px-4 py-3 text-start">@lang('general.log_pattern')</th>
                        <th class="px-4 py-3 text-start">@lang('general.supplier')</th>
                        <th class="px-4 py-3 text-start">@lang('general.recipient')</th>
                        <th class="px-4 py-3 text-start">@lang('general.user')</th>
                        <th class="px-4 py-3 text-start">@lang('general.date')</th>
                        <th class="px-4 py-3 text-start">@lang('general.details')</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($logs as $log)
                        @php
                            $statusClass = match ($log->status) {
                                \App\Models\NotificationLogs::STATUS_SUCCESS => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                                \App\Models\NotificationLogs::STATUS_FAIL => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                default => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-700/30 align-top">
                            <td class="px-4 py-3 whitespace-nowrap">{{ $log->id }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $statusClass }}">
                                    {{ \App\Models\NotificationLogs::statusLabels()[$log->status] ?? $log->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs max-w-[200px]">
                                @php
                                    $patternKey = $log->pattern;
                                    if (! $patternLabelByKey->has($patternKey)) {
                                        $patternKey = $log->section;
                                    }
                                    $patternLabelPath = $patternKey ? ($patternLabelByKey[$patternKey] ?? null) : null;
                                @endphp
                                @if($patternLabelPath)
                                    @lang($patternLabelPath)
                                @elseif($log->pattern)
                                    {{ $log->pattern }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $log->supplier ? ($supplierLabels[$log->supplier] ?? $log->supplier) : '—' }}
                            </td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $log->recipient ?? '—' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($log->notifiable)
                                    <x-admin.user-profile :user="$log->notifiable" />
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs">{{ $log->getCreateAt() }}</td>
                            <td class="px-4 py-3 text-xs max-w-md">
                                @if($log->description)
                                    <details class="cursor-pointer">
                                        <summary class="text-primary-600 dark:text-primary-400">@lang('general.error')</summary>
                                        <pre class="mt-2 whitespace-pre-wrap break-all text-[11px] text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/20 p-2 rounded-lg">{{ $log->description }}</pre>
                                    </details>
                                @endif
                                @if($log->response)
                                    <details class="cursor-pointer mt-1">
                                        <summary class="text-gray-500">@lang('general.response')</summary>
                                        <pre class="mt-2 whitespace-pre-wrap break-all text-[11px] bg-gray-50 dark:bg-gray-900/50 p-2 rounded-lg">{{ $log->response }}</pre>
                                    </details>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-16 text-center text-gray-500">@lang('general.not_found')</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                    {{ $logs->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </main>
@endsection
