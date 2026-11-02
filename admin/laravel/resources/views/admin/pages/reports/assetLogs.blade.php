@extends('admin.layout.master')

@section('title', trans('general.change_asset_by_admin'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.change_asset_by_admin')</h2>
            </div>
            @if(request()->filled('user') && is_numeric(request('user')))
                <a href="{{ route('admin.user.edit', request('user')) }}"
                   class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 shadow-sm dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    @lang('master.back')
                </a>
            @endif
        </div>
        @include('admin.pages.filter', [
       'filters' => $filters,
       'back' => request()->filled('user') && is_numeric(request('user'))
           ? route('admin.user.details.asset.logs', ['user' => request('user')])
           : route('admin.user.details.asset.logs'),
       ])
        <div
                class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="dark:bg-gray-800 dark:border-gray-700 w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="">
                    <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                        <th class="table-header py-4 font-medium">@lang('general.id')</th>
                        <th class="table-header py-4 font-medium">@lang('general.user')</th>
                        <th class="table-header py-4 font-medium">@lang('general.admin')</th>
                        <th class="table-header py-4 font-medium">@lang('general.currency')</th>
                        <th class="table-header py-4 font-medium">@lang('general.asset_type')</th>
                        <th class="table-header py-4 font-medium">@lang('general.old_balance')</th>
                        <th class="table-header py-4 font-medium">@lang('general.new_balance')</th>
                        <th class="table-header py-4 font-medium">@lang('general.description')</th>
                        <th class="table-header py-4 font-medium">@lang('general.created_at')</th>
                    </tr>
                    </thead>
                    <tbody
                            class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                    @forelse($logs as $log)
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$log?->id ?? ''}}</span>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <a href="{{route('admin.user.edit',$log?->user_id)}}">{{$log?->user->getFullInfo() ?? ''}}</a>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <a href="{{route('admin.admin.edit',$log?->admin_id)}}">{{$log?->admin->getFullInfo() ?? ''}}</a>
                            </td>

                            <td class="whitespace-nowrap max-xl:min-w-70">
                                {{$log?->asset?->symbol}}
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                {{\App\Models\AssetLog::receiveKey($log?->key)}}
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                {{toCurrency($log?->old_balance,10)}}
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                {{toCurrency($log?->new_balance,10)}}
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <button type="button" data-extra="{{$log->description}}"
                                        class="extra-data w-32  px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                    @lang('general.see')
                                </button>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                {{$log?->getCreatedAt()}}
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
            {{ $logs->withQueryString()->links('pagination::tailwind') }}
        </div>

    </main>
@endsection
@section('scripts')
    <script>
        document.querySelectorAll('.extra-data').forEach((button) => {
            button.addEventListener('click', (event) => {
                mboxNotice(event.currentTarget.dataset.extra)
            })
        })
    </script>
@endsection


