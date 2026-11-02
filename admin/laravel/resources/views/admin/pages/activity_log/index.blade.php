@extends('admin.layout.master')

@section('title', trans('master.admin_activity_log'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('master.admin_activity_log')</h2>
            </div>

            <div class="mt-4 md:mt-0">
                {{--                    @if($previousRouteName && request('user') && $previousRouteName == 'admin.user.edit')--}}
                {{--                    <a href="{{route('admin.user.edit',['user'=>request('user')])}}"--}}
                {{--                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">--}}
                {{--                        @lang('master.back')--}}
                {{--                    </a>--}}
                {{--                    @endif--}}
                {{--                    <a href="{{route('admin.user.card.add')}}"--}}
                {{--                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">--}}
                {{--                        @lang('general.add_new_card')--}}
                {{--                    </a>--}}
                <a href="{{ route('admin.activity.log.export') }}"
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    @lang('general.download_excel')
                </a>

            </div>

        </div>

        @include('admin.pages.filter',['filters' => $filters,'back'=>route('admin.activity.log.index')])
        <div class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="table-header py-4 font-medium text-center">@lang('general.admin')</th>
                        <th class="table-header py-4 font-medium text-center">@lang('general.ip')</th>
                        <th class="table-header py-4 font-medium text-center">@lang('general.section')</th>
                        <th class="table-header py-4 font-medium text-center">@lang('general.section_identifier')</th>
                        <th class="table-header py-4 font-medium text-center">@lang('general.operation')</th>
                        <th class="table-header py-4 font-medium text-center">@lang('general.request_date')</th>
                        <th class="table-header py-4 font-medium text-center">@lang('general.changes')</th>{{--                        <th class="table-header py-4 font-medium text-center">@lang('general.operation')</th>--}}
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $log)
                        <tr>
                            <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-gray-100">
                                @if($log->admin)
                                    <span>{{$log->admin->name . " ". $log->admin->family. " (". $log->admin->id .")"}}</span>
                                @else
                                    -
                                @endif

                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-gray-100">
                               <span class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $log->ip ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-gray-100">
                               <span class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ model_display_name($log->model_type) ?? '-' }}
                                </span>
                            </td>


                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $log->model_id ?? '-' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ route_display_name($log->route) ?? '-' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-900 dark:text-gray-100">
                                  {!! $log->getCreatedAt() ?? '-'  !!}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center space-y-1">
                                @if($log->changes)
                                    <button
                                        class="text-blue-600 hover:underline text-sm"
                                        onclick="openChangesModal(  {{ $log->id }},'{{ (new $log->model_type)->getTable() }}')">
                                        @lang('general.view')
                                    </button>
                                @else
                                    -
                                @endif

                                {{--                                        <span class="text-gray-400">→</span>--}}

                                {{--                                        <span class="text-green-600 font-semibold">--}}
                                {{--                                            {{ $change->new ?? '-' }}--}}
                                {{--                                        </span>--}}
                                {{--                                @endforeach--}}
                            </td>

                            <td class="whitespace-nowrap max-xl:min-w-70 text-center">
                                {{--                                <button onclick="mboxDelete('{{route('admin.activity.log.destroy',$log)}}')"   class="px-2 py-2 border rounded-lg text-sm font-medium text-white bg-danger dark:bg-danger hover:bg-danger dark:hover:bg-danger border-danger dark:border-danger">@lang('general.delete')</button>--}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                @lang('general.no_data')
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $logs->links('pagination::tailwind') }}
        </div>
    </main>

@endsection

@include("admin.pages.activity_log.change_modal")

@php
    $logsData = $logs->mapWithKeys(function ($log) {
        return [
            $log->id => [
                'changes' => $log->changes ? json_decode($log->changes, true) : [],
                'model_type' => $log->model_type,
            ]
        ];
    });
@endphp
@section('scripts')
    <script>
        window.tableTranslations = @json(trans('table'));
    </script>
    <script>
        const changesData = @json(
            $logs->pluck('changes', 'id')
        );

        const logsData = @json($logsData);

        {{--function openChangesModal(id) {--}}
        {{--    const modal = document.getElementById('changesModal');--}}
        {{--    const content = document.getElementById('detailLogContent');--}}

        {{--    content.innerHTML = '';--}}

        {{--    const changes = JSON.parse(changesData[id] ?? '{}');--}}

        {{--    const fieldLabels = @json(--}}
        {{--        collect(json_decode($log->changes, true))--}}
        {{--            ->keys()--}}
        {{--            ->mapWithKeys(fn($f) => [$f => model_field_label($log->model_type, $f)])--}}
        {{--    );--}}

        {{--    Object.entries(changes).forEach(([field, change]) => {--}}
        {{--        content.innerHTML += `--}}
        {{--            <div class="flex items-center gap-2 text-sm">--}}
        {{--                <span class="font-medium text-gray-700">${fieldLabels[field] ?? field}</span>:--}}
        {{--                <span class="text-red-600 line-through">${change.old}</span>--}}
        {{--                 <span class="text-gray-400"> => </span>--}}
        {{--                <span class="text-green-600 font-semibold">${change.new}</span>--}}
        {{--            </div>--}}
        {{--        `;--}}
        {{--    });--}}

        {{--    modal.classList.remove('hidden');--}}
        {{--    modal.classList.add('flex');--}}
        {{--}--}}

        function openChangesModal(id, table) {
            const modal = document.getElementById('changesModal');
            const content = document.getElementById('changesModalContent');

            content.innerHTML = '';

            if (!logsData[id] || !logsData[id].changes) {
                content.innerHTML = '<span>-</span>';
                return;
            }

            const changes = logsData[id].changes;
            const modelType = logsData[id].model_type;

            Object.entries(changes).forEach(([field, change]) => {

                const label =
                    window.tableTranslations?.[table]?.[field] ?? field;

                content.innerHTML += `
            <div class="flex items-center gap-2 text-sm">
                <span class="font-medium text-gray-700">
                    ${label}
                </span>:
                <span class="text-red-600 line-through">
                    ${change.old ?? '-'}
                </span>
                <span class="text-gray-400"> → </span>
                <span class="text-green-600 font-semibold">
                    ${change.new ?? '-'}
                </span>
            </div>
        `;
            });

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeChangesModal() {
            document.getElementById('changesModal').classList.add('hidden');
        }
    </script>

@endsection

