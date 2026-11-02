@extends('admin.layout.master')

@section('title', trans('general.api_setting'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.api_setting')</h2>
            </div>

            <div class="mt-4 md:mt-0 flex items-center gap-3">
                <button
                    class="modal-trigger inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200"
                    data-modal-target="create_token"
                >
                    @lang('general.add_new_token')
                </button>

                <a href="{{route('admin.setting.index')}}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                    @lang('master.back')
                </a>
            </div>

        </div>
        <div
            class="bg-white w-full dark:bg-gray-800 min-h-[700px] rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="dark:bg-gray-800 dark:border-gray-700 w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="">
                    <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                        <th class="table-header py-4 font-medium">@lang('general.id')</th>
                        <th class="table-header py-4 font-medium">@lang('general.name')
                        <th class="table-header py-4 font-medium">@lang('general.expire_at')</th>
                        <th class="table-header py-4 font-medium">@lang('general.created_at') </th>
                        <th class="table-header py-4 font-medium">@lang('general.operation')</th>
                    </tr>
                    </thead>
                    <tbody
                        class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                    @forelse($tokens as $token)
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$token->id}}</span>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$token->name}}</span>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$token->getExpiresAt()}}</span>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <span>{{$token->getCreatedAt()}}</span>
                            </td>
                            <td class="whitespace-nowrap max-xl:min-w-70">
                                <a href="{{route('admin.setting.api.domain',$token->id)}}"
                                   class="px-3 py-1 border rounded-lg text-sm font-medium text-white bg-primary-600 dark:bg-primary-500 hover:bg-primary-500 dark:hover:bg-primary-600 border-primary-600 dark:border-primary-500">@lang('general.details')</a>

                                <button
                                    onclick="mboxDelete('{{ route('admin.setting.api.destroy',$token->id) }}')"
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
            {{ $tokens->links('pagination::tailwind') }}
        </div>
    </main>
@endsection


@section('modal')
    <div
        class="modal fixed inset-0 z-50 flex items-center justify-center bg-gray-200/40 dark:bg-gray-900/40 backdrop-blur-md hidden"
        data-modal-id="create_token">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6 relative">

            <button type="button" data-modal-close
                    class="absolute top-3 left-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                @lang('general.add_new_token')
            </h3>

            <form action="{{route('admin.setting.api.setting.store')}}" method="POST">
                @csrf

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    @lang('general.name')
                </label>
                <input type="text" name="name" value="{{old('name')}}"
                       required
                       oninvalid="this.setCustomValidity('@lang('general.name_required')')"
                       oninput="setCustomValidity('')"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                       placeholder="@lang('general.name')">
                @error('name')
                <span class="text-danger">{{$message}}</span>
                @enderror
                <br><br>
                <label class="block text-sm font-medium mb-2">
                    @lang('general.expire_at')
                </label>

                @if(app()->getLocale() == 'fa')
                    <input data-jdp type="text" name="expires_at"
                           value="{{old('expires_at')}}"
                           class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                    focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"/>
                @else
                    <input type="date"
                           name="expires_at"
                           value="{{old('expires_at')}}"
                           class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                      focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"/>
                @endif


                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" data-modal-close
                            class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200">
                        @lang('general.cancel_button')
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white">
                        @lang('general.confirm')
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
