<div class="tabs-report-order flex flex-items-center gap-x-4 h-11 border-b border-grayCustom-300 dark:border-grayCustom-700 px-3 text-sm bg-white dark:bg-inherit">
    <button type="button" id="positionTab" class="text-grayCustom-400 relative active">Position(0)</button>
    <button type="button" id="historyTab" class="text-grayCustom-400 relative">History</button>
</div>
<!--Lists Position-->
<ul data-tab-content="positionTab" class="content-table position-list-order overflow-x-auto p-3 bg-white dark:bg-inherit">
    <li class="flex flex-col-reverse md:flex-row items-start md:items-center pb-3">
        <div class="title-list flex items-center flex-row md:flex-col">
            <!--title-->
            <div class="row flex flex-col md:flex-row md:hidden items-start md:items-center text-xs gap-2">
                <span class="text-grayCustom-400 w-36 mt-6">Symbol</span>
                <span class="text-grayCustom-400 w-36">Size</span>
                <span class="text-grayCustom-400 w-32">Entry Price</span>
                <span class="text-grayCustom-400 w-32">Breack Even Price</span>
                <span class="text-grayCustom-400 w-32">Mark Price</span>
                <span class="text-grayCustom-400 w-32">Liq.Price</span>
                <span class="text-grayCustom-400 w-32">Margin Ratio</span>
                <span class="text-grayCustom-400 w-32">Margin</span>
                <span class="text-grayCustom-400 w-32">PNL(ROI %)</span>
            </div>

            <div class="flex flex-col md:flex-row items-start md:items-center dark:text-white mt-4 text-xs gap-2">
                <div class="flex items-center w-36">
                    <span class="w-1 h-10 hidden md:block bg-tealCustom-500"></span>
                    <div class="flex flex-col items-start relative md:px-3">
                        <span>BTCUSDT</span>
                        <div class="flex items-center">
                            <span class="rounded-sm bg-white dark:bg-grayCustom-800 text-xs px-1">Perp</span>
                            <span class="rounded-sm bg-white dark:bg-grayCustom-800 text-xs px-1 mx-1">1X</span>
                        </div>
                    </div>
                </div>
                <span class="text-tealCustom-500 w-36">11795070 USDT</span>
                <span class="w-32">96,144.63</span>
                <span class="w-32">96,181.21</span>
                <span class="w-32">94,680.00</span>
                <span class="text-orange-400 w-32">--</span>
                <span class="w-32">0.39%</span>
                <div class="flex flex-row md:flex-col w-44 md:w-32">
                    <span>11,982.54 USDT</span>
                    <span>(Isolated)</span>
                </div>
                <div class="flex flex-row md:flex-col text-red-500 w-44 md:w-32">
                    <span>- 139.56 USDT</span>
                    <span>(- 1.20 %)</span>
                </div>
            </div>
        </div>

        <!--Close position-->
        <div class="row-close flex flex-col items-start text-yellow-500 text-sm">
            <span class="hidden">Close Position</span>
            <div class="flex items-center justify-between gap-x-5 mt-4">
                <div class="buttons-close-position flex items-center">
                    <button type="button" class="market pr-2 relative">Market</button>
                    <button type="button" class="px-2">Limit</button>
                </div>

                <div class="flex items-center gap-x-2 px-2">
                    <input 
                        type="text" 
                        value="96290.3"
                        class="border text-darkBody dark:text-white border-grayCustom-600 focus:shadow-none focus:border-yellow-500 text-xs hover:border-yellow-500 bg-inherit h-6 w-24 rounded-md"
                    />
                    <input 
                        type="text" 
                        value="0.123"
                        class="border text-darkBody dark:text-white border-grayCustom-600 focus:shadow-none focus:border-yellow-500 text-xs hover:border-yellow-500 bg-inherit h-6 w-24 rounded-md"
                    />
                </div>
            </div>
        </div>
    </li>
    <li class="flex flex-col-reverse md:flex-row items-start md:items-center pb-3">
        <div class="title-list flex items-center flex-row md:flex-col">
            <!--title-->
            <div class="row flex flex-col md:flex-row md:hidden items-start md:items-center text-xs gap-2">
                <span class="text-grayCustom-400 w-36 mt-6">Symbol</span>
                <span class="text-grayCustom-400 w-36">Size</span>
                <span class="text-grayCustom-400 w-32">Entry Price</span>
                <span class="text-grayCustom-400 w-32">Breack Even Price</span>
                <span class="text-grayCustom-400 w-32">Mark Price</span>
                <span class="text-grayCustom-400 w-32">Liq.Price</span>
                <span class="text-grayCustom-400 w-32">Margin Ratio</span>
                <span class="text-grayCustom-400 w-32">Margin</span>
                <span class="text-grayCustom-400 w-32">PNL(ROI %)</span>
            </div>

            <div class="flex flex-col md:flex-row items-start md:items-center dark:text-white mt-4 text-xs gap-2">
                <div class="flex items-center w-36">
                    <span class="w-1 h-10 hidden md:block bg-red-500"></span>
                    <div class="flex flex-col items-start relative md:px-3">
                        <span>BTCUSDT</span>
                        <div class="flex items-center">
                            <span class="rounded-sm bg-white dark:bg-grayCustom-800 text-xs px-1">Perp</span>
                            <span class="rounded-sm bg-white dark:bg-grayCustom-800 text-xs px-1 mx-1">1X</span>
                        </div>
                    </div>
                </div>
                <span class="text-red-500 w-36">11795070 USDT</span>
                <span class="w-32">96,144.63</span>
                <span class="w-32">96,181.21</span>
                <span class="w-32">94,680.00</span>
                <span class="text-orange-400 w-32">190.547.53</span>
                <span class="w-32">0.39%</span>
                <div class="flex flex-row md:flex-col w-44 md:w-32">
                    <span>11,982.54 USDT</span>
                    <span>(Isolated)</span>
                </div>
                <div class="flex flex-row md:flex-col text-red-500 w-44 md:w-32">
                    <span>- 139.56 USDT</span>
                    <span>(- 1.20 %)</span>
                </div>
            </div>
        </div>

        <!--Close position-->
        <div class="row-close flex flex-col items-start text-yellow-500 text-sm">
            <span class="hidden">Close Position</span>
            <div class="flex items-center justify-between gap-x-5 mt-4">
                <div class="buttons-close-position flex items-center">
                    <button type="button" class="market pr-2 relative">Market</button>
                    <button type="button" class="px-2">Limit</button>
                </div>

                <div class="flex items-center gap-x-2 px-2">
                    <input 
                        type="text" 
                        value="96290.3"
                        class="border text-darkBody dark:text-white border-grayCustom-600 focus:shadow-none focus:border-yellow-500 text-xs hover:border-yellow-500 bg-inherit h-6 w-24 rounded-md"
                    />
                    <input 
                        type="text" 
                        value="0.123"
                        class="border text-darkBody dark:text-white border-grayCustom-600 focus:shadow-none focus:border-yellow-500 text-xs hover:border-yellow-500 bg-inherit h-6 w-24 rounded-md"
                    />
                </div>
            </div>
        </div>
    </li>
