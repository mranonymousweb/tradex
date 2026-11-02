<div id="offcanvas-responsive-menu-right"
     class="offcanvas invisible dark:bg-background-dark dark:text-white fixed top-0 right-0 sm:w-100 w-[80%] h-full bg-white shadow-lg transform -translate-x-full transition-transform opacity-0 z-50"
     role="dialog"
     aria-labelledby="cart-title"
     aria-modal="true">
    <!-- Header -->
    <header class="border-b p-3 flex items-center justify-between border-gray-400">
        <button
            onclick="closeOffcanvas()"
            class="cursor-pointer"
            aria-label="بستن "
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
            </svg>
        </button>
    </header>
    <!-- menu Items -->
    <main class="p-3 overflow-y-scroll h-full">
        <div class="flex pb-10 rounded flex-col w-full h-full bg-card-light dark:bg-card-dark">
            <!-- logo -->
            <div class="flex items-center justify-center h-20 p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-96 h-96 rounded-md  flex items-center justify-center">
                        <img
                            class="max-h-[50px] max-w-[96px] object-contain dark:hidden"
                            src="{{ data_get($metaData, config('constants.meta.logo'), '') }}"
                            alt="Logo"
                            loading="lazy"
                        />

                        <img
                            class="max-h-[50px] max-w-[96px] object-contain hidden dark:block"
                            src="{{ data_get($metaData, config('constants.meta.dark_logo') ?? "", '') }}"
                            alt="Logo"
                            loading="lazy"
                        />
                    </div>
                </div>
            </div>

            <!-- menu -->
            <div class="flex flex-col flex-grow p-4 overflow-y-auto">
                @include('admin.partial.menu', ['menuPrefix' => 'mobile'])

                <!-- Store -->
                <div class="mt-auto p-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                    <div class="flex items-center">
                        <div class="relative">
                            <img class="h-12 w-12 rounded-full border-2 border-white dark:border-gray-800 shadow"
                                 src="{{ auth('admin')->user()->getAvatar() }}" loading="lazy" alt="Store logo">
                            <span
                                class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-green-500 dark:bg-green-400 border-2 border-white dark:border-gray-800"></span>
                        </div>
                        <div class="mr-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth('admin')->user()->getFullName() }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth('admin')->user()->email ?? null }}</p>
                        </div>
                    </div>
                    <a href="{{route('admin.logout')}}"
                       class="mt-4 w-full flex items-center justify-center px-4 py-2 text-sm text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                             fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                                  clip-rule="evenodd"/>
                        </svg>
                        @lang('general.logout')
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>
