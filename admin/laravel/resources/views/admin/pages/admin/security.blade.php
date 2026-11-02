@extends('admin.layout.master')

@section('title', trans('general.profile'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.update')
                    (@lang('general.password'))</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">

            <div class="lg:col-span-1 space-y-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">

                    <form action="{{ route('admin.admin.password.update',$admin) }}" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.password')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="password" required value="{{old('password')}}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.password')">
                                @error('password')
                                <span class="text-red-500 mt-2">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-span-2 md:col-span-1">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.confirm_password')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="password_confirmation" required
                                       value="{{old('password_confirmation')}}"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.confirm_password')">
                                @error('password_confirmation')
                                <span class="text-red-500 mt-2">{{$message}}</span>
                                @enderror
                            </div>

                            <div class="pt-4">
                                <button type="submit"
                                        class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                    @lang('general.save')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between mt-5">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('general.2fa_authentication')</h2>
            </div>
        </div>


        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">

            <div class="lg:col-span-1 space-y-6">

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- 2fa --}}
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                @lang('general.2fa')
                            </label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input
                                    id="enable-2fa-checkbox"
                                    type="checkbox"
                                    class="sr-only peer modal-trigger"
                                    value="1"
                                    data-modal-target="2fa_modal"
                                    @if($admin->google2fa_status ) checked @endif
                                >

                                <div
                                    class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-green-600 transition-colors"></div>
                                <div
                                    class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-full"></div>
                            </label>

                        </div>
                        {{-- otp --}}
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                @lang('general.otp')
                            </label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input
                                    id="enable-opt-checkbox"
                                    type="checkbox"
                                    class="sr-only peer modal-trigger"
                                    value="1"
                                    data-modal-target="otp_modal"
                                    @if($admin->otp_status ) checked @endif
                                >

                                <div
                                    class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:bg-green-600 transition-colors"></div>
                                <div
                                    class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-full"></div>
                            </label>
                            @error('accepted_by_admin')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </main>

@endsection

