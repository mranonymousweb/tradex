<div class="md:col-span-3 xl:col-span-2 md:flex flex-col border border-grayCustom-300 dark:border-grayCustom-700 dark:text-white bg-white dark:bg-inherit">
    <div class="px-3 h-11 border-b border-grayCustom-300 dark:border-grayCustom-700 hidden md:flex items-center">
        <h2>Order Book</h2>
    </div>
    <div data-tab-content="order-book-tab" class="content-order hidden md:flex flex-col">
        <!--Tilte-->
        <div class="flex items-center text-grayCustom-400 text-xs px-3 pt-3">
            <span class="w-1/2">Price(USDT)</span>
            <span class="w-1/4 text-end">Size(BTC)</span>
            <span class="w-1/2 text-end">Sum(BTC)</span>
        </div>
        <!--Sell-->
        <ul class="p-3 text-xs flex flex-col">
            <li class="flex items-center h-5 relative hover:dark:bg-grayCustom-800 cursor-pointer z-[2]">
                <!--progress::Start-->
                <div class="absolute top-0 right-0 h-full bg-[#fae8ea] dark:bg-[#2d1e24] w-[50%] z-[-1]"></div>
                <!--progress::End-->
                <span class="w-1/2 text-red-500">99102.4</span>
                <span class="w-1/4 text-end">1.275</span>
                <span class="w-1/2 text-end">45.443</span>
            </li>
            <li class="flex items-center h-5 relative hover:dark:bg-grayCustom-800 cursor-pointer z-[2]">
                <!--progress::Start-->
                <div class="absolute top-0 right-0 h-full bg-[#fae8ea] dark:bg-[#2d1e24] w-[70%] z-[-1]"></div>
                <!--progress::End-->
                <span class="w-1/2 text-red-500">99102.4</span>
                <span class="w-1/4 text-end">1.275</span>
                <span class="w-1/2 text-end">45.443</span>
            </li>
            <li class="flex items-center h-5 relative hover:dark:bg-grayCustom-800 cursor-pointer z-[2]">
                <!--progress::Start-->
                <div class="absolute top-0 right-0 h-full bg-[#fae8ea] dark:bg-[#2d1e24] w-[100%] z-[-1]"></div>
                <!--progress::End-->
                <span class="w-1/2 text-red-500">99102.4</span>
                <span class="w-1/4 text-end">1.275</span>
                <span class="w-1/2 text-end">45.443</span>
            </li>
            <li class="flex items-center h-5 relative hover:dark:bg-grayCustom-800 cursor-pointer z-[2]">
                <!--progress::Start-->
                <div class="absolute top-0 right-0 h-full bg-[#fae8ea] dark:bg-[#2d1e24] w-[10%] z-[-1]"></div>
                <!--progress::End-->
                <span class="w-1/2 text-red-500">99102.4</span>
                <span class="w-1/4 text-end">1.275</span>
                <span class="w-1/2 text-end">45.443</span>
            </li>
            <li class="flex items-center h-5 relative hover:dark:bg-grayCustom-800 cursor-pointer z-[2]">
                <!--progress::Start-->
                <div class="absolute top-0 right-0 h-full bg-[#fae8ea] dark:bg-[#2d1e24] w-[30%] z-[-1]"></div>
                <!--progress::End-->
                <span class="w-1/2 text-red-500">99102.4</span>
                <span class="w-1/4 text-end">1.275</span>
                <span class="w-1/2 text-end">45.443</span>
            </li>
            <li class="flex items-center h-5 relative hover:dark:bg-grayCustom-800 cursor-pointer z-[2]">
                <!--progress::Start-->
                <div class="absolute top-0 right-0 h-full bg-[#fae8ea] dark:bg-[#2d1e24] w-[30%] z-[-1]"></div>
                <!--progress::End-->
                <span class="w-1/2 text-red-500">99102.4</span>
                <span class="w-1/4 text-end">1.275</span>
                <span class="w-1/2 text-end">45.443</span>
            </li>
            <li class="flex items-center h-5 relative hover:dark:bg-grayCustom-800 cursor-pointer z-[2]">
                <!--progress::Start-->
                <div class="absolute top-0 right-0 h-full bg-[#fae8ea] dark:bg-[#2d1e24] w-[30%] z-[-1]"></div>
                <!--progress::End-->
                <span class="w-1/2 text-red-500">99102.4</span>
                <span class="w-1/4 text-end">1.275</span>
                <span class="w-1/2 text-end">45.443</span>
            </li>
        </ul>
        <!--Price-->
        <div class="flex items-center gap-x-2 px-3">
            <div class="flex items-center gap-x-2">
                <span class="text-red-500 text-lg">99090.0</span>
                <i class="fa-solid fa-arrow-down text-sm text-red-500"></i>

                <!--up-->
                {{--<i class="fa-solid fa-arrow-up text-sm text-tealCustom-500"></i>--}}
            </div>
            <span class="text-xs text-grayCustom-400">96888.0</span>
        </div>
        <!--Buy-->
        <ul class="p-3 text-xs flex flex-col">
            <li class="flex items-center h-5 relative hover:dark:bg-grayCustom-800 cursor-pointer z-[2]">
                <!--progress::Start-->
                <div class="absolute top-0 right-0 h-full bg-[#d9f1e7] dark:bg-[#192a28] w-[50%] z-[-1]"></div>
                <!--progress::End-->
                <span class="w-1/2 text-tealCustom-500">99102.4</span>
                <span class="w-1/4 text-end">1.275</span>
                <span class="w-1/2 text-end">45.443</span>
            </li>
            <li class="flex items-center h-5 relative hover:dark:bg-grayCustom-800 cursor-pointer z-[2]">
                <!--progress::Start-->
                <div class="absolute top-0 right-0 h-full bg-[#d9f1e7] dark:bg-[#192a28] w-[70%] z-[-1]"></div>
                <!--progress::End-->
                <span class="w-1/2 text-tealCustom-500">99102.4</span>
                <span class="w-1/4 text-end">1.275</span>
                <span class="w-1/2 text-end">45.443</span>
            </li>
            <li class="flex items-center h-5 relative hover:dark:bg-grayCustom-800 cursor-pointer z-[2]">
                <!--progress::Start-->
                <div class="absolute top-0 right-0 h-full bg-[#d9f1e7] dark:bg-[#192a28] w-[100%] z-[-1]"></div>
                <!--progress::End-->
                <span class="w-1/2 text-tealCustom-500">99102.4</span>
                <span class="w-1/4 text-end">1.275</span>
                <span class="w-1/2 text-end">45.443</span>
            </li>
            <li class="flex items-center h-5 relative hover:dark:bg-grayCustom-800 cursor-pointer z-[2]">
                <!--progress::Start-->
                <div class="absolute top-0 right-0 h-full bg-[#d9f1e7] dark:bg-[#192a28] w-[70%] z-[-1]"></div>
                <!--progress::End-->
                <span class="w-1/2 text-tealCustom-500">99102.4</span>
                <span class="w-1/4 text-end">1.275</span>
                <span class="w-1/2 text-end">45.443</span>
            </li>
            <li class="flex items-center h-5 relative hover:dark:bg-grayCustom-800 cursor-pointer z-[2]">
                <!--progress::Start-->
                <div class="absolute top-0 right-0 h-full bg-[#d9f1e7] dark:bg-[#192a28] w-[30%] z-[-1]"></div>
                <!--progress::End-->
                <span class="w-1/2 text-tealCustom-500">99102.4</span>
                <span class="w-1/4 text-end">1.275</span>
                <span class="w-1/2 text-end">45.443</span>
            </li>
            <li class="flex items-center h-5 relative hover:dark:bg-grayCustom-800 cursor-pointer z-[2]">
                <!--progress::Start-->
                <div class="absolute top-0 right-0 h-full bg-[#d9f1e7] dark:bg-[#192a28] w-[30%] z-[-1]"></div>
                <!--progress::End-->
                <span class="w-1/2 text-tealCustom-500">99102.4</span>
                <span class="w-1/4 text-end">1.275</span>
                <span class="w-1/2 text-end">45.443</span>
            </li>
            <li class="flex items-center h-5 relative hover:dark:bg-grayCustom-800 cursor-pointer z-[2]">
                <!--progress::Start-->
                <div class="absolute top-0 right-0 h-full bg-[#d9f1e7] dark:bg-[#192a28] w-[30%] z-[-1]"></div>
                <!--progress::End-->
                <span class="w-1/2 text-tealCustom-500">99102.4</span>
                <span class="w-1/4 text-end">1.275</span>
                <span class="w-1/2 text-end">45.443</span>
            </li>
        </ul>
    </div>

    <!--Trades-->
    <div class="px-3 h-11 border-y border-grayCustom-300 dark:border-grayCustom-700 hidden md:flex items-center">
        <h2>Trades</h2>
    </div>
    <div data-tab-content="trades-tab" class="content-order hidden md:flex flex-col">
        <!--Tilte-->
        <div class="flex items-center text-grayCustom-400 text-xs px-3 pt-3">
            <span class="w-1/2">Price(USDT)</span>
            <span class="w-1/4 text-end">Amount(BTC)</span>
            <span class="w-1/2 text-end">Time</span>
        </div>
        <!--Orders-->
        <ul class="p-3 text-xs flex flex-col overflow-y-auto max-h-44">
            <li class="flex items-center h-5">
                <span class="w-1/2 text-red-500">99102.4</span>
                <span class="w-1/4 text-end">0.010</span>
                <span class="w-1/2 text-end">13:35:55</span>
            </li>
            <li class="flex items-center h-5">
                <span class="w-1/2 text-tealCustom-500">99102.4</span>
                <span class="w-1/4 text-end">0.010</span>
                <span class="w-1/2 text-end">13:35:55</span>
            </li>
            <li class="flex items-center h-5">
                <span class="w-1/2 text-red-500">99102.4</span>
                <span class="w-1/4 text-end">0.010</span>
                <span class="w-1/2 text-end">13:35:55</span>
            </li>
            <li class="flex items-center h-5">
                <span class="w-1/2 text-red-500">99102.4</span>
                <span class="w-1/4 text-end">0.010</span>
                <span class="w-1/2 text-end">13:35:55</span>
            </li>
            <li class="flex items-center h-5">
                <span class="w-1/2 text-red-500">99102.4</span>
                <span class="w-1/4 text-end">0.010</span>
                <span class="w-1/2 text-end">13:35:55</span>
            </li>
            <li class="flex items-center h-5">
                <span class="w-1/2 text-tealCustom-500">99102.4</span>
                <span class="w-1/4 text-end">0.010</span>
                <span class="w-1/2 text-end">13:35:55</span>
            </li>
        </ul>
    </div>
</div>