@extends('admin.layout.master')

@section('title', trans('general.allowed_domains'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">

        <!-- header page -->
        <div class="mb-6 flex  md:flex-row md:items-center md:justify-between">
{{--            <div>--}}
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        @lang('general.allowed_domains') ({{ $token->name }})
                    </h2>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{route('admin.setting.api.setting')}}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                             fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                  clip-rule="evenodd"/>
                        </svg>
                        @lang('master.back')
                    </a>
                </div>
{{--            </div>--}}
        </div>

        <!-- add domain form -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 p-6 mb-6">

            <form action="{{ route('admin.setting.api.domain.store', $token->id) }}" method="POST">
                @csrf

                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    @lang('general.domain')
                </label>

                <div class="flex gap-3">
                    <input
                        type="text"
                        name="domain"
                        required
                        value="{{ old('domain') }}"
                        placeholder="example.com"
                        oninvalid="this.setCustomValidity('@lang('general.domain_required')')"
                        oninput="setCustomValidity('')"
                        class="flex-1 px-4 py-2 border rounded-lg
                               focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >

                    <button type="submit"
                            class="px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white">
                        @lang('general.add')
                    </button>
                </div>

                @error('domain')
                <span class="text-danger text-sm">{{ $message }}</span>
                @enderror
            </form>
        </div>

        <!-- domain list -->
        <div
            class="bg-white w-full dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">

            <div class="w-full overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr
                        class="border-b border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 h-16">
                        <th class="table-header py-4 font-medium">@lang('general.id')</th>
                        <th class="table-header py-4 font-medium">@lang('general.domain')</th>
                        <th class="table-header py-4 font-medium">@lang('general.created_at')</th>
                        <th class="table-header py-4 font-medium">@lang('general.operation')</th>
                    </tr>
                    </thead>

                    <tbody
                        class="divide-y text-sm text-gray-500 dark:text-gray-400 dark:divide-gray-700">

                    @forelse($domains as $domain)
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16">
                            <td>{{ $domain['id'] }}</td>
                            <td>{{ $domain['domain'] }}</td>
                            <td>{{ $domain['created_at'] }}</td>
                            <td>
                                <button
                                    onclick="mboxDelete('{{ route('admin.setting.api.domain.destroy',[$token->id , $domain['id']]) }}')"
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
                        <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16">
                            <td colspan="4">
                                @lang('general.not_found')
                            </td>
                        </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>
        </div>

    </main>
@endsection
