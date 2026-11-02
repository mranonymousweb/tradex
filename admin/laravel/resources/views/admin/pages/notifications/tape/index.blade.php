@extends('admin.layout.master')

@section('title', trans('general.tape_notification'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.tape_notification')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.notification.tape.create')}}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                    @lang('general.save')
                </a>
            </div>

        </div>
        @include('admin.pages.filter',['filters' => $filters,'back'=>route('admin.notification.tape.index')])
        <div
            class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="dark:bg-gray-800 dark:border-gray-700 w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="">
                    <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                        <th class="table-header py-4 font-medium">@lang('general.id')</th>
                        <th class="table-header py-4 font-medium">@lang('general.title')</th>
                        <th class="table-header py-4 font-medium">@lang('general.link')</th>
                        <th class="table-header py-4 font-medium">@lang('general.status')</th>
                        <th class="table-header py-4 font-medium">@lang('general.created_at')</th>
                        <th class="table-header py-4 font-medium">@lang('general.operation')</th>
                    </tr>
                    </thead>
                    <tbody
                        class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                    @forelse($notifications as $notification)
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$notification?->id ?? ''}}</span>
                            </td>

                            <td class="whitespace-nowrap max-xl:min-w-70">
    <span>
        {{
            \Illuminate\Support\Str::limit(
               $notification?->getTranslation('title',$lang) ?? '',
                30
            )
        }}
    </span>
                            </td>


                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <button type="button"
                                        data-description="{{$notification->getTranslation('description',$lang)}}"
                                        class="description-button w-32  px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                    @lang('general.see')
                                </button>
                            </td>

                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{\App\Models\Notification::getStatus($notification->status)}}</span>
                            </td>

                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$notification->getCreatedAt()}}</span>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <a href="{{route('admin.notification.tape.edit',['id'=>$notification->id])}}"
                                   class="px-3 py-1 border rounded-lg text-sm font-medium text-white bg-primary-600 dark:bg-primary-500 hover:bg-primary-500 dark:hover:bg-primary-600 border-primary-600 dark:border-primary-500">@lang('general.edit')</a>
                                <button
                                    onclick="mboxDelete('{{ route('admin.notification.tape.destroy',['id'=>$notification->id]) }}')"
                                    type="button"
                                    class="px-2 py-2 border rounded-lg text-sm font-medium text-white bg-danger">
                                    @lang('general.delete')
                                </button>

                                <form id="delete-form" method="POST" style="display:none">
                                    @csrf
                                    @method('DELETE')
                                </form>

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
            {{ $notifications->withQueryString()->links('pagination::tailwind') }}
        </div>

    </main>
@endsection
@section('scripts')
    <script>
        document.querySelectorAll('.description-button').forEach((button) => {
            button.addEventListener('click', (event) => {
                mboxNotice(event.currentTarget.dataset.description)
            })
        })
    </script>
@endsection


