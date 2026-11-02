@extends('auth.layout.master')
@section('title',trans('auth.confirm_account'))
@section('contents')
    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Image section -->
        <div class="lg:w-1/2 auth-bg relative hidden lg:flex items-center justify-center p-12">
            <div class="absolute inset-0 bg-black/30 dark:bg-black/50"></div>
            <div class="relative z-10 text-white text-center max-w-md">
                <div class="w-20 h-20 rounded-xl bg-primary-grad flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold mb-4">به سایت ما خوش آمدید</h2>
                <p class="text-lg mb-6">با ثبت نام در آرینو کالا از بهترین تجربه خرید آنلاین لذت ببرید. دسترسی به هزاران
                    محصول با کیفیت و قیمت مناسب.</p>
            </div>
        </div>

        <!-- Registration form section -->
        <div class="lg:w-1/2 flex items-center justify-center p-6">
            <div class="w-full max-w-md">
                <!-- Mobile logo -->
                <div class="flex items-center justify-center mb-8 lg:hidden">
                    <div class="w-12 h-12 rounded-md bg-primary-grad flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <h1 class="mr-3 text-xl font-bold text-dark dark:text-light">قالب <span class="text-primary">آرینو
                        </span></h1>
                </div>

                <div
                    class="bg-white dark:bg-card-dark rounded-2xl shadow-soft dark:shadow-soft-dark p-8 border border-gray-100 dark:border-gray-700">


                    <div class="step active" id="step1">
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold text-dark dark:text-light mb-2">تایید کد احراز هویت</h2>
                            <p class="text-gray-500 dark:text-gray-400">کد ارسال شده را وارد کنید</p>
                        </div>

                        <form novalidate class="space-y-5" id="verify-code-form">
                            <!-- Verification code -->
                            <div>
                                <label for="verification-code"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">کد
                                    تایید</label>
                                <div class="relative">
                                    <input type="text" id="verification-code"
                                           class="w-full pl-12 pr-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                           placeholder="کد ۶ رقمی" required maxlength="6">
                                    @error('mobile')
                                    <p class="error-message">{{$message}}</p>
                                    @enderror
                                    <div class="absolute input-icon top-1/2 transform -translate-y-1/2 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="error-message hidden" id="verification-code-error">لطفا کد ۶ رقمی معتبر وارد
                                    کنید</p>


                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-xs text-gray-500 dark:text-gray-400"></p>
                                    <button
                                        type="button"
                                        class="text-xs font-medium text-primary dark:text-primary-dark hover:underline focus:outline-none resend-button"
                                        data-timeleft="{{ $user->getTokenTimeLeftInSecond('otp') }}"
                                        data-url="{{ route('user.send.otp') }}"
                                    >
                                        <span class="button_text">@lang('auth.resend')</span>
                                        <span class="timer hidden"></span>
                                        <span
                                            class="spinner hidden w-4 h-4 border-2 border-t-transparent border-primary dark:border-primary-dark rounded-full animate-spin"></span>
                                    </button>
                                </div>
                            </div>

                            <!-- Code verification button -->
                            <div>
                                <button type="submit"
                                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-primary dark:bg-primary-dark hover:bg-primary/90 dark:hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    تایید کد
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                         fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </form>

                    </div>

                    <!-- Login link -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            قبلاً حساب کاربری دارید؟
                            <a href="login.html"
                               class="font-medium text-primary dark:text-primary-dark hover:underline">وارد
                                شوید</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.resend-button').forEach(button => {
                new ResendButton(button);
            });
        });
    </script>
@endsection