</ul>
<!--Lists History-->
<ul data-tab-content="historyTab" class="content-table position-list-order hidden overflow-x-auto p-3 bg-white dark:bg-inherit">
    <li class="flex items-center pt-2 md:pt-0 pb-3 border-b md:border-0 lg:border-b border-grayCustom-300 dark:border-grayCustom-700">
        <div class="title-list flex flex-row md:flex-col">
            <!--title-->
            <div class="row flex flex-col md:flex-row md:hidden items-start md:items-center text-xs gap-2">
                <span class="text-grayCustom-400 w-36">Time</span>
                <span class="text-grayCustom-400 w-36">Symbol</span>
                <span class="text-grayCustom-400 w-36">Side</span>
                <span class="text-grayCustom-400 w-36">Price</span>
                <span class="text-grayCustom-400 w-36">Quantity</span>
                <span class="text-grayCustom-400 w-36">Fee</span>
                <span class="text-grayCustom-400 w-36">Role</span>
                <span class="text-grayCustom-400 w-36">Realised Profit</span>
            </div>
            <div class="flex flex-col md:flex-row items-start md:items-center dark:text-white md:mt-6 text-xs gap-2">
                <span class="w-36">2024-12-04 11:18:15</span>
                <div class="flex items-center w-36 gap-x-1">
                    <span>BTCUSDT</span>
                    <span class="rounded-sm bg-white dark:bg-grayCustom-800 text-xs px-1">Perp</span>
                </div>
                <span class="text-tealCustom-500 w-36">Buy</span>
                <span class="w-36">96,144.63</span>
                <span class="w-36">2375.5 USDT</span>
                <span class="w-36">0.950156200 USDT</span>
                <span class="w-36">Taket</span>
                <span class="w-36">-26.856326923 USDT</span>
            </div>
        </div>
    </li>
    <li class="flex items-center pt-2 md:pt-0 pb-3 border-b md:border-0 lg:border-b border-grayCustom-300 dark:border-grayCustom-700">
        <div class="title-list flex flex-row md:flex-col">
            <!--title-->
            <div class="row flex flex-col md:flex-row md:hidden items-start md:items-center text-xs gap-2">
                <span class="text-grayCustom-400 w-36">Time</span>
                <span class="text-grayCustom-400 w-36">Symbol</span>
                <span class="text-grayCustom-400 w-36">Side</span>
                <span class="text-grayCustom-400 w-36">Price</span>
                <span class="text-grayCustom-400 w-36">Quantity</span>
                <span class="text-grayCustom-400 w-36">Fee</span>
                <span class="text-grayCustom-400 w-36">Role</span>
                <span class="text-grayCustom-400 w-36">Realised Profit</span>
            </div>
            <div class="flex flex-col md:flex-row items-start md:items-center dark:text-white md:mt-6 text-xs gap-2">
                <span class="w-36">2024-12-04 11:18:15</span>
                <div class="flex items-center w-36 gap-x-1">
                    <span>BTCUSDT</span>
                    <span class="rounded-sm bg-white dark:bg-grayCustom-800 text-xs px-1">Perp</span>
                </div>
                <span class="text-tealCustom-500 w-36">Buy</span>
                <span class="w-36">96,144.63</span>
                <span class="w-36">2375.5 USDT</span>
                <span class="w-36">0.950156200 USDT</span>
                <span class="w-36">Taket</span>
                <span class="w-36">-26.856326923 USDT</span>
            </div>
        </div>
    </li>


    {{--No data--}}
    <div class="flex flex-col pt-20 items-center justify-center">
        <svg class="w-20 h-20" viewBox="0 0 96 96" xmlns="http://www.w3.org/2000/svg">
            <path opacity="0.5" d="M84 28H64V8l20 20z" fill="#AEB4BC"></path>
            <path opacity="0.2" fill-rule="evenodd" clip-rule="evenodd" d="M24 8h40v20h20v60H24V8zm10 30h40v4H34v-4zm40 8H34v4h40v-4zm-40 8h40v4H34v-4z" fill="#AEB4BC"></path>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M22.137 64.105c7.828 5.781 18.916 5.127 26.005-1.963 7.81-7.81 7.81-20.474 0-28.284-7.81-7.81-20.474-7.81-28.284 0-7.09 7.09-7.744 18.177-1.964 26.005l-14.3 14.3 4.243 4.243 14.3-14.3zM43.9 57.9c-5.467 5.468-14.331 5.468-19.799 0-5.467-5.467-5.467-14.331 0-19.799 5.468-5.467 14.332-5.467 19.8 0 5.467 5.468 5.467 14.332 0 19.8z" fill="#AEB4BC"></path>
        </svg>
        <span class="text-grayCustom-400 text-sm">No data</span>
    </div>
    {{--before login--}}
    <div class="flex items-center justify-center text-base text-tealCustom-500 pt-20">
        <a href="http://">Login</a>
        <span class="px-2">/</span>
        <a href="http://">Register</a>
    </div>
</ul>