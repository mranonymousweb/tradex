<div class="md:col-span-6 xl:col-span-6 2xl:col-span-8 flex flex-col border border-grayCustom-200 dark:border-grayCustom-700 ltr:border-l-0 rtl:border-r-0">
    <!--Ternd Currency-->
    <ul class="hidden md:flex items-center justify-start border-b dark:text-white border-grayCustom-200 dark:border-grayCustom-700 px-3 h-11 text-xs bg-white dark:bg-inherit">
        <li class="flex items-center gap-x-2 w-32">
            <span class="font-medium uppercase">BTCUSDT</span>
            <span class="text-green-500">+0.37%</span>
        </li>
        <li class="flex items-center gap-x-2 w-32">
            <span class="font-medium uppercase">ETHUSDT</span>
            <span class="text-green-500">+0.35%</span>
        </li>
        <li class="flex items-center gap-x-2 w-32">
            <span class="font-medium uppercase">BNBUSDT</span>
            <span class="text-red-500">-4.45%</span>
        </li>
    </ul>
    <!--prices-->
    <div class="flex items-start lg:items-center gap-x-10 lg:gap-x-2 px-3 relative bg-white dark:bg-inherit">
        <div class="flex flex-col lg:flex-row items-start lg:items-center gap-x-4 py-2">
            <div class="market-selection flex flex-col py-4 cursor-pointer">
                <div class="item-coin flex items-center">
                    <span class="border border-grayCustom-700 rounded-md w-6 h-6 hidden lg:flex items-center justify-center">
                        <i class="fa-solid fa-star text-tealCustom-500 text-xs"></i>
                    </span>
                    <div class="flex flex-col lg:flex-row lg:items-center mx-2 dark:text-white">
                        <span class="font-medium text-lg uppercase">BTCUSDT</span>
                        <div class="flex flex-row items-center">
                            <span class="rounded-sm bg-white dark:bg-grayCustom-800 text-xs px-1 mx-1">Perp</span>
                            <i class="fa-solid fa-caret-down text-xs text-grayCustom-500 mx-1 cursor-pointer"></i>
                        </div>
                    </div>

                    <!--Tooltip-->
                    <div>
                        <svg data-tooltip-target="guideCurrency" class="text-grayCustom-500 text-xl w-4 h-4 cursor-pointer" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11 8.2a4 4 0 00-4-4H3v14h5.16c1.193 0 2.243.635 2.86 1.6h1.96c.617-.965 1.668-1.6 2.86-1.6H21v-14h-4a4 4 0 00-4 4v8h-2v-8z" fill="currentColor"></path>
                        </svg>
                        <div id="guideCurrency" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-xs font-medium dark:text-white transition-opacity duration-300 bg-grayCustom-800 rounded-lg shadow-sm opacity-0 tooltip">
                            Tooltip content
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                </div>

                <!--Box Search Market base-->
                <div class="drop w-full md:w-96 bg-white dark:bg-grayCustom-800 shadow-2xl flex flex-col py-3 rounded-md z-100">
                    <div class="flex justify-center md:hidden items-center mb-5">
                        <span class="text-sm dark:text-white w-1/2 ml-auto">Market</span>
                        <i class="fa-solid fa-x close-drop-currency mr-4 dark:text-white"></i>
                    </div>
                    <form action="" class="px-3">
                        <label for="" class="h-9 block border border-grayCustom-500 hover:border-tealCustom-500 w-full rounded-md relative">
                            <input 
                                type="text" 
                                class="bg-inherit h-full w-full pr-2 border-0 dark:text-white text-sm pl-8 placeholder:text-grayCustom-400 focus:shadow-none focus:outline-none focus:border-tealCustom-500" 
                                placeholder="Search"
                            />
                            <i class="fa-solid fa-magnifying-glass absolute translate-y-1/2 left-2 text-grayCustom-500 text-sm -top-1"></i>
                        </label>
                    </form>
                    <div class="h-screen md:h-96 overflow-y-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-grayCustom-400 text-xs h-10">
                                    <th class="text-start font-thin pl-3 w-32">Symbols</th>
                                    <th class="pr-3 w-32">
                                        <div class="flex items-center justify-end md:justify-between w-full font-thin">
                                            <span class="mx-2 md:mx-0">Last Price</span>
                                            <span>24h %</span>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-row dark:text-white text-sm hover:bg-opacity-50 cursor-pointer h-8" onclick="window.location='http://127.0.0.1:8000/test'">
                                    <td class="pl-3">
                                        <i class="fa-solid fa-star text-tealCustom-500 text-xs"></i>
                                        <span class="uppercase font-medium">bnbusdc</span>
                                    </td>
                                    <td class="pr-3">
                                        <div class="flex flex-col md:flex-row items-end md:items-center justify-end md:justify-between w-full">
                                            <span>0.012342</span>
                                            <span class="text-green-500">25.70%</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-row dark:text-white text-sm hover:bg-opacity-50 cursor-pointer h-8" onclick="window.location='http://127.0.0.1:8000/test'">
                                    <td class="pl-3">
                                        <i class="fa-solid fa-star text-grayCustom-500 text-xs"></i>
                                        <span class="uppercase font-medium">bnbusdc</span>
                                    </td>
                                    <td class="pr-3">
                                        <div class="flex flex-col md:flex-row items-end md:items-center justify-end md:justify-between w-full">
                                            <span>0.012342</span>
                                            <span class="text-red-500">-25.70%</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-row dark:text-white text-sm hover:bg-opacity-50 cursor-pointer h-8" onclick="window.location='http://127.0.0.1:8000/test'">
                                    <td class="pl-3">
                                        <i class="fa-solid fa-star text-grayCustom-500 text-xs"></i>
                                        <span class="uppercase font-medium">bnbusdc</span>
                                    </td>
                                    <td class="pr-3">
                                        <div class="flex flex-col md:flex-row items-end md:items-center justify-end md:justify-between w-full">
                                            <span>0.012342</span>
                                            <span class="text-red-500">-25.70%</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-row dark:text-white text-sm hover:bg-opacity-50 cursor-pointer h-8" onclick="window.location='http://127.0.0.1:8000/test'">
                                    <td class="pl-3">
                                        <i class="fa-solid fa-star text-grayCustom-500 text-xs"></i>
                                        <span class="uppercase font-medium">bnbusdc</span>
                                    </td>
                                    <td class="pr-3">
                                        <div class="flex flex-col md:flex-row items-end md:items-center justify-end md:justify-between w-full">
                                            <span>0.012342</span>
                                            <span class="text-red-500">-25.70%</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-row dark:text-white text-sm hover:bg-opacity-50 cursor-pointer h-8" onclick="window.location='http://127.0.0.1:8000/test'">
                                    <td class="pl-3">
                                        <i class="fa-solid fa-star text-grayCustom-500 text-xs"></i>
                                        <span class="uppercase font-medium">bnbusdc</span>
                                    </td>
                                    <td class="pr-3">
                                        <div class="flex flex-col md:flex-row items-end md:items-center justify-end md:justify-between w-full">
                                            <span>0.012342</span>
                                            <span class="text-red-500">-25.70%</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-row dark:text-white text-sm hover:bg-opacity-50 cursor-pointer h-8" onclick="window.location='http://127.0.0.1:8000/test'">
                                    <td class="pl-3">
                                        <i class="fa-solid fa-star text-grayCustom-500 text-xs"></i>
                                        <span class="uppercase font-medium">bnbusdc</span>
                                    </td>
                                    <td class="pr-3">
                                        <div class="flex flex-col md:flex-row items-end md:items-center justify-end md:justify-between w-full">
                                            <span>0.012342</span>
                                            <span class="text-red-500">-25.70%</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-row dark:text-white text-sm hover:bg-opacity-50 cursor-pointer h-8" onclick="window.location='http://127.0.0.1:8000/test'">
                                    <td class="pl-3">
                                        <i class="fa-solid fa-star text-grayCustom-500 text-xs"></i>
                                        <span class="uppercase font-medium">bnbusdc</span>
                                    </td>
                                    <td class="pr-3">
                                        <div class="flex flex-col md:flex-row items-end md:items-center justify-end md:justify-between w-full">
                                            <span>0.012342</span>
                                            <span class="text-red-500">-25.70%</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="flex flex-col pt-20 items-center justify-center">
                                            <svg class="w-20 h-20" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path>
                                                <path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path>
                                            </svg>
                                            <span class="text-grayCustom-400 text-sm">No data</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="flex flex-col min-w-[100px]">
                <span class="text-xl dark:text-white">520.00</span>
                <span class="text-red-500 text-xs">-179.900 -25.70%</span>
            </div>
        </div>
        <div class="relative">
            <div id="containerSilde" class="overflow-scroll">
                <div class="grid grid-cols-2 lg:flex items-start lg:items-center text-xs gap-2 py-4">
                    <div class="flex flex-col gap-y-1 min-w-[50px]">
                        <span class="text-grayCustom-400">Mark</span>
                        <span class="dark:text-white">620.61</span>
                    </div>
                    <div class="flex flex-col gap-y-1 min-w-[50px]">
                        <span class="text-grayCustom-400">Index</span>
                        <span class="dark:text-white">620.61</span>
                    </div>
                    <div class="price-funding flex flex-col gap-y-1 min-w-[116px]">
                        <span class="text-grayCustom-400">Funding / Countdown</span>
                        <div class="flex items-center gap-x-2"><span class="text-yellow-500">0.0000%</span><span class="dark:text-white">05:35:52</span></div>
                    </div>
                    <div class="flex flex-col gap-y-1 min-w-[55px]">
                        <span class="text-grayCustom-400">24h High</span>
                        <span class="dark:text-white">700.00</span>
                    </div>
                    <div class="flex flex-col gap-y-1 min-w-[55px]">
                        <span class="text-grayCustom-400">24h Low</span>
                        <span class="dark:text-white">508.00</span>
                    </div>
                    <div class="flex flex-col gap-y-1 min-w-[100px]">
                        <span class="text-grayCustom-400 flex flex-col lg:flex-row lg:flex-wrap">24h Volume <span>(BNB)</span></span>
                        <span class="dark:text-white">49,202.62</span>
                    </div>
                    <div class="flex flex-col gap-y-1 min-w-[100px]">
                        <span class="text-grayCustom-400 flex flex-col lg:flex-row lg:flex-wrap">24h Volume <span>(USDC)</span></span>
                        <span class="dark:text-white">29,464,193.95</span>
                    </div>
                    <div class="flex flex-col gap-y-1 min-w-[125px]">
                        <span class="text-grayCustom-400 flex flex-col lg:flex-row lg:flex-wrap">Open Interrest <span>(USDC)</span></span>
                        <span class="dark:text-white">1,733,124.14</span>
                    </div>
                </div>
            </div>
            <button type="button" id="slideNext" class="absolute top-0 right-0 bg-white dark:bg-darkBody w-5 h-full 2xl:hidden"><i class="fa-solid fa-angle-up fa-rotate-90 dark:text-white"></i></button>
            <button type="button" id="slideBack" class="absolute hidden top-0 left-0 bg-white dark:bg-darkBody w-5 h-full "><i class="fa-solid fa-angle-up fa-rotate-270 dark:text-white"></i></button>
        </div>
    </div>

    <!--tabs mobile::Start-->
    <div class="button-tabs-orders flex flex-row md:hidden p-3 gap-x-3 border-t border-grayCustom-300 dark:border-grayCustom-700 dark:text-white text-sm bg-white dark:bg-inherit">
        <button type="button" id="chart-trd-tab" class="active">Chart</button>
        <button type="button" id="order-book-tab">Order Book</button>
        <button type="button" id="trades-tab">Trades</button>
    </div>
    <!--tabs mobile::End-->

    <!--Chart-->
    <div id="tradingview_chart_container" data-tab-content="chart-trd-tab" class="content-order w-full flex-1 min-h-[524px] max-h-[524px] z-10 md:order-1 lg:order-0 desktop-trading-view-wrapper active"></div>
</div>