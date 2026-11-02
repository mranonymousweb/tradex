<header
    class="flex items-center justify-between px-6 py-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="flex items-center">
        <!-- Button menu for mobile -->
        <button onclick="toggleOffcanvas('offcanvas-responsive-menu-right')"
            class="lg:hidden text-gray-500 dark:text-gray-400 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <span class="text-muted">
            Version {{ appVersion() }}
        </span>

        <!-- search -->
        {{--        <div class="relative max-lg:hidden mr-4 lg:mr-0 lg:w-96"> --}}
        {{--            <input type="text" class="w-full px-5 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-500" placeholder="@lang('general.search') ..."> --}}
        {{--        </div> --}}
    </div>

    <!-- top menu -->
    <div class="flex items-center space-x-4 space-x-reverse">

        <!-- ✅ RESET DROPDOWN -->
        <div class="relative ms-3">
            <!-- Button -->
            <button onclick="toggleDropdown('reset-dropdown-menu','reset-dropdown-icon')"
                class="flex items-center focus:outline-none">

                <!-- آیکون ریست -->
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-gray-700 dark:text-white" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v6h6M20 20v-6h-6M5.636 18.364A9 9 0 106.343 6.343" />
                </svg>

                <span class="m-2 text-sm font-medium text-gray-900 dark:text-white">Reset</span>

                <!-- فلش -->
                <svg xmlns="http://www.w3.org/2000/svg" id="reset-dropdown-icon"
                    class="size-4 ms-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"></path>
                </svg>
            </button>

            <!-- Menu -->
            <div id="reset-dropdown-menu"
                class="absolute left-0 mt-2 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg z-10 shadow-lg hidden">

                <!-- Node -->
                <a href="{{ route('admin.admin.reset.service', ['service' => config('constants.service_node')]) }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg space-x-2">

                    <!-- Icon Node -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <circle cx="12" cy="12" r="3" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                    </svg>

                    <span>Node</span>
                </a>

                <!-- Queue -->
                <a href="{{ route('admin.admin.reset.service', ['service' => config('constants.service_queue')]) }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg space-x-2">

                    <!-- Icon Queue -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                    </svg>

                    <span>Queue</span>
                </a>

            </div>
        </div>

        <!-- lang drop down -->
        <div class="relative ms-3">
            <!-- Dropdown Button -->
            <button onclick="toggleDropdown('lang-dropdown-menu','lang-dropdown-icon')"
                class="flex items-center focus:outline-none">
                <img style="width: 30px;border-radius: 50%;" src="{{ getCurrentLang('flag') }}">
                <span style="margin: 3px;"
                    class="m-2 text-sm font-medium text-gray-900 dark:text-white">{{ getCurrentLang('name') }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" id="lang-dropdown-icon" class="size-4 ms-2 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"></path>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div id="lang-dropdown-menu"
                class="absolute left-0 mt-2 w-32 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg z-10 shadow-lg hidden">
                <!-- Profile Link -->
                @foreach ($langs as $code => $lang)
                    <a href="{{ route('site.switch.lang', ['locale' => $code]) }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 space-x-2 rounded-lg dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                        role="button" aria-label="Switch to Persian">
                        <img style="width: 30px;border-radius: 50%;" src="{{ asset($lang['flag']) }}">
                        <span>{{ $lang['name'] }}</span>
                    </a>
                @endforeach

            </div>
        </div>

        <!-- Change mode button -->
        <button id="dark-mode-toggle"
            class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden dark:block" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 dark:hidden" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
        <!-- site notification -->
        <a href="{{ route('admin.notification.index') }}"
            class="relative text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-0 right-0 w-2 h-2 rounded-full bg-red-500 dark:bg-red-400"></span>
        </a>

        <!-- user messages -->
        <a href="{{ route('admin.admin.chat') }}"
            class="relative text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <span class="absolute top-0 right-0 w-2 h-2 rounded-full bg-green-500 dark:bg-green-400"></span>
        </a>

        <!-- user drop down -->
        <div class="relative ms-3" id="user-dropdown">

            <!-- Dropdown Button -->
            <button onclick="toggleUserDropdown()" id="user-dropdown-button"
                class="flex items-center gap-2 px-2 py-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">

                <div class="relative">
                    <img class="h-8 w-8 max-sm:hidden rounded-full border-2 border-white dark:border-gray-800 shadow object-cover"
                        src="{{ auth('admin')->user()->getAvatar() }}" alt="avatar">

                    <!-- Online indicator -->
                    <span
                        class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-green-500 dark:bg-green-400 border-2 border-white dark:border-gray-800"></span>
                </div>

                <span class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ auth('admin')->user()->getFullName() }}
                </span>

                <i id="user-dropdown-icon" class="fa-solid fa-chevron-down text-xs transition-transform"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="user-dropdown-menu"
                class="absolute left-0 mt-2 w-52 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg hidden z-10">

                <!-- Profile -->
                <a href="{{ route('admin.admin.profile') }}"
                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-regular fa-user text-base"></i>
                    <span>@lang('general.profile')</span>
                </a>

                <!-- Settings -->
                <a href="{{ route('admin.admin.security') }}"
                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-shield-halved text-base"></i>
                    <span>@lang('general.setting') (@lang('general.security'))</span>
                </a>

                <!-- Divider -->
                <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

                <!-- Logout -->
                <a href="{{ route('admin.logout') }}"
                    class="flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-right-from-bracket text-base"></i>
                    <span>@lang('general.logout')</span>
                </a>
            </div>
        </div>
    </div>
</header>
