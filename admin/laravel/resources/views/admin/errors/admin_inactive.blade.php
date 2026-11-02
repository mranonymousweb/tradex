@extends('admin.layout.master')

@section('title', trans('general.admin_inactive_page'))

@section('contents')

    <div class="flex justify-center pt-20 px-4">
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-10 max-w-md w-full text-center">

            <div class="text-6xl mb-6">
                ⛔
            </div>

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-3">
                {{ trans('general.account_inactive') }}
            </h1>

            <p class="text-gray-500 dark:text-gray-300 leading-7 mb-6">
                {{ trans('general.admin_inactive_message') }}
            </p>

            <div class="flex flex-col gap-3">

{{--                <a href="#"--}}
{{--                   class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg transition duration-200">--}}
{{--                    {{ trans('general.contact_support') }}--}}
{{--                </a>--}}

                <form method="GET" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 py-2.5 rounded-lg transition duration-200">
                        {{ trans('general.logout') }}
                    </button>
                </form>

            </div>
        </div>
    </div>

@endsection