<div class="content-add-order md:col-span-3 xl:col-span-2 flex flex-col border border-grayCustom-300 dark:border-grayCustom-700 dark:text-white ltr:border-l-0 bg-white dark:bg-grayCustom-800">
    <div class="border-b border-grayCustom-300 dark:border-darkBody flex items-center justify-between gap-x-4 h-11 px-4">
        <button type="button" id="modal_margin" class="modalTriggerBtn element-margin-cross w-full bg-grayCustom-200 dark:bg-grayCustom-900 h-6 text-sm rounded-md">Cross</button>
        <button type="button" id="modal_leverage" class="modalTriggerBtn w-full bg-grayCustom-200 dark:bg-grayCustom-900 h-6 text-sm rounded-md">1X</button>
    </div>

    {{--Tabs --}}
    <div class="flex items-center justify-between px-3 pt-3">
        <div class="tab-exchange-form flex items-center gap-x-3">
            <button type="button" id="limitTab" class="text-grayCustom-400 text-sm hover:text-tealCustom-500 pb-3 active">Limit</button>
            <button type="button" id="marketTab" class="text-grayCustom-400 text-sm hover:text-tealCustom-500 pb-3">Market</button>
        </div>
        <i class="fa-solid fa-circle-exclamation text-grayCustom-500 pb-3"></i>
    </div>

    <div class="px-3 flex items-center text-sm gap-x-1">
        <span class="text-grayCustom-500">Avbl</span>
        <span>15000 USDT</span>
    </div>

    <!--Form limit-->
    <form action="" data-tab-content="limitTab" class="content-form-exchange p-3">
        <label for="priceLimit" class="lebel-input text-sm flex rounded-sm items-center bg-grayCustom-200 dark:bg-grayCustom-900 border border-grayCustom-200 dark:border-grayCustom-900 hover:border-tealCustom-500 h-10 w-full">
            <span class="text-grayCustom-500 inline-block px-2">Price</span>
            <input type="text" id="priceLimit" dir="rtl" class="border-0 bg-inherit h-full w-full focus:shadow-none"/>
            <span class="px-2 inline-block uppercase">USDT</span>
        </label>
        <label for="priceLimit" class="lebel-input text-sm flex rounded-sm items-center bg-grayCustom-200 dark:bg-grayCustom-900 border border-grayCustom-200 dark:border-grayCustom-900 hover:border-tealCustom-500 h-10 w-full mt-3">
            <span class="text-grayCustom-500 inline-block px-2">Size</span>
            <input type="text" id="priceLimit" dir="rtl" class="input-size-limit border-0 bg-inherit h-full w-full focus:shadow-none"/>
            <span class="px-2 inline-block uppercase">usdt</span>
        </label>

        <!--slider-->
        <div class="slider-input relative my-2">
            <div class="tooltip-step-slider absolute left-1/2 -translate-x-3 -top-6 bg-grayCustom-500 text-xs w-9 h-5 hidden items-center justify-center text-white dark:text-inherit"></div>
            <input 
                type="range"
                min="0"
                max="100"
                step="1"
                value="0"
                class="block bg-grayCustom-200 shadow-none focus:shadow-none border-0 cursor-pointer text-grayCustom-900 text-sm rounded-lg w-full my-1 py-2"
            />
            <!--progress-->
            <div id="progressStep" class=" w-full bg-[#474d57] dark:bg-white left-0 h-[5px] block absolute top-2"></div>
            <ul class="slider-input-steps-container flex justify-between absolute w-full pointer-events-none">
                <li class="slider-input-step active"></li>
                <li class="slider-input-step"></li>
                <li class="slider-input-step"></li>
                <li class="slider-input-step"></li>
                <li class="slider-input-step"></li>
            </ul>
        </div>
        
        <div class="flex justify-between items-center mt-4 text-xs border-b border-grayCustom-300 dark:border-grayCustom-700 pb-4">
            <div class="flex items-center gap-x-2 dark:text-white">
                <span class="text-grayCustom-400">Buy</span>
                <span>0.0 USDT</span>
            </div>
            <div class="flex items-center gap-x-2 dark:text-white">
                <span class="text-grayCustom-400">Sell</span>
                <span>0.0 USDT</span>
            </div>
        </div>

        <!--Buttons-->
        <div class="flex items-center justify-between gap-x-3 mt-4">
            <button type="button" class="bg-tealCustom-500 text-white text-sm rounded-md h-9 text-center w-full hover:opacity-80">Buy/Long</button>
            <button type="button" class="bg-red-500 text-white text-sm rounded-md h-9 text-center w-full hover:opacity-80">Sell/Short</button>
        </div>

        <ul class="flex flex-col gap-y-2 mt-4">
            <li class="flex md:flex-col lg:flex-row items-center md:items-start lg:items-center justify-between">
                <div class="flex items-center gap-x-2 dark:text-white text-xs">
                    <span class="text-grayCustom-400">Cost:</span>
                    <span>9,676,89 USDT</span>
                </div>
                <div class="flex items-center gap-x-2 dark:text-white text-xs md:mt-2 lg:mt-0">
                    <span class="text-grayCustom-400">Cost:</span>
                    <span>9,676,89 USDT</span>
                </div>
            </li>
            <li class="flex md:flex-col lg:flex-row items-center md:items-start lg:items-center justify-between">
                <div class="flex items-center gap-x-2 dark:text-white text-xs">
                    <span class="text-grayCustom-400">Max:</span>
                    <span>9,676,89 USDT</span>
                </div>
                <div class="flex items-center gap-x-2 dark:text-white text-xs md:mt-2 lg:mt-0">
                    <span class="text-grayCustom-400">Max:</span>
                    <span>9,676,89 USDT</span>
                </div>
            </li>
        </ul>
    </form>

    <!--Form market-->
    <form action="" data-tab-content="marketTab" class="content-form-exchange hidden p-3">
        <label for="priceLimit" class="lebel-input text-sm flex rounded-sm items-center bg-grayCustom-200 dark:bg-grayCustom-900 border border-grayCustom-200 dark:border-grayCustom-900 hover:border-tealCustom-500 h-10 w-full mt-3">
            <span class="text-grayCustom-500 inline-block px-2">Size</span>
            <input type="text" id="priceLimit" dir="rtl" class="input-size-market border-0 bg-inherit h-full w-full focus:shadow-none"/>
            <span class="px-2 inline-block uppercase">usdt</span>
        </label>

        <!--slider-->
        <div class="slider-input relative my-2">
            <div class="tooltip-step-slider-market absolute left-1/2 -translate-x-3 -top-6 bg-grayCustom-500 text-xs w-9 h-5 hidden items-center justify-center text-white dark:text-inherit"></div>
            <input 
                type="range"
                min="0"
                max="100"
                step="1"
                value="0"
                class="input-rang-market block bg-grayCustom-200 shadow-none focus:shadow-none border-0 cursor-pointer text-grayCustom-900 text-sm rounded-lg w-full my-1 py-2"
            />
            <!--progress-->
            <div id="progressStepMarkte" class=" w-full bg-[#474d57] dark:bg-white left-0 h-[5px] block absolute top-2"></div>
            <ul class="slider-input-steps-container slider-steps-market flex justify-between absolute w-full pointer-events-none">
                <li class="slider-input-step active"></li>
                <li class="slider-input-step"></li>
                <li class="slider-input-step"></li>
                <li class="slider-input-step"></li>
                <li class="slider-input-step"></li>
            </ul>
        </div>

        <!--Link Before Login-->
        <a href="http://" class="w-full flex items-center justify-center h-9 rounded-md text-white text-sm hover:opacity-80 bg-tealCustom-500 mt-4">Login or Register</a>
    </form>
</div>