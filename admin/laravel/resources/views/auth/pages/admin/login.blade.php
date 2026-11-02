@extends('auth.layout.master')

@section('title', 'Admin/Login')

@section('contents')
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Image section -->
        <div class="lg:w-1/2 auth-bg relative hidden lg:flex items-center justify-center p-12" style="background-image: url('{{asset('assets/images/panel/photo-1581291518633-83b4ebd1d83e.jpg')}}');">
            <div class="absolute inset-0 bg-black/30 dark:bg-black/50"></div>
            <div class="relative z-10 text-white text-center max-w-md">
                <div class="w-[100px] h-[100px] rounded-xl  flex items-center justify-center mx-auto mb-6" style="background-color: #ffffff !important; padding: 10px !important;">
                    <div class="flex items-center">
                        <div class="rounded-md  flex items-center justify-center">
                            <img
                                src="{{ optional($metaData)[config('constants.meta.logo')] ?? '' }}"
                                alt="Logo"
                                loading="lazy"
                                class="w-[96px] h-[96px]"
                            />

                        </div>
                    </div>
                </div>
                <h2 class="text-3xl font-bold mb-4">{{optional($metaData)[config('constants.meta.title')] ?? '' }}</h2>
                <p class="text-lg mb-6">{{optional($metaData)[config('constants.meta.description')] ?? '' }}</p>
            </div>
        </div>
        <!-- Login form section -->
        <div class="lg:w-1/2 flex items-center justify-center p-6">
            <div class="w-full max-w-md">
                <!-- Mobile logo -->
                <div class="flex items-center justify-center mb-8 lg:hidden">
                    <div class="w-96 h-96 rounded-md  flex items-center justify-center">
                        <img
                            src="{{optional($metaData)[config('constants.meta.logo')] ?? ''  }}"
                            alt="Logo"
                            loading="lazy"
                        />

                    </div>

                </div>
                <div
                    class="bg-white dark:bg-card-dark rounded-2xl shadow-soft dark:shadow-soft-dark p-8 border border-gray-100 dark:border-gray-700 fade-in">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-dark dark:text-light mb-2">@lang('general.login_admin_panel')</h2>
                    </div>
                    @error('email')
                    <p class="error-message">{{$message}}</p>
                    @enderror
                    <!-- Login form with password -->
                    <form class="space-y-5" action="{{ route('admin.login.submit') }}" method="POST"
                          id="submit-re-captcha-form">
                        @csrf
                        <input type="hidden" id="g-recaptcha-response" name="g_recaptcha_response">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.username')</label>
                            <div class="relative">
                                <input type="text" name="email" value="{{old('email')}}"
                                       class="w-full pl-12 pr-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.username')"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.username_input')')"
                                       oninput="setCustomValidity('')"
                                >
                                <div class="absolute input-icon top-1/2 transform -translate-y-1/2 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>

                        </div>
                        <div>
                            <label for="password"
                                   class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.password')</label>
                            <div class="relative">
                                <input id="password" type="password" name="password"
                                       class="w-full pl-12 pr-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.password')"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.password_input')')"
                                       oninput="setCustomValidity('')"
                                >
                                <div class="absolute input-icon top-1/2 transform -translate-y-1/2 text-gray-400">
                                </div>
                                <button type="button"
                                        class="absolute password-toggle top-1/2 transform -translate-y-1/2 text-gray-400"
                                        onclick="togglePassword('password')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @if(!isset($options[config('constants.option.google_captcha_status')]) || $options[config('constants.option.google_captcha_status')] != config('constants.option.active'))
                            <div class="flex flex-col gap-4">
                                <!-- Captcha image & refresh -->
                                <div class="flex items-center">
                                    <div
                                        class="h-16 w-44 rounded-lg overflow-hidden border border-gray-300 dark:border-gray-600 shadow-sm">
                                        <img id="captcha-image" class="h-full w-full object-cover"
                                             src="{{ $captcha->inline() }}"
                                             alt="captcha"/>
                                    </div>
                                    <button type="button"
                                            onclick="reloadCaptcha()"
                                            class="ml-auto flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 4v5h.582M20 20v-5h-.581M5.5 9A7.5 7.5 0 0112 4.5c2.004 0 3.82.784 5.144 2.056M18.5 15A7.5 7.5 0 0112 19.5c-2.004 0-3.82-.784-5.144-2.056"/>
                                        </svg>
                                        @lang('general.reload')
                                    </button>
                                </div>

                                <!-- Captcha input -->
                                <div>
                                    <label for="captcha"
                                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        @lang('general.enter_security_code')
                                    </label>
                                    <input id="captcha" type="text" name="captcha"
                                           class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                           placeholder="@lang('general.enter_security_code')"
                                           required
                                           oninvalid="this.setCustomValidity('@lang('general.enter_security_code_input')')"
                                           oninput="setCustomValidity('')"
                                    >
                                    @error('captcha')
                                    <p class="error-message">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <div>
                            <button id="btn_submit" type="submit"
                                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-primary dark:bg-primary-dark hover:bg-primary/90 dark:hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                @lang('general.login')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @if(!isset($options[config('constants.option.google_captcha_status')]) || $options[config('constants.option.google_captcha_status')] != config('constants.option.active'))
        <script>
            async function reloadCaptcha() {
                const route = "{{route('captcha.reload')}}";
                const response = await fetch(route)
                const result = await response.json();
                document.getElementById('captcha-image').src = result.image;
            }
        </script>
    @elseif(isset($options[config('constants.option.google_captcha_status')]) && $options[config('constants.option.google_captcha_status')] == config('constants.option.active'))
        <script>
            const siteKey = "{{$options[config('constants.option.google_captcha_site_key')]}}";
        </script>
        <script src="https://www.google.com/recaptcha/api.js?hl=fa&render={{$options[config('constants.option.google_captcha_site_key')]}}"></script>
        <script>
            const current_text = document.getElementById('btn_submit').textContent;
            $(document).ready(function () {
                $(document).on('submit', '#submit-re-captcha-form', function (e) {
                    e.preventDefault();
                    grecaptcha.ready(function () {
                        document.getElementById('btn_submit').classList.add("opacity-70");
                        document.getElementById('btn_submit').setAttribute('disabled', 'disabled');
                        document.getElementById('btn_submit').innerHTML = '<span class="fa fa-refresh block fa-spin text-grayCustom-100"></span><p class="text-grayCustom-100">@lang('general.validation')</P?';
                        grecaptcha.execute("{{$options[config('constants.option.google_captcha_site_key')]}}", {action: 'login'})
                            .then(function (token) {
                                // add token value to form
                                document.getElementById('g-recaptcha-response').value = token;
                                document.getElementById('btn_submit').classList.remove("opacity-70");
                                document.getElementById('btn_submit').removeAttribute('disabled');
                                document.getElementById('btn_submit').innerHTML = current_text;
                                document.getElementById("submit-re-captcha-form").submit();
                            });
                    });
                });
            });
        </script>
    @endif
@endsection

