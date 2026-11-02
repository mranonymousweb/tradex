@extends('auth.layout.master')
@section('title',trans('auth.sign_up'))
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
                            <h2 class="text-2xl font-bold text-dark dark:text-light mb-2">@lang('auth.register_user')</h2>
                            <p class="text-gray-500 dark:text-gray-400">@lang('auth.please_enter_personal_info')</p>
                        </div>

                        <form class="space-y-5" method="POST" action="{{route('user.sign_up')}}">
                            @csrf
                            <!-- First and Last Name -->
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label for="first-name"
                                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('auth.first_name')</label>
                                    <div class="relative">
                                        <input type="text" id="first-name"
                                               class="w-full pr-4 pl-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                               placeholder="@lang('auth.enter_your_first_name')"
                                               name="first_name"
                                               required
                                               oninvalid="this.setCustomValidity('@lang('auth.pleas_enter_your_first_name')')"
                                               oninput="setCustomValidity('')"
                                               value="{{old('first_name')}}"
                                        >
                                        @error('first_name')
                                        <p class="error-message">{{$message}}</p>
                                        @enderror
                                        <div
                                            class="absolute input-icon top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label for="last-name"
                                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('auth.last_name')</label>
                                    <div class="relative">
                                        <input type="text" id="last-name"
                                               class="w-full pr-4 pl-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                               placeholder="@lang('auth.enter_your_last_name')"
                                               name="last_name"
                                               required
                                               oninvalid="this.setCustomValidity('@lang('auth.pleas_enter_your_last_name')')"
                                               oninput="setCustomValidity('')"
                                               value="{{old('last_name')}}"
                                        >
                                        @error('last_name')
                                        <p class="error-message">{{$message}}</p>
                                        @enderror
                                        <div
                                            class="absolute input-icon top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>


                                <div>
                                    <label for="email"
                                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('auth.email')</label>
                                    <div class="relative">
                                        <input type="email" id="email"
                                               class="w-full pr-4 pl-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                               placeholder="@lang('auth.enter_email')"
                                               required
                                               name="email"
                                               oninvalid="this.setCustomValidity('@lang('auth.pleas_enter_email')')"
                                               oninput="setCustomValidity('')"
                                               value="{{old('email')}}"
                                        >
                                        @error('email')
                                        <p class="error-message">{{$message}}</p>
                                        @enderror
                                        <div
                                            class="absolute input-icon top-1/2 transform -translate-y-1/2 text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M16 12H8m0 0l4-4m0 0l4 4M4 6h16a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <!-- Mobile -->
                            <div>
                                <label for="mobile"
                                       class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('auth.mobile')
                                </label>

                                <div id="country-phone-container"
                                     class=" dir-ltr w-full flex  overflow-hidden pr-4 pl-10 py-3 border rounded-xl focus-within:border-primary focus-within:ring-2 focus-within:ring-primary transition dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <div
                                        class="flex items-center border-r w-1/3 pe-3 text-sm text-gray-700 dark:text-gray-200  dark:border-gray-600">
                                        <select id="country_input" name="country_id"
                                                class="w-full bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                            @foreach($countries as $country)
                                                @if($country->name == 'Iran')
                                                    <option
                                                        @selected($country->id == old('country_id')) data-code="{{$country->phonecode}}"
                                                        value="{{$country->id}}">{{ $country->native }}
                                                        ({{ $country->iso2 }})
                                                    </option>
                                                @else
                                                    <option
                                                        @selected($country->id == old('country_id')) data-code="{{$country->phonecode}}"
                                                        value="{{$country->id}}">{{ $country->name }}
                                                        ({{ $country->iso2 }})
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('country_id')
                                        <p class="error-message">{{$message}}</p>
                                        @enderror
                                    </div>


                                    <div
                                        class="flex items-center justify-center border-r w-1/6 px-3 text-sm text-gray-700 dark:text-gray-200  dark:border-gray-600">
                                        <input readonly type="text" id="code_input"
                                               class="w-14 text-center focus:outline-none"/>
                                    </div>


                                    <div
                                        class="flex justify-center w-2/3 relative px-3 text-gray-700 dark:text-gray-200">
                                        <input type="tel" id="mobile" placeholder="912xxxxxxx"
                                               class="w-full bg-transparent focus:outline-none"
                                               name="mobile"
                                               required
                                               oninvalid="this.setCustomValidity('@lang('auth.pleas_enter_authentic_mobile')')"
                                               oninput="setCustomValidity('')"
                                               value="{{old('mobile')}}"

                                        />
                                    </div>
                                    @error('mobile')
                                    <p class="error-message">{{$message}}</p>
                                    @enderror
                                </div>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    @lang('auth.verify_code_will_send_to_this_mobile')
                                </p>
                            </div>

                            <div class="pt-4">
                                <button type="submit"
                                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-primary dark:bg-primary-dark hover:bg-primary/90 dark:hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    @lang('auth.sign_up')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-4 ms-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15.75 19.5 8.25 12l7.5-7.5"/>
                                    </svg>
                                </button>
                            </div>
                        </form>

                    </div>

                    <!-- Login link -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @lang('auth.have_you_account')
                            <a href="{{route('user.sign_in.show')}}"
                               class="font-medium text-primary dark:text-primary-dark hover:underline">@lang('auth.sign_in')</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const country_input = document.getElementById('country_input')
        const code_input = document.getElementById('code_input')
        country_input.addEventListener('change', (event) => {
            changeCountryCode(event)
        })

        function changeCountryCode(event) {
            const select = event.currentTarget;
            const selectedOption = select.options[select.selectedIndex];
            const code = selectedOption.dataset.code
            setCode(code);
        }

        function setCode(code) {
            code_input.value = code;
        }

        window.addEventListener('DOMContentLoaded', () => {
            const selectedOption = country_input.options[country_input.selectedIndex];
            const code = selectedOption.dataset.code;
            setCode(code);
        });
    </script>

@endsection
