@extends('admin.layout.master')

@section('title', 'Admin/Dashboard')

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            {{-- Left Section --}}
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    @lang('general.hello_dear_manager')
                    <span class="text-xl">👋</span>
                </h2>

                <div class="mt-2 flex flex-col gap-1 text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1">
                            @lang('general.last_update_at'):
                        </span>
                        <span class="font-medium text-gray-700 dark:text-gray-200">
                            {{ getSystemDate() }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs md:text-sm">
                            @lang('general.dashboard_auto_refresh_notice')
                        </span>
                    </div>

                </div>
            </div>

            {{-- Right Section --}}
            <div class="flex items-center gap-3">

                <a href="{{ route('admin.packages.index') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium rounded-xl
                  bg-primary-600 text-white hover:bg-primary-500
                  shadow-sm transition-all duration-200
                  dark:bg-primary-500 dark:hover:bg-primary-600">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>

                    @lang('general.package_management')
                </a>
            </div>

        </div>

        <!-- Summary of cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">

            @if($isSwapInnerMethod)
                <a href="{{ route('admin.irt.usdt') }}"
                   class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700 hover:border-primary-400 dark:hover:border-primary-500 transition-colors duration-200 block">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">USDT/IRT</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white">@lang('general.swap_method_inner')</p>
                        </div>
                        <div class="p-3 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 dark:text-gray-400">@lang('setting.usdt_irt_live_origin_price')</span>
                            <span class="font-semibold text-gray-900 dark:text-white">
                                {{ filled($usdtIrtPrice->origin_last ?? null) ? toCurrency($usdtIrtPrice->origin_last) : '-' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 dark:text-gray-400">@lang('setting.usdt_irt_live_sell_price')</span>
                            <span class="font-semibold text-gray-900 dark:text-white">
                                {{ filled($usdtIrtPrice->last ?? null) ? toCurrency($usdtIrtPrice->last) : '-' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500 dark:text-gray-400">@lang('setting.usdt_irt_live_buy_price')</span>
                            <span class="font-semibold text-gray-900 dark:text-white">
                                {{ filled($usdtIrtPrice->buy_last ?? null) ? toCurrency($usdtIrtPrice->buy_last) : '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 text-end">
                        <span class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                            @lang('setting.usd_price_setting') →
                        </span>
                    </div>
                </a>
            @endif

            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('master.users')</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $allUser }} <span
                                class="text-sm">@lang('general.person')</span></p>
                    </div>
                    <div class="p-3 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <circle cx="12" cy="6" r="4" stroke="#00c950" stroke-width="1.5"></circle>
                                <path
                                    d="M19.9975 18C20 17.8358 20 17.669 20 17.5C20 15.0147 16.4183 13 12 13C7.58172 13 4 15.0147 4 17.5C4 19.9853 4 22 12 22C14.231 22 15.8398 21.8433 17 21.5634"
                                    stroke="#00c950" stroke-width="1.5" stroke-linecap="round"></path>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <span
                        class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full {{ $waitingUser > 0
                            ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 animate-pulse'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ $waitingUser }}@lang('general.waiting')
                    </span>
                    <a href="{{ route('admin.user.authentication') }}"
                        class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">@lang('general.view_all')</a>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('general.swap')</p>
                        <p class="text-2xl font-bold text-read-500 dark:text-white">{{ $totalSwap }} <span
                                class="text-sm">@lang('general.transaction')</span></p>
                    </div>
                    <div class="p-3 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M6 19L3 16M3 16L6 13M3 16H11C12.6569 16 14 14.6569 14 13V12M10 12V11C10 9.34315 11.3431 8 13 8H21M21 8L18 11M21 8L18 5"
                                    stroke="#00c950" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 space-y-3">

                    {{-- Waiting swaps --}}
                    <div class="flex justify-between items-center">
        <span
            class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full
            {{ $waitingSwap > 0
                ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 animate-pulse'
                : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
            {{ $waitingSwap }}@lang('general.waiting')
        </span>

                        <a href="{{ route('admin.swaps.index', ['status' => 'waiting']) }}"
                           class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                            @lang('general.view_all')
                        </a>
                    </div>

                    {{-- Rejected by supplier --}}
                    <div class="flex justify-between items-center">
        <span
            class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full
            {{ $rejectedBySupplierSwap?->count() > 0
                ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 animate-pulse'
                : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
            {{ $rejectedBySupplierSwap?->count() }}@lang('general.rejected_by_supplier')
        </span>

                        <a href="{{ route('admin.swaps.index', ['status' => \App\Models\Swap::STATUS_REJECTED_BY_SUPPLIER]) }}"
                           class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                            @lang('general.view_all')
                        </a>
                    </div>

                </div>

            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('general.tickets')</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTicket }} <span
                                class="text-sm">@lang('general.ticket_count')</span></p>
                    </div>
                    <div class="p-3 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path d="M8 9H16" stroke="#00c950" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M8 12.5H13.5" stroke="#00c950" stroke-width="1.5" stroke-linecap="round"></path>
                                <path
                                    d="M13.0867 21.3877L13.7321 21.7697L13.0867 21.3877ZM13.6288 20.4718L12.9833 20.0898L13.6288 20.4718ZM10.3712 20.4718L9.72579 20.8539H9.72579L10.3712 20.4718ZM10.9133 21.3877L11.5587 21.0057L10.9133 21.3877ZM1.25 10.5C1.25 10.9142 1.58579 11.25 2 11.25C2.41421 11.25 2.75 10.9142 2.75 10.5H1.25ZM3.07351 15.6264C2.915 15.2437 2.47627 15.062 2.09359 15.2205C1.71091 15.379 1.52918 15.8177 1.68769 16.2004L3.07351 15.6264ZM7.78958 18.9915L7.77666 19.7413L7.78958 18.9915ZM5.08658 18.6194L4.79957 19.3123H4.79957L5.08658 18.6194ZM21.6194 15.9134L22.3123 16.2004V16.2004L21.6194 15.9134ZM16.2104 18.9915L16.1975 18.2416L16.2104 18.9915ZM18.9134 18.6194L19.2004 19.3123H19.2004L18.9134 18.6194ZM19.6125 2.7368L19.2206 3.37628L19.6125 2.7368ZM21.2632 4.38751L21.9027 3.99563V3.99563L21.2632 4.38751ZM4.38751 2.7368L3.99563 2.09732V2.09732L4.38751 2.7368ZM2.7368 4.38751L2.09732 3.99563H2.09732L2.7368 4.38751ZM9.40279 19.2098L9.77986 18.5615L9.77986 18.5615L9.40279 19.2098ZM13.7321 21.7697L14.2742 20.8539L12.9833 20.0898L12.4412 21.0057L13.7321 21.7697ZM9.72579 20.8539L10.2679 21.7697L11.5587 21.0057L11.0166 20.0898L9.72579 20.8539ZM12.4412 21.0057C12.2485 21.3313 11.7515 21.3313 11.5587 21.0057L10.2679 21.7697C11.0415 23.0767 12.9585 23.0767 13.7321 21.7697L12.4412 21.0057ZM10.5 2.75H13.5V1.25H10.5V2.75ZM21.25 10.5V11.5H22.75V10.5H21.25ZM7.8025 18.2416C6.54706 18.2199 5.88923 18.1401 5.37359 17.9265L4.79957 19.3123C5.60454 19.6457 6.52138 19.7197 7.77666 19.7413L7.8025 18.2416ZM1.68769 16.2004C2.27128 17.6093 3.39066 18.7287 4.79957 19.3123L5.3736 17.9265C4.33223 17.4951 3.50486 16.6678 3.07351 15.6264L1.68769 16.2004ZM21.25 11.5C21.25 12.6751 21.2496 13.5189 21.2042 14.1847C21.1592 14.8438 21.0726 15.2736 20.9265 15.6264L22.3123 16.2004C22.5468 15.6344 22.6505 15.0223 22.7007 14.2868C22.7504 13.5581 22.75 12.6546 22.75 11.5H21.25ZM16.2233 19.7413C17.4786 19.7197 18.3955 19.6457 19.2004 19.3123L18.6264 17.9265C18.1108 18.1401 17.4529 18.2199 16.1975 18.2416L16.2233 19.7413ZM20.9265 15.6264C20.4951 16.6678 19.6678 17.4951 18.6264 17.9265L19.2004 19.3123C20.6093 18.7287 21.7287 17.6093 22.3123 16.2004L20.9265 15.6264ZM13.5 2.75C15.1512 2.75 16.337 2.75079 17.2619 2.83873C18.1757 2.92561 18.7571 3.09223 19.2206 3.37628L20.0044 2.09732C19.2655 1.64457 18.4274 1.44279 17.4039 1.34547C16.3915 1.24921 15.1222 1.25 13.5 1.25V2.75ZM22.75 10.5C22.75 8.87781 22.7508 7.6085 22.6545 6.59611C22.5572 5.57256 22.3554 4.73445 21.9027 3.99563L20.6237 4.77938C20.9078 5.24291 21.0744 5.82434 21.1613 6.73809C21.2492 7.663 21.25 8.84876 21.25 10.5H22.75ZM19.2206 3.37628C19.7925 3.72672 20.2733 4.20752 20.6237 4.77938L21.9027 3.99563C21.4286 3.22194 20.7781 2.57144 20.0044 2.09732L19.2206 3.37628ZM10.5 1.25C8.87781 1.25 7.6085 1.24921 6.59611 1.34547C5.57256 1.44279 4.73445 1.64457 3.99563 2.09732L4.77938 3.37628C5.24291 3.09223 5.82434 2.92561 6.73809 2.83873C7.663 2.75079 8.84876 2.75 10.5 2.75V1.25ZM2.75 10.5C2.75 8.84876 2.75079 7.663 2.83873 6.73809C2.92561 5.82434 3.09223 5.24291 3.37628 4.77938L2.09732 3.99563C1.64457 4.73445 1.44279 5.57256 1.34547 6.59611C1.24921 7.6085 1.25 8.87781 1.25 10.5H2.75ZM3.99563 2.09732C3.22194 2.57144 2.57144 3.22194 2.09732 3.99563L3.37628 4.77938C3.72672 4.20752 4.20752 3.72672 4.77938 3.37628L3.99563 2.09732ZM11.0166 20.0898C10.8136 19.7468 10.6354 19.4441 10.4621 19.2063C10.2795 18.9559 10.0702 18.7304 9.77986 18.5615L9.02572 19.8582C9.07313 19.8857 9.13772 19.936 9.24985 20.0898C9.37122 20.2564 9.50835 20.4865 9.72579 20.8539L11.0166 20.0898ZM7.77666 19.7413C8.21575 19.7489 8.49387 19.7545 8.70588 19.7779C8.90399 19.7999 8.98078 19.832 9.02572 19.8582L9.77986 18.5615C9.4871 18.3912 9.18246 18.3215 8.87097 18.287C8.57339 18.2541 8.21375 18.2487 7.8025 18.2416L7.77666 19.7413ZM14.2742 20.8539C14.4916 20.4865 14.6287 20.2564 14.7501 20.0898C14.8622 19.936 14.9268 19.8857 14.9742 19.8582L14.2201 18.5615C13.9298 18.7304 13.7204 18.9559 13.5379 19.2063C13.3646 19.4441 13.1864 19.7468 12.9833 20.0898L14.2742 20.8539ZM16.1975 18.2416C15.7862 18.2487 15.4266 18.2541 15.129 18.287C14.8175 18.3215 14.5129 18.3912 14.2201 18.5615L14.9742 19.8582C15.0192 19.832 15.096 19.7999 15.2941 19.7779C15.5061 19.7545 15.7842 19.7489 16.2233 19.7413L16.1975 18.2416Z"
                                    fill="#00c950"></path>
                            </g>
                        </svg>

                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <span
                        class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full {{ $waitingTicket > 0
                            ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 animate-pulse'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ $waitingTicket }}@lang('general.waiting')
                    </span>
                    <a href="{{ route('admin.ticket.index', ['status' => 'pending']) }}"
                        class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">@lang('general.view_all')</a>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('general.cards')</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalCard }} <span
                                class="text-sm">@lang('general.card')</span></p>
                    </div>
                    <div class="p-3 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                                    stroke="#00c950" stroke-width="1.5"></path>
                                <path d="M10 16H6" stroke="#00c950" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M14 16H12.5" stroke="#00c950" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M2 10L22 10" stroke="#00c950" stroke-width="1.5" stroke-linecap="round"></path>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <span
                        class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full {{ $waitingCard > 0
                            ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 animate-pulse'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ $waitingCard }}@lang('general.waiting')
                    </span>
                    <a href="{{ route('admin.card.index', ['status' => 'waiting']) }}"
                        class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">@lang('general.view_all')</a>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('general.staking')</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalStaking }} <span
                                class="text-sm">@lang('general.staking')</span></p>
                    </div>
                    <div class="p-3 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <span
                        class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full {{ $waitingStaking > 0
                            ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 animate-pulse'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ $waitingStaking }}@lang('general.waiting')
                    </span>
                    <a href="{{ route('admin.staking.index', ['status' => 'waiting']) }}"
                        class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">@lang('general.view_all')</a>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('general.withdraws')</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalWithdraw }} <span
                                class="text-sm">@lang('general.withdraw')</span></p>
                    </div>
                    <div class="p-3 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M12 9C11.4477 9 11 9.44771 11 10V15.5856L9.70711 14.2928C9.3166 13.9024 8.68343 13.9024 8.29292 14.2928C7.90236 14.6834 7.90236 15.3165 8.29292 15.7071L11.292 18.7063C11.6823 19.0965 12.3149 19.0968 12.7055 18.707L15.705 15.7137C16.0955 15.3233 16.0955 14.69 15.705 14.2996C15.3145 13.909 14.6814 13.909 14.2908 14.2996L13 15.5903V10C13 9.44771 12.5523 9 12 9Z"
                                    fill="#00c950"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M21 1C22.6569 1 24 2.34315 24 4V8C24 9.65685 22.6569 11 21 11H19V20C19 21.6569 17.6569 23 16 23H8C6.34315 23 5 21.6569 5 20V11H3C1.34315 11 0 9.65685 0 8V4C0 2.34315 1.34315 1 3 1H21ZM22 8C22 8.55228 21.5523 9 21 9H19V7H20C20.5523 7 21 6.55229 21 6C21 5.44772 20.5523 5 20 5H4C3.44772 5 3 5.44772 3 6C3 6.55229 3.44772 7 4 7H5V9H3C2.44772 9 2 8.55228 2 8V4C2 3.44772 2.44772 3 3 3H21C21.5523 3 22 3.44772 22 4V8ZM7 7V20C7 20.5523 7.44772 21 8 21H16C16.5523 21 17 20.5523 17 20V7H7Z"
                                    fill="#00c950"></path>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <span
                        class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full {{ $waitingWithdraw > 0
                            ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 animate-pulse'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ $waitingWithdraw }}@lang('general.waiting')
                    </span>
                    <a href="{{ route('admin.reports.transaction', ['type' => 2]) }}"
                        class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">@lang('general.view_all')</a>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">P2P</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">0 <span
                                class="text-sm">@lang('general.transaction')</span></p>
                    </div>
                    <div class="p-3 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.5 5C10.6716 5 10 5.67157 10 6.5V8H6.5C5.67157 8 5 8.67157 5 9.5V17.5C5 18.3284 5.67157 19 6.5 19H12.5C13.3284 19 14 18.3284 14 17.5V16H17.5C18.3284 16 19 15.3284 19 14.5V6.5C19 5.67157 18.3284 5 17.5 5H11.5ZM13 16H11.5C10.6716 16 10 15.3284 10 14.5V9H6.5C6.22386 9 6 9.22386 6 9.5V17.5C6 17.7761 6.22386 18 6.5 18H12.5C12.7761 18 13 17.7761 13 17.5V16ZM11 6.5C11 6.22386 11.2239 6 11.5 6H17.5C17.7761 6 18 6.22386 18 6.5V14.5C18 14.7761 17.7761 15 17.5 15H11.5C11.2239 15 11 14.7761 11 14.5V6.5Z"
                                    fill="#00c950"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 8.5C12 8.22386 12.2239 8 12.5 8H16.5C16.7761 8 17 8.22386 17 8.5C17 8.77614 16.7761 9 16.5 9H12.5C12.2239 9 12 8.77614 12 8.5Z"
                                    fill="#00c950"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 10.5C12 10.2239 12.2239 10 12.5 10H14.5C14.7761 10 15 10.2239 15 10.5C15 10.7761 14.7761 11 14.5 11H12.5C12.2239 11 12 10.7761 12 10.5Z"
                                    fill="#00c950"></path>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <span
                        class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full {{ 0 > 0
                            ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 animate-pulse'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                        0 @lang('general.waiting')
                    </span>
                    <a href="#"
                        class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">@lang('general.view_all')</a>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('master.admins')</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $admins }} <span
                                class="text-sm">@lang('master.admin')</span></p>
                    </div>
                    <div class="p-3 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                        <svg class="h-6 w-6" fill="#00c950" height="200px" width="200px" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="0 0 24 24" enable-background="new 0 0 24 24" xml:space="preserve">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g id="user-admin">
                                    <path
                                        d="M22.3,16.7l1.4-1.4L20,11.6l-5.8,5.8c-0.5-0.3-1.1-0.4-1.7-0.4C10.6,17,9,18.6,9,20.5s1.6,3.5,3.5,3.5s3.5-1.6,3.5-3.5 c0-0.6-0.2-1.2-0.4-1.7l1.9-1.9l2.3,2.3l1.4-1.4l-2.3-2.3l1.1-1.1L22.3,16.7z M12.5,22c-0.8,0-1.5-0.7-1.5-1.5s0.7-1.5,1.5-1.5 s1.5,0.7,1.5,1.5S13.3,22,12.5,22z">
                                    </path>
                                    <path
                                        d="M2,19c0-3.9,3.1-7,7-7c2,0,3.9,0.9,5.3,2.4l1.5-1.3c-0.9-1-1.9-1.8-3.1-2.3C14.1,9.7,15,7.9,15,6c0-3.3-2.7-6-6-6 S3,2.7,3,6c0,1.9,0.9,3.7,2.4,4.8C2.2,12.2,0,15.3,0,19v5h8v-2H2V19z M5,6c0-2.2,1.8-4,4-4s4,1.8,4,4s-1.8,4-4,4S5,8.2,5,6z">
                                    </path>
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <span
                        class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full {{ 0 > 0
                            ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 animate-pulse'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                        0 @lang('general.waiting')
                    </span>

                    <a href="{{ route('admin.admin.index') }}"
                        class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">@lang('general.view_all')</a>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('general.fishing_transactions')</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $fishingTransactions->count() }}
                            <span class="text-sm">@lang('general.fishing')</span>
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M12 9C11.4477 9 11 9.44771 11 10V15.5856L9.70711 14.2928C9.3166 13.9024 8.68343 13.9024 8.29292 14.2928C7.90236 14.6834 7.90236 15.3165 8.29292 15.7071L11.292 18.7063C11.6823 19.0965 12.3149 19.0968 12.7055 18.707L15.705 15.7137C16.0955 15.3233 16.0955 14.69 15.705 14.2996C15.3145 13.909 14.6814 13.909 14.2908 14.2996L13 15.5903V10C13 9.44771 12.5523 9 12 9Z"
                                    fill="#00c950"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M21 1C22.6569 1 24 2.34315 24 4V8C24 9.65685 22.6569 11 21 11H19V20C19 21.6569 17.6569 23 16 23H8C6.34315 23 5 21.6569 5 20V11H3C1.34315 11 0 9.65685 0 8V4C0 2.34315 1.34315 1 3 1H21ZM22 8C22 8.55228 21.5523 9 21 9H19V7H20C20.5523 7 21 6.55229 21 6C21 5.44772 20.5523 5 20 5H4C3.44772 5 3 5.44772 3 6C3 6.55229 3.44772 7 4 7H5V9H3C2.44772 9 2 8.55228 2 8V4C2 3.44772 2.44772 3 3 3H21C21.5523 3 22 3.44772 22 4V8ZM7 7V20C7 20.5523 7.44772 21 8 21H16C16.5523 21 17 20.5523 17 20V7H7Z"
                                    fill="#00c950"></path>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <span
                        class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full {{ 0 > 0
                            ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 animate-pulse'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                        0 @lang('general.waiting')
                    </span>
                    <a href="{{ route('admin.reports.transaction', ['status' => config('constants.transaction.fishing')]) }}"
                        class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">@lang('general.view_all')</a>
                </div>
            </div>
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('general.card_to_card')</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $cardToCard->count() }} <span
                                class="text-sm">@lang('general.card_to_card')</span></p>
                    </div>
                    <div class="p-3 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                                    stroke="#00c950" stroke-width="1.5"></path>
                                <path d="M10 16H6" stroke="#00c950" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M14 16H12.5" stroke="#00c950" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M2 10L22 10" stroke="#00c950" stroke-width="1.5" stroke-linecap="round"></path>
                            </g>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <span
                        class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full {{ $cardToCardWaiting > 0
                            ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 animate-pulse'
                            : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ $cardToCardWaiting }}@lang('general.waiting')
                    </span>
                    <a href="{{ route('admin.reports.transaction', ['status' => \App\Models\UserTransaction::STATUS_WAITING, 'section' => \App\Models\UserTransaction::SECTION_CARD_TO_CARD]) }}"
                        class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">@lang('general.view_all')</a>
                </div>
            </div>

            {{-- Deposit scanning providers. Rendered only when at least one API key is
                 stored, and only for admins who may manage suppliers. --}}
            @if(!empty($depositProviders) && auth()->user()?->can('admin.setting.supplier'))
                <div id="depositProvidersCard"
                     data-endpoint="{{ route('admin.setting.supplier.api.key.test') }}"
                     class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">@lang('general.deposit_providers')</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ count($depositProviders) }}
                                <span class="text-sm">@lang('general.deposit_providers_active')</span>
                            </p>
                        </div>
                        <div class="p-3 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0a8.949 8.949 0 0 0 4.951-1.488A3.987 3.987 0 0 0 13 16h-2a3.987 3.987 0 0 0-3.951 3.512A8.949 8.949 0 0 0 12 21Zm3-11a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
                        @foreach($depositProviders as $provider)
                            <div class="flex items-center justify-between gap-2"
                                 data-deposit-provider="{{ $provider['provider'] }}">
                                <span class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300 min-w-0">
                                    <span class="inline-block w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600 shrink-0"
                                          data-role="dot"></span>
                                    <span class="truncate">{{ $provider['label'] }}</span>
                                </span>
                                <span class="text-xs text-gray-400 dark:text-gray-500 shrink-0" data-role="value">
                                    @lang('general.api_key_testing')
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                        <a href="{{ route('admin.setting.supplier') }}"
                           class="text-xs text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">@lang('general.view_all')</a>
                    </div>
                </div>
            @endif

        </div>

        <!-- Tickets and Orders -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
            <!-- Activity table (Last Tickets) -->
            <div
                class="xl:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/80 flex flex-col justify-between">
                <div>
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700/50">
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white">@lang('general.last_ticket')</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50/70 dark:bg-gray-700/40">
                                <tr>
                                    <th
                                        class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                        @lang('general.id')</th>
                                    <th
                                        class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                        @lang('general.user')</th>
                                    <th
                                        class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                        @lang('general.title')</th>
                                    <th
                                        class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                        @lang('general.department')</th>
                                    <th
                                        class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                        @lang('general.status')</th>
                                    <th
                                        class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-end tracking-wider uppercase">
                                        @lang('general.operation')</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($tickets as $ticket)
                                    <tr
                                        class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                            #{{ $ticket?->id ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                            <x-admin.user-profile :user="$ticket->user" />
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 font-medium truncate max-w-[200px]">
                                            {{ $ticket?->title ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                {{ $ticket?->department?->getName() ?? '' }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {!! $ticket->getStatus() !!}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-end font-medium">
                                            <a href="{{ route('admin.ticket.chat', $ticket) }}"
                                                class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 inline-flex items-center gap-1 group transition-colors duration-150">
                                                @lang('general.details')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center space-y-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-8 h-8 text-gray-400 dark:text-gray-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                                                </svg>
                                                <span
                                                    class="text-gray-500 dark:text-gray-400 text-sm font-medium">@lang('general.not_found')</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Latest transactions -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/80 overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700/50">
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white">@lang('general.last_transactions')</h3>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @foreach ($transactions as $transaction)
                            <div
                                class="p-4 flex items-center justify-between hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                <div class="flex items-center space-x-3.5">
                                    <div
                                        class="p-2.5 rounded-xl bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-600/30 flex items-center justify-center">
                                        <img src="{{ $transaction->currency->icon }}" loading="lazy"
                                            class="h-6 w-6 object-contain" alt="currency" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">
                                            {{ $transaction->getType($transaction->type) }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 font-medium">
                                            {{ $transaction->getCreatedAt() }}</p>
                                    </div>
                                </div>
                                <div
                                    class="text-sm font-bold tracking-tight px-3 py-1 rounded-lg bg-opacity-10 dark:bg-opacity-20 {{ $transaction->getCssClass() }}">
                                    {{ toCurrency($transaction->amount) }} {{ $transaction->currency->symbol }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div
                    class="p-4 bg-gray-50/70 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700/50 text-center">
                    <a href="{{ route('admin.reports.transaction') }}"
                        class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors duration-150">
                        @lang('general.view_all')
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent orders -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700/80 overflow-hidden mb-6">
            <!-- Card Header -->
            <div class="p-6 border-b border-gray-100 dark:border-gray-700/50 flex items-center justify-between">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white">@lang('general.last_order')</h3>
                <a href="{{ route('admin.swaps.index') }}"
                    class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors duration-150">
                    @lang('general.view_all')
                </a>
            </div>

            <!-- Responsive Table Wrapper -->
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50/70 dark:bg-gray-700/40">
                        <tr>
                            <th
                                class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                @lang('general.id')</th>
                            <th
                                class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                @lang('general.user')</th>
                            <th
                                class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                @lang('general.from_currency')
                                / @lang('general.to_currency')</th>
                            <th
                                class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                @lang('general.fee')</th>
                            <th
                                class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                @lang('general.status')</th>
                            <th
                                class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-start tracking-wider uppercase">
                                @lang('general.created_at')</th>
                            <th
                                class="px-6 py-3.5 font-semibold text-xs text-gray-500 dark:text-gray-400 text-end tracking-wider uppercase">
                                @lang('general.operation')</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        @forelse($swaps as $swap)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors duration-150">
                                <!-- ID -->
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 dark:text-white">
                                    #{{ $swap->id }}
                                </td>

                                <!-- User -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-admin.user-profile :user="$swap?->user" />
                                </td>

                                <!-- Swap Routing -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                                        <span class="font-semibold">{{ toCurrency($swap->from_amount) }} <span
                                                class="text-xs text-gray-400 dark:text-gray-500 font-normal">{{ $swap->from_currency_symbol ?? '' }}</span></span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                        </svg>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ toCurrency($swap->to_amount) }}
                                            <span
                                                class="text-xs text-gray-400 dark:text-gray-500 font-normal">{{ $swap->to_currency_symbol ?? '' }}</span></span>
                                    </div>
                                </td>

                                <!-- Fee (Right Aligned) -->
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right font-medium text-gray-500 dark:text-gray-400">
                                    {{ toCurrency($swap->fee) }} <span
                                        class="text-xs text-gray-400 dark:text-gray-500 font-normal">{{ $swap->to_currency_symbol }}</span>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                        {{ \App\Models\Swap::getStatus($swap->status) }}
                                    </span>
                                </td>

                                <!-- Created At -->
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400 text-xs font-medium">
                                    {{ $swap->getCreateAt(true) }}
                                </td>

                                <!-- Operation Button -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.swaps.edit', $swap->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 shadow-sm transition-all duration-150">
                                        @lang('general.see')
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <!-- Empty State Block -->
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-8 h-8 text-gray-400 dark:text-gray-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0-4.5 4.5M21 7.5H7.5" />
                                        </svg>
                                        <span
                                            class="text-gray-500 dark:text-gray-400 text-sm font-medium">@lang('general.not_found')</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <!-- ================= end blog section ================= -->
@endsection

@section('scripts')
    <script>
        setInterval(() => {
            location.reload();
        }, 60000);
    </script>

    <script>
        (function () {
            const card = document.getElementById('depositProvidersCard');

            if (!card) {
                return;
            }

            const endpoint = card.dataset.endpoint;
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

            const text = {
                ok: @json(trans('general.api_key_connection_ok')),
                failed: @json(trans('general.api_key_connection_failed')),
                remaining: @json(trans('general.api_key_quota_remaining')),
            };

            card.querySelectorAll('[data-deposit-provider]').forEach(function (row) {
                const provider = row.getAttribute('data-deposit-provider');
                const dot = row.querySelector('[data-role="dot"]');
                const value = row.querySelector('[data-role="value"]');

                // cached:true keeps the auto refreshing dashboard from spending a
                // Tatum credit every minute; the settings page button stays live.
                fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({provider: provider, cached: true}),
                })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (result) {
                        const ok = !!result.ok;

                        dot.className = 'inline-block w-2 h-2 rounded-full shrink-0 ' +
                            (ok ? 'bg-green-500' : 'bg-red-500');

                        if (ok && result.quota) {
                            const q = result.quota;
                            value.textContent = text.remaining + ': ' +
                                Number(q.remaining).toLocaleString() + ' / ' + Number(q.limit).toLocaleString();
                            value.className = 'text-xs shrink-0 font-medium ' + (
                                q.percent >= 90 ? 'text-red-600 dark:text-red-400'
                                    : (q.percent >= 70 ? 'text-yellow-600 dark:text-yellow-400'
                                        : 'text-green-600 dark:text-green-400')
                            );
                        } else {
                            value.textContent = ok ? text.ok : text.failed;
                            value.className = 'text-xs shrink-0 ' + (ok
                                ? 'text-green-600 dark:text-green-400'
                                : 'text-red-600 dark:text-red-400');
                        }
                    })
                    .catch(function () {
                        dot.className = 'inline-block w-2 h-2 rounded-full shrink-0 bg-red-500';
                        value.textContent = text.failed;
                        value.className = 'text-xs shrink-0 text-red-600 dark:text-red-400';
                    });
            });
        })();
    </script>
@endsection
