@extends('admin.layout.master')

@section('title', trans('general.back_up'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">

        {{-- HEADER --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    @lang('general.back_up')
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    @lang('general.backup_system_description')
                </p>
            </div>

            <a href="{{route('admin.setting.index')}}"
               class="inline-flex items-center px-4 py-2 rounded-xl bg-gray-200 text-gray-700
                  hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition">
                @lang('master.back')
            </a>

        </div>

        {{-- BACKUP CARD --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">

            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        @lang('general.secure_backup_download')
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        @lang('general.backup_description')
                    </p>
                </div>

                <span class="px-3 py-1 text-xs font-semibold rounded-full
                         bg-yellow-100 text-yellow-700
                         dark:bg-yellow-900/30 dark:text-yellow-300">
                @lang('general.two_fa_required')
            </span>

            </div>

            {{-- WARNING --}}
            <div class="mt-5 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                <p class="text-sm text-red-700 dark:text-red-300">
                    @lang('general.backup_warning')
                </p>
            </div>

            {{-- ACTION --}}
            <div class="mt-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">


                <button type="button"
                        data-modal-target="otp_modal"
                        class="modal-trigger inline-flex items-center gap-2 px-6 py-2.5 rounded-xl
                           bg-primary-600 text-white font-medium hover:bg-primary-700 transition shadow">
                    @lang('general.download_backup')
                </button>

            </div>

        </div>

        {{-- CONFIGURATION --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">

            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">
                @lang('general.backup_configuration')
            </h3>

            <form action="{{ route('admin.option.save') }}" method="post">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- TIME --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            @lang('general.time_period')
                        </label>

                        <select name="{{config('constants.option.backup_time')}}"
                                class="mt-2 w-full px-4 py-2.5 rounded-xl border
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                   focus:ring-2 focus:ring-primary-500">

                            @foreach($times as $key => $value)
                                <option
                                    @selected(old(config('constants.option.backup_time'), optional($options)[config('constants.option.backup_time')]) == $key)
                                    value="{{ $key }}">
                                    {{$value}}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- DOMAIN --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            @lang('general.domain_or_ip')
                        </label>

                        <input type="text"
                               name="{{config('constants.option.backup_domain')}}"
                               value="{{old(config('constants.option.backup_domain'), optional($options)[config('constants.option.backup_domain')])}}"
                               class="mt-2 w-full px-4 py-2.5 rounded-xl border
                                  dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                  focus:ring-2 focus:ring-primary-500">
                    </div>

                    {{-- PORT --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            @lang('general.port')
                        </label>

                        <input type="number"
                               name="{{config('constants.option.backup_port')}}"
                               value="{{old(config('constants.option.backup_port'), optional($options)[config('constants.option.backup_port')])}}"
                               class="mt-2 w-full px-4 py-2.5 rounded-xl border
                                  dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                  focus:ring-2 focus:ring-primary-500">
                    </div>

                    {{-- USERNAME --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            @lang('general.username')
                        </label>

                        <input type="text"
                               name="{{config('constants.option.backup_username')}}"
                               value="{{old(config('constants.option.backup_username'), optional($options)[config('constants.option.backup_username')])}}"
                               class="mt-2 w-full px-4 py-2.5 rounded-xl border
                                  dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                  focus:ring-2 focus:ring-primary-500">
                    </div>

                    {{-- PASSWORD --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            @lang('general.password')
                        </label>

                        <input type="password"
                               name="{{config('constants.option.backup_password')}}"
                               class="mt-2 w-full px-4 py-2.5 rounded-xl border
                                  dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                  focus:ring-2 focus:ring-primary-500"
                               placeholder="••••••••">
                    </div>

                    {{-- PATH --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            @lang('general.path')
                        </label>

                        <input type="text"
                               name="{{config('constants.option.backup_path')}}"
                               value="{{old(config('constants.option.backup_path'), optional($options)[config('constants.option.backup_path')])}}"
                               class="mt-2 w-full px-4 py-2.5 rounded-xl border
                                  dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                  focus:ring-2 focus:ring-primary-500">
                    </div>

                </div>

                {{-- SAVE --}}

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-primary-600 text-white font-medium
                               hover:bg-primary-700 transition shadow">
                        @lang('general.save')
                    </button>
                </div>

            </form>
        </div>

    </main>
@endsection


@section('modal')

    <div class="modal fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-md"
         data-modal-id="otp_modal">
        <div
            class="bg-white dark:bg-gray-800 w-full max-w-md rounded-2xl shadow-2xl p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">@lang('general.security_verification')</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 mb-6">@lang('general.otp_instruction')</p>
            @if($admin->otp_status || $admin->google2fa_status)
                <form id="otp-form" method="POST" action="{{ route('admin.setting.backup.download') }}">
                    @csrf
                    <div class="space-y-4">

                        <div class="space-y-1">
                            <input
                                required
                                type="text"
                                name="code"
                                id="otp_code_input"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl transition-all outline-none"
                            >
                            <p id="input-error" class="hidden text-xs text-red-500 font-medium ml-1"></p>
                        </div>
                        @if($admin->otp_status)
                            <x-admin.otp-button :url="route('admin.otp.send', $admin)"/>
                        @endif
                        <div class="flex justify-end gap-3 mt-8">
                            <button type="button" data-modal-close
                                    class="px-5 py-2.5 rounded-xl font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                @lang('general.cancel')
                            </button>
                            <button type="submit" id="submit-verify-btn"
                                    class="px-5 py-2.5 rounded-xl font-medium bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-200 dark:shadow-none transition-all flex items-center gap-2">
                                <span>@lang('general.verify_continue')</span>
                            </button>
                        </div>
                    </div>
                </form>
            @else

                <div

                    class="flex items-center justify-between gap-4 p-4 rounded-xl border border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-900/20">


                    <!-- Text content -->

                    <div class="flex-1">

                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">

                            @lang('general.security_not_enabled')

                        </h4>


                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">

                            @lang('general.enable_2fa_backup_hint')

                        </p>

                    </div>


                    <!-- Actions -->

                    <div class="flex items-center gap-2 shrink-0">

                        <button

                            type="button"

                            data-modal-close

                            class="px-4 py-2 text-sm rounded-lg bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition">

                            @lang('general.cancel')

                        </button>


                        <a

                            href="{{ route('admin.admin.security') }}"

                            class="px-4 py-2 text-sm rounded-lg bg-primary-600 text-white hover:bg-primary-700 transition">

                            @lang('general.enable')

                        </a>

                    </div>


                </div>

            @endif

        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const otpForm = document.getElementById('otp-form');
            const submitBtn = document.getElementById('submit-verify-btn');
            const modal = document.querySelector('[data-modal-id="otp_modal"]');
            const inputField = document.getElementById('otp_code_input');
            const errorText = document.getElementById('input-error');
            if (!otpForm) return;

            const toggleLoading = (loading) => {
                submitBtn.disabled = loading;
                if (loading) {
                    submitBtn.dataset.oldHtml = submitBtn.innerHTML;
                    submitBtn.innerHTML = `<svg class="animate-spin h-4 w-4 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> {{__('general.verifying')}}`;
                } else {
                    submitBtn.innerHTML = submitBtn.dataset.oldHtml;
                }
            };

            otpForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                toggleLoading(true);

                const formData = new FormData(this);
                formData.append('validate_only', 'true');

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const result = await response.json();

                    if (response.ok) {
                        toggleLoading(false);
                        inputField.classList.remove('border-red-500', 'ring-1', 'ring-red-500');
                        setTimeout(() => {
                            if (modal) modal.classList.add('hidden');
                            otpForm.submit();
                            inputField.value = '';
                            errorText.innerText = '';
                        }, 600);
                        toastAlert('{{__('general.operation_done')}}', 'success');

                    } else {
                        inputField.classList.add('border-red-500', 'ring-1', 'ring-red-500');
                        errorText.innerText = result.message;
                        errorText.classList.remove('hidden');

                        toggleLoading(false);
                    }
                } catch (error) {
                    toastAlert('{{__('general.operation_failed')}}', 'error');
                    toggleLoading(false);
                    console.error('OTP Error:', error);
                }
            });
        });
    </script>
@endsection
