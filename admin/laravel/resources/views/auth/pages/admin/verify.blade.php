@extends('auth.layout.master')

@section('title', __('general.verify_account'))

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
                    <div class="rounded-md  flex items-center justify-center">
                        <img
                            src="{{optional($metaData)[config('constants.meta.logo')] ?? ''  }}"
                            alt="Logo"
                            loading="lazy"
                            class="w-[96px] h-[96px]"
                        />

                    </div>

                </div>
                <div
                    class="bg-white dark:bg-card-dark rounded-2xl shadow-soft dark:shadow-soft-dark p-8 border border-gray-100 dark:border-gray-700 fade-in">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-dark dark:text-light mb-2">@lang('general.verify_account')</h2>
                    </div>

                    <!-- Login form with password -->
                    <form class="space-y-5" action="{{ route('verify.submit') }}" method="POST">
                        @csrf
                        @if($admin->google2fa_status)
                        <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ">
                                    @lang('general.2fa') (@lang('general.code'))
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="code" value="{{old('code')}}"
                                       class="w-full p-2  pr-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.code')"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.enter_code')')"
                                       oninput="setCustomValidity('')"
                                >
                                @error('code')
                                <span class="text-red-500 mt-2 block">{{$message}}</span>
                                @enderror
                            </div>
                        @endif
                        @if($admin->otp_status)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ">
                                @lang('general.otp') (@lang('general.code'))
                                <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="otp_txt_code"
                                name="otp_code"
                                value="{{ old('otp_code') }}"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none
                               focus:ring-2 focus:ring-primary-500 focus:border-transparent
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="@lang('general.code')"
                                required
                                oninvalid="this.setCustomValidity('@lang('general.enter_code')')"
                                oninput="setCustomValidity('')"
                            >
                            <x-admin.otp-button :url="route('admin.otp.send', $admin)" :send="true" />
                            @error('otp_code')
                            <span class="text-red-500 mt-2 block">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif
                        <div>
                            <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-primary dark:bg-primary-dark hover:bg-primary/90 dark:hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                @lang('general.verify')
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection


