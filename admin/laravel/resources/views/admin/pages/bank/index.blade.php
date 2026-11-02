@extends('admin.layout.master')

@section('title', trans('general.banks'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.banks')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                @can('admin.bank.store')
                    <a href="{{route('admin.bank.add')}}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                        @lang('general.add_bank')
                    </a>
                @endcan
            </div>
        </div>

        @include('admin.pages.filter',['filters' => $filters,'back'=>route('admin.card.index')])

        <div class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="table-header py-4 font-medium text-center">@lang('general.id')</th>
                        <th class="table-header py-4 font-medium text-center">@lang('general.icon')</th>
                        <th class="table-header py-4 font-medium text-center">@lang('general.name')</th>
                        <th class="table-header py-4 font-medium text-center">@lang('general.status')</th>
                        <th class="table-header py-4 font-medium text-center">@lang('general.operation')</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($banks as $bank)
                        <tr>
                            <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-gray-100">{{$bank->id}}</td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <div class="flex items-center justify-center w-full">
                                    <img class="w-10 h-10" loading="lazy" src="{{$bank->img}}" alt="{{$bank->name}}">
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-900 dark:text-gray-100">
                                {{ $bank->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">{!! $bank->getStatus() ?? '-' !!}</td>
                            <td class="whitespace-nowrap max-xl:min-w-70 text-center">
                                @can('admin.bank.details')
                                    <a href="{{route('admin.bank.details',$bank)}}"
                                       class="px-2 py-2 border rounded-lg text-sm font-medium text-white bg-primary-600 dark:bg-primary-500 hover:bg-primary-500 dark:hover:bg-primary-600 border-primary-600 dark:border-primary-500">
                                        @lang('general.edit')
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                @lang('general.no_data')
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $banks->links('pagination::tailwind') }}
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll(".save-btn").forEach((btn) => {
            btn.addEventListener("click", async (e) => {
                const input = btn.previousElementSibling;
                const value = input.value;
                const assetId = input.dataset.id;
                const key = input.name;
                const route = "{{ route('admin.user.details.assets', ':id') }}".replace(':id', assetId);

                Swal.fire({
                    title: "{{trans('general.confirm_delete_title')}}",
                    icon: 'question',
                    showCancelButton: true,
                    cancelButtonText: "{{trans('general.cancel_button')}}",
                    confirmButtonColor: '#63d0df',
                    cancelButtonColor: '#f7858d',
                    confirmButtonText: "{{trans('general.yes')}}",
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(route, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({ key, value })
                            });

                            if (response.ok) {
                                mboxSuccess();
                            } else {
                                mboxError();
                            }
                        } catch (err) {
                            mboxError();
                        }
                    }
                });
            });
        });
    </script>
@endsection
