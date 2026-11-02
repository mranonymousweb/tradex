@extends('admin.layout.master')

@section('title', trans('general.bonus_reports'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.bonus_reports')</h2>
            </div>
        </div>
        @include('admin.pages.filter',['filters' => $filters,'back'=>route('admin.setting.bonus.logs')])
        <div
            class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="dark:bg-gray-800 dark:border-gray-700 w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="">
                    <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                        <th class="table-header py-4 font-medium">@lang('general.id')</th>
                        <th class="table-header py-4 font-medium">@lang('general.user')</th>
                        <th class="table-header py-4 font-medium">@lang('general.currency')</th>
                        <th class="table-header py-4 font-medium">@lang('general.section')</th>
                        <th class="table-header py-4 font-medium">@lang('general.amount')</th>
                        <th class="table-header py-4 font-medium">@lang('general.status')</th>
                        <th class="table-header py-4 font-medium">@lang('general.expire_at')</th>
                        <th class="table-header py-4 font-medium">@lang('general.created_at') </th>
                    </tr>
                    </thead>
                    <tbody
                        class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                    @forelse($logs as $log)
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$log->id}}</span>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <a href="{{route('admin.user.edit',$log?->user)}}">{{$log?->user->getFullInfo() ?? ''}}</a>

                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$log->symbol ?? ''}}</span>
                            </td>

                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{\App\Models\Bonus::getSection($log->section)}}</span>
                            </td>

                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{toCurrency($log->amount)}}</span>
                            </td>

                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{\App\Models\BonusLog::getStatus($log->status)}}</span>
                            </td>

                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$log->getFinishAt()}}</span>
                            </td>

                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$log->getCreateAt()}}</span>
                            </td>

                        </tr>
                    @empty
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                            <td class="whitespace-nowrap" colspan="6">
                                <span> @lang('general.not_found')</span>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            {{ $logs->links('pagination::tailwind') }}
        </div>
    </main>
@endsection

