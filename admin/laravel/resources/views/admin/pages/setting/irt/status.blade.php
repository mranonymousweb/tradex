@extends('admin.layout.master')

@section('title', trans('setting.irt_status'))


@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('setting.irt_status')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.irt.index')}}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-light hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>
        <!-- Summary of cards -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"> @lang('setting.irt_status')
                                <span class="text-red-500">*</span></label>
                            <label class="w-11 relative flex items-center">
                                <input
                                    type="checkbox"
                                    class="hidden peer"
                                    id="irt_system_status"
                                    value="1"
                                    name="{{config('constants.option.irt_system_status')}}"
                                    {{ optional($options)[config('constants.option.irt_system_status')] ==  config('constants.option.active') ? 'checked' : '' }}
                                >
                                <div
                                    class="w-11 h-6 bg-gray-300 rounded-full transition-colors duration-300 peer-checked:bg-green-600"></div>
                                <div
                                    class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-full"></div>
                            </label>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"> @lang('setting.irt_user_status_active_all_user')
                                <span class="text-red-500">*</span></label>
                            <label class="w-11 relative flex items-center">
                                <input
                                    type="checkbox"
                                    class="hidden peer"
                                    id="irt_ـuser_system_status"
                                    value="1"
                                    name="{{config('constants.option.irt_system_all_user_status')}}"
                                    {{ optional($options)[config('constants.option.irt_system_all_user_status')] ==  config('constants.option.active') ? 'checked' : '' }}
                                >
                                <div
                                    class="w-11 h-6 bg-gray-300 rounded-full transition-colors duration-300 peer-checked:bg-green-600"></div>
                                <div
                                    class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform duration-300 peer-checked:translate-x-full"></div>
                            </label>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('input[type="checkbox"][name]').forEach((checkbox) => {
            checkbox.addEventListener('change', async (event) => {
                const isChecked = event.currentTarget.checked;
                await setRequest(isChecked, event);
            });
        });

        async function setRequest(isChecked, event) {
            const url = "{{route('admin.option.save') }}";

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        [event.target.name]: isChecked ? "{{ config('constants.option.active') }}" : "{{ config('constants.option.inactive') }}",
                        fetch: true
                    }),
                });

                if (!response.ok) {
                    mboxError();
                    return;
                }

                mboxSuccess();
            } catch (error) {
                console.error('Fetch error:', error.message);
            }
        }
    </script>

@endsection


