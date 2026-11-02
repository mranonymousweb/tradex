@extends('admin.layout.master')

@section('title', trans('general.admin_chats'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.admin_chats')</h2>
            </div>
        </div>
        @include('admin.pages.filter',['filters' => $filters,'back'=>route('admin.setting.chats')])
        <div
            class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="dark:bg-gray-800 dark:border-gray-700 w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="">
                    <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                        <th class="table-header py-4 font-medium">@lang('general.id')</th>
                        <th class="table-header py-4 font-medium">@lang('general.user_sender')</th>
                        <th class="table-header py-4 font-medium">@lang('general.user_receive')</th>
                        <th class="table-header py-4 font-medium">@lang('general.chat_message')</th>
                        <th class="table-header py-4 font-medium">@lang('general.seen_at')</th>
                        <th class="table-header py-4 font-medium">@lang('general.created_at') </th>
                    </tr>
                    </thead>
                    <tbody
                        class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                    @forelse($chats as $chat)
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$chat->id ?? '-'}}</span>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{ optional($chat->fromAdmin)->getFullInfo() ?? '-' }}</span>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{ optional($chat->toAdmin)->getFullInfo() ?? '-' }}</span>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <button type="button" data-extra="{{$chat->message}}"
                                        class="extra-data w-32  px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                    @lang('general.see')
                                </button>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$chat->getSeenAt()}}</span>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$chat->getCreatedAt()}}</span>
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
            {{ $chats->links('pagination::tailwind') }}
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
