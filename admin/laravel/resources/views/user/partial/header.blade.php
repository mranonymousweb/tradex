<header class="flex items-center justify-between px-6 py-4 bg-white dark:bg-card-dark border-b border-gray-200 dark:border-gray-700">
    <div class="flex items-center">
        <!-- Button menu for mobile -->
        <button onclick="toggleOffcanvas('offcanvas-responsive-menu-right')"
                class="lg:hidden text-gray-500 dark:text-gray-400 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- search -->
        <div class="relative max-lg:hidden mr-4 lg:mr-0 lg:w-96">
                        <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                            </svg>
                        </span>
            <input type="text" class="w-full pr-10 pl-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-500" placeholder="جستجو در پنل کاربری...">
        </div>
    </div>

    <!-- top menu -->
    <div class="flex items-center space-x-4 space-x-reverse">
        <!-- Change mode button -->
        <button id="dark-mode-toggle"
                class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden dark:block" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 dark:hidden" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        </button>

        <!-- site notification -->
        <button onclick="toggleOffcanvas('offcanvas-notification-left')"
                class="relative text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <span class="absolute top-0 right-0 w-2 h-2 rounded-full bg-danger dark:bg-danger-dark"></span>
        </button>

        <!-- user messages -->
        <button onclick="toggleOffcanvas('offcanvas-user-message-left')"
                class="relative text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
            <span class="absolute top-0 right-0 w-2 h-2 rounded-full bg-success dark:bg-success-dark"></span>
        </button>

        <!-- user drop down -->
        <div class="relative ms-3" id="user-dropdown">
            <!-- Dropdown Button -->
            <button onclick="toggleUserDropdown()" id="user-dropdown-button" class="flex items-center focus:outline-none">
                <div class="relative">
                    <img class="h-8 w-8 max-sm:hidden rounded-full border-2 border-white dark:border-gray-800 shadow" src="{{asset('assets/images/user/user.jpg')}}" alt="پروفایل کاربر">
                    <span class="absolute bottom-0 right-0 w-2 h-2 rounded-full bg-green-500 dark:bg-green-400 border-2 border-white dark:border-gray-800"></span>
                </div>
                <span class="mr-2 text-sm font-medium text-gray-900 dark:text-white">مریم محمدی</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" id="user-dropdown-icon" class="size-4 ms-2 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"></path>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div id="user-dropdown-menu"
                 class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg z-10 shadow-lg hidden">
                <!-- Profile Link -->
                <a href="#"
                   class="flex items-center px-4 py-2 text-sm text-gray-700 space-x-1 rounded-lg dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                    <span>پروفایل</span>
                </a>

                <!-- Settings Link -->
                <a href="#"
                   class="flex items-center px-4 py-2 text-sm text-gray-700 space-x-1 rounded-lg dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281a1.125 1.125 0 0 0 .865.997l1.437.553a1.125 1.125 0 0 1 .51 1.685l-1.004.827a1.125 1.125 0 0 0 0 1.742l1.004.827a1.125 1.125 0 0 1-.51 1.685l-1.437.553a1.125 1.125 0 0 0-.865.997l-.213 1.281a1.125 1.125 0 0 1-1.11.94h-2.593a1.125 1.125 0 0 1-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.865-.997l-1.437-.553a1.125 1.125 0 0 1-.51-1.685l1.004-.827a1.125 1.125 0 0 0 0-1.742l-1.004-.827a1.125 1.125 0 0 1 .51-1.685l1.437-.553a1.125 1.125 0 0 0 .865-.997l.213-1.281Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    <span>تنظیمات</span>
                </a>

                <!-- Logout Link -->
                <a href="#"
                   class="flex items-center px-4 py-2 text-sm text-gray-700 space-x-1 rounded-lg dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                    </svg>
                    <span>خروج</span>
                </a>
            </div>
        </div>

    </div>
</header>