@section('modal')

    {{-- googel 2fa modal --}}
    <div
        class="modal fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-200/40 dark:bg-gray-900/40 backdrop-blur-md"
        data-modal-id="2fa_modal">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <form id="verify-2fa-form" method="POST" action="{{ route('admin.admin.2fa.toggle', $admin) }}">
                @csrf
                <!-- Header -->
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 ">
                    @lang('general.2fa')
                </h3>

                <!-- Body -->
                <div class="space-y-4">


                    @if(is_null($admin->verifiedAtGoogle2Fa()))
                        <h5 class="text-gray-700 dark:text-gray-300 ">
                            @lang('general.scan_code_with_google_authenticator')
                        </h5>
                     <div id="google-2Fa-container">
                         <div class="flex justify-center">
                             {!! $admin->getGoogleAuthQrcode() !!}
                         </div>
                         <div>
                             <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ">
                                 @lang('general.secret')
                             </label>

                             <input
                                 disabled
                                 type="text"
                                 name="secret"
                                 required
                                 value="{{ $admin->google_2fa_secret }}"
                                 class="w-full px-4 py-2 border rounded-lg focus:outline-none
                               focus:ring-2 focus:ring-primary-500 focus:border-transparent
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                             >
                         </div>
                     </div>
                    @endif

                    <!-- Code Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ">
                            @lang('general.code')
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="txt_code"
                            name="code"
                            required
                            value="{{ old('code') }}"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none
                               focus:ring-2 focus:ring-primary-500 focus:border-transparent
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="@lang('general.code')"
                        >

                        @error('code')
                        <span class="text-red-500 mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <!-- Footer -->
                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        data-modal-close
                        class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300
                          dark:bg-gray-700 dark:hover:bg-gray-600
                           text-gray-700 dark:text-gray-200">
                        @lang('general.cancel_button')
                    </button>

                    <button type="submit" class="px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white">
                        @lang('general.accept')
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{--  otp modal --}}
    <div
        class="modal fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-200/40 dark:bg-gray-900/40 backdrop-blur-md"
        data-modal-id="otp_modal">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6 relative">
            <form id="verify-otp-form" method="POST" action="{{route('admin.admin.otp.toggle', $admin) }}">
                @csrf
                <!-- Header -->
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 ">
                    @lang('general.otp')
                </h3>

                <!-- Body -->
                <div class="space-y-4">
                    <!-- Code Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ">
                            @lang('general.code')
                            <span class="text-red-500">*</span>

                        </label>

                        <input
                            type="text"
                            id="otp_txt_code"
                            name="otp_code"
                            required
                            value="{{ old('otp_code') }}"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none
                               focus:ring-2 focus:ring-primary-500 focus:border-transparent
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            placeholder="@lang('general.code')"
                        >
                        <x-admin.otp-button :url="route('admin.otp.send', $admin)" />

                        @error('otp_code')
                        <span class="text-red-500 mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" data-modal-close
                            class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300
                          dark:bg-gray-700 dark:hover:bg-gray-600
                           text-gray-700 dark:text-gray-200">
                        @lang('general.cancel_button')
                    </button>

                    <button type="submit" class="px-4 py-2 rounded-lg bg-green-500 hover:bg-green-600 text-white">
                        @lang('general.accept')
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            /** ------------------------------
             *  Helper: Show validation errors
             *  --------------------------------
             */
            function showValidationErrors(form, errors) {
                form.querySelectorAll(".error-message").forEach(el => el.remove());

                Object.entries(errors).forEach(([field, messages]) => {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        const error = document.createElement("span");
                        error.className = "text-red-500 mt-2 block error-message";
                        error.textContent = messages[0];
                        input.insertAdjacentElement("afterend", error);
                    }
                });
            }

            /** ------------------------------
             *  Helper: Send Fetch Request
             *  --------------------------------
             */
            async function sendRequest(url, formData) {
                const res = await fetch(url, {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });

                return { response: res, data: await res.json() };
            }

            /** ------------------------------
             *  Helper: Handle AJAX Form Submit
             *  --------------------------------
             */
            function ajaxFormHandler(form, onSuccess = () => {}) {
                form.addEventListener("submit", async function (e) {
                    e.preventDefault();
                    const formData = new FormData(form);

                    try {
                        const { response, data } = await sendRequest(form.action, formData);

                        if (!response.ok) {
                            if (data.errors) {
                                showValidationErrors(form, data.errors);
                            } else if (data.message) {
                                toastAlert(data.message, "error");
                            }
                            return;
                        }

                        onSuccess(data);

                    } catch (err) {
                        console.error("Form Submit Error:", err);
                        toastAlert("@lang('general.something_went_wrong')", "error");
                    }
                });
            }


            /** ------------------------------
             *  Google 2FA Form (2fa_modal)
             *  --------------------------------
             */
            const twoFaForm = document.getElementById("verify-2fa-form");

            ajaxFormHandler(twoFaForm, (data) => {
                closeModal("2fa_modal");

                const checkbox = document.getElementById("enable-2fa-checkbox");
                if (checkbox) checkbox.checked = data.is_enabled ?? false;

                const google2FaContainer = document.getElementById("google-2Fa-container");
                google2FaContainer?.classList.add("hidden");

                twoFaForm.reset();

                toastAlert(data.message, data.success ? "success" : "error");
            });


            /** ------------------------------
             *  OTP Form (otp_modal)
             *  --------------------------------
             */
            const otpForm = document.getElementById("verify-otp-form");

            ajaxFormHandler(otpForm, (data) => {
                closeModal("otp_modal");

                const checkbox = document.getElementById("enable-opt-checkbox");
                if (checkbox) checkbox.checked = data.otp_status ?? false;

                const txtCode = document.getElementById("otp_txt_code");
                if (txtCode) txtCode.value = "";

                toastAlert(data.message, data.success ? "success" : "error");
            });

        });


        /** ------------------------------
         *  Modal Close Helper
         *  --------------------------------
         */
        function closeModal(id) {
            document.querySelector(`[data-modal-id="${id}"]`)?.classList.add("hidden");
        }
    </script>

@endsection


