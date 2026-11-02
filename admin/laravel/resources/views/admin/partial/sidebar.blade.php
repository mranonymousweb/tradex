<div class="hidden lg:flex lg:flex-shrink-0">
    <div class="flex flex-col w-72 bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 shadow-sm">
        <!-- logo -->
        <div class="flex items-center justify-center h-20 p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-96 h-96 rounded-md  flex items-center justify-center">
                    <img
                        class="max-h-[96px] max-w-[96px] object-contain dark:hidden"
                        src="{{ data_get($metaData, config('constants.meta.logo'), '') }}"
                        alt="Logo"
                        loading="lazy"
                    />

                    <img
                        class="max-h-[96px] max-w-[96px] object-contain hidden dark:block"
                        src="{{ data_get($metaData, config('constants.meta.dark_logo') ?? "", '') }}"
                        alt="Logo"
                        loading="lazy"
                    />
                </div>
            </div>
        </div>

        <!-- menu -->
        <div class="flex flex-col flex-grow p-4 overflow-y-auto">
            @include('admin.partial.menu', ['menuPrefix' => 'desktop'])
            <!-- User Details -->
            <div class="mt-auto p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">

                <!-- User Info -->
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <img class="h-12 w-12 rounded-full border-2 border-white dark:border-gray-800 shadow object-cover"
                             src="{{ auth('admin')->user()->getAvatar() }}"
                             alt="User avatar">

                        <!-- Online indicator -->
                        <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-green-500 dark:bg-green-400 border-2 border-white dark:border-gray-800"></span>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ auth('admin')->user()->getFullName() }}
                        </p>
                    </div>
                </div>

                <!-- Logout Button -->
                <a href="{{ route('admin.logout') }}"
                   class="mt-4 w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium
              text-red-600 dark:text-red-400
              bg-white dark:bg-gray-700
              border border-gray-200 dark:border-gray-600
              rounded-lg
              hover:bg-gray-100 dark:hover:bg-gray-600
              transition">

                    <i class="fa-solid fa-right-from-bracket"></i>

                    <span>@lang('general.logout')</span>
                </a>

            </div>
        </div>
    </div>
</div>
