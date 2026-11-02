@extends('admin.layout.master')

@section('title', trans('master.system_logs'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <nav class="flex mb-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                        <span>@lang('general.settings')</span>
                        <span class="mx-2">/</span>
                        <span>@lang('general.admin.setting.monitor')</span>
                        <span class="mx-2">/</span>
                        <span class="text-primary-600">@lang('general.system_logs')</span>
                    </nav>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        @lang('general.system_logs')
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @lang('general.monitor_system_logs')
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.setting.monitor') }}"
                       class="inline-flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm transition-all dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        @lang('master.back')
                    </a>
                    <button
                        onclick="mboxDelete('{{ route('admin.monitor.system.log.delete') }}')"
                        type="button"
                        class="inline-flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl bg-red-600 hover:bg-red-700 text-white shadow-sm transition-all">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        @lang('general.delete_all')
                    </button>
                    <form id="delete-form" method="POST" style="display:none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>

        @php
            $levelStyles = [
                'EMERGENCY' => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                'ALERT'     => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                'CRITICAL'  => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                'ERROR'     => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                'WARNING'   => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800',
                'NOTICE'    => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
                'INFO'      => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
                'DEBUG'     => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600',
            ];
            $errorLevels = ['EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR'];
            $pageErrorCount = $logs->getCollection()->filter(fn ($log) => in_array(strtoupper($log->level ?? ''), $errorLevels, true))->count();
        @endphp

        <div class="max-w-7xl mx-auto space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('general.total_logs')</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($logs->total()) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">@lang('general.error')</p>
                    <p class="mt-2 text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($pageErrorCount) }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h3 class="font-bold text-gray-800 dark:text-white">@lang('general.system_logs')</h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }} / {{ $logs->total() }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/20">
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center w-16">#</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center w-28">Level</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-start">@lang('general.description')</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-start w-56">File</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center w-24">Env</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center w-40">@lang('general.date')</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-center w-24">@lang('general.operation')</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($logs as $index => $log)
                            @php
                                $level = strtoupper($log->level ?? 'INFO');
                                $badgeClass = $levelStyles[$level] ?? $levelStyles['DEBUG'];
                                $extra = is_array($log->extra ?? null) ? $log->extra : (json_decode($log->extra ?? '{}', true) ?: []);
                                $file = $extra['file'] ?? null;
                                $line = $extra['line'] ?? null;
                                $shortFile = $file ? basename(str_replace('\\', '/', $file)) : null;
                            @endphp
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-4 py-4 text-center text-xs font-mono text-gray-400">
                                    {{ $logs->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold border {{ $badgeClass }}">
                                        {{ $level }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-start">
                                    <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed break-words">
                                        {{ \Illuminate\Support\Str::limit($log->message ?? '-', 120) }}
                                    </p>
                                </td>
                                <td class="px-4 py-4 text-start">
                                    @if($shortFile)
                                        <div class="text-xs font-mono text-gray-600 dark:text-gray-300 truncate max-w-[14rem]" title="{{ $file }}">
                                            {{ $shortFile }}@if($line)<span class="text-primary-600 dark:text-primary-400">:{{ $line }}</span>@endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-[11px] font-medium
                                        {{ ($log->environment ?? '') === 'production' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                                        {{ $log->environment ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ $log->datetime ?? $log->getCreatedAt() ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-primary-600 border border-primary-200 hover:bg-primary-50 dark:border-primary-800 dark:hover:bg-primary-900/20 transition-colors log-detail-btn"
                                        data-log-index="{{ $index }}">
                                        @lang('general.view')
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center text-gray-400">
                                        <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-sm">@lang('general.no_data')</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                        {{ $logs->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection

@include('admin.pages.monitor.logs.log_detail_modal')

@section('scripts')
    @php
        $logsForJs = $logs->getCollection()->values()->map(function ($log) {
            $extra = is_array($log->extra ?? null) ? $log->extra : (json_decode($log->extra ?? '{}', true) ?: []);
            $context = is_array($log->context ?? null) ? $log->context : (json_decode($log->context ?? '{}', true) ?: []);
            return [
                'level' => $log->level ?? '-',
                'message' => $log->message ?? '-',
                'environment' => $log->environment ?? '-',
                'datetime' => $log->datetime ?? ($log->created_at ? (string) $log->created_at : '-'),
                'context' => $context,
                'extra' => $extra,
            ];
        })->values();
    @endphp
    <script>
        const systemLogs = @js($logsForJs);

        const modal = document.getElementById('detailLogModal');
        const content = document.getElementById('detailLogContent');

        function escapeHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function formatJsonBlock(data) {
            if (!data || (typeof data === 'object' && Object.keys(data).length === 0)) {
                return '';
            }
            try {
                return `<pre class="text-[11px] font-mono whitespace-pre-wrap break-all bg-gray-50 dark:bg-gray-900 rounded-lg p-2 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 max-h-28 overflow-y-auto">${escapeHtml(JSON.stringify(data, null, 2))}</pre>`;
            } catch (e) {
                return '';
            }
        }

        function hasJsonData(data) {
            return data && typeof data === 'object' && Object.keys(data).length > 0;
        }

        function levelBadgeClass(level) {
            const map = {
                EMERGENCY: 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                ALERT: 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                CRITICAL: 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                ERROR: 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                WARNING: 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800',
                NOTICE: 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
                INFO: 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800',
                DEBUG: 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600',
            };
            return map[String(level || '').toUpperCase()] || map.DEBUG;
        }

        function openDetailModal(index) {
            const log = systemLogs[index];
            if (!log) return;

            const extra = log.extra || {};
            const file = extra.file || '-';
            const line = extra.line || '-';
            const trace = extra.trace_summary || [];
            const extraWithoutKnown = { ...extra };
            delete extraWithoutKnown.file;
            delete extraWithoutKnown.line;
            delete extraWithoutKnown.trace_summary;

            content.innerHTML = `
                <div class="flex flex-wrap items-center gap-2 mb-3 pb-3 border-b border-gray-100 dark:border-gray-700">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border ${levelBadgeClass(log.level)}">${escapeHtml(log.level)}</span>
                    ${log.environment && log.environment !== '-' ? `<span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">${escapeHtml(log.environment)}</span>` : ''}
                    <span class="text-[10px] text-gray-400 mr-auto">${escapeHtml(log.datetime)}</span>
                </div>

                <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed break-words mb-3">${escapeHtml(log.message)}</p>

                ${file !== '-' ? `
                <div class="text-[11px] font-mono text-gray-500 dark:text-gray-400 mb-3 truncate" title="${escapeHtml(file)}">
                    ${escapeHtml(file)}${line !== '-' ? `<span class="text-primary-600 dark:text-primary-400">:${escapeHtml(line)}</span>` : ''}
                </div>` : ''}

                ${trace.length ? `
                <details class="mb-2 group">
                    <summary class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 cursor-pointer select-none">Trace (${trace.length})</summary>
                    <ul class="mt-1 text-[10px] font-mono space-y-0.5 text-gray-500 dark:text-gray-400 pl-2 border-r-2 border-gray-200 dark:border-gray-700">
                        ${trace.map(item => `<li class="break-all">${escapeHtml(item)}</li>`).join('')}
                    </ul>
                </details>` : ''}

                ${hasJsonData(log.context) ? `
                <details class="mb-2 group">
                    <summary class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 cursor-pointer select-none">Context</summary>
                    <div class="mt-1">${formatJsonBlock(log.context)}</div>
                </details>` : ''}

                ${hasJsonData(extraWithoutKnown) ? `
                <details class="group">
                    <summary class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 cursor-pointer select-none">Extra</summary>
                    <div class="mt-1">${formatJsonBlock(extraWithoutKnown)}</div>
                </details>` : ''}
            `;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        document.querySelectorAll('.log-detail-btn').forEach((button) => {
            button.addEventListener('click', () => openDetailModal(Number(button.dataset.logIndex)));
        });
    </script>
@endsection
