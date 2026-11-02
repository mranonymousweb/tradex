@extends('user.layout.master')

@section('title', 'فیوچرز')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/futures/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/futures/css/style.css') }}">
@endpush

@section('contents')
    <div class="flex flex-col pb-32 xl:pb-5 pt-16 xl:pt-0 bg-white dark:bg-inherit" dir="ltr">
        <div class="grid grid-cols-1 md:grid-cols-12 xl:grid-cols-10 2xl:grid-cols-12">
            <!--Chart && Header-->
            @include('user.pages.market.futures.components.chart')
            {{--Order Book--}}
            @include('user.pages.market.futures.components.order-book')
            {{--Add Order--}}
            @include('user.pages.market.futures.components.add-order')
        </div>

        <!--Table-->
        @include('user.pages.market.futures.components.table')

        <!--Footer-->
        @include('user.pages.market.futures.components.footer')
    </div>

    <!--back drop-->
    <div class="back-drop-market fixed top-0 lef-0 w-full h-screen bg-black bg-opacity-75 z-50 hidden"></div>
@endsection


@push('modals')
    {{--Margin Modal--}}
    <div data-modal="modal_margin" class="modal-margin hidden fixed z-[999] top-1/2 left-1/2 w-full -translate-x-1/2 -translate-y-1/2">
        <div class="modal-content bg-white dark:bg-grayCustom-800 h-screen overflow-auto lg:h-auto mx-auto p-7 lg:rounded-lg max-w-md">
            {{-- title --}}
            <div class="flex justify-between dark:border-grayCustom-600 items-center pb-2 pt-3 lg:pt-0">
                <h2 class="dark:text-white font-bold text-xl">Margin Mode</h2>
                <span class="close cursor-pointer">
                    <i class="fa-solid fa-xmark text-redCustom-500 dark:text-grayCustom-300 text-2xl"></i>
                </span>
            </div>

            <!-- content -->
            <div class="flex items-center gap-x-1 dark:text-white mt-4">
                <span class="uppercase font-medium text-sm">btcusdt</span>
                <span class="rounded-sm bg-white dark:bg-grayCustom-700 text-xs px-1 mx-1">Perp</span>
            </div>
            <div class="modal-margin-button flex items-center justify-between dark:text-white mt-5 gap-x-5">
                <button type="button" class="cross border border-grayCustom-200 dark:border-grayCustom-700 hover:dark:border-white hover:border-grayCustom-700 w-full h-9 rounded-md active">Cross</button>
                <button type="button" class="isolate border border-grayCustom-200 dark:border-grayCustom-700 hover:dark:border-white hover:border-grayCustom-700 w-full h-9 rounded-md">Isolated</button>
            </div>
            <ul class="text-xs text-grayCustom-500 mt-4">
                <li class="list-disc list-inside">
                    Switching the margin mode will only apply it to the selected contract.
                </li>
                <li class="list-disc list-inside mt-5">
                    Cross Margin Mode: All cross positions under the same margin asset share the same asset cross margin balance. In the event of liquidation, your assets full margin balance along with any remaining open positions under the asset may be forfeited.
                </li>
                <li class="list-disc list-inside my-5">
                    Isolated Margin Mode: Manage your risk on individual positions by restricting the amount of margin allocated to each. If the margin ratio of a position reached 100%, the position will be liquidated. Margin can be added or removed to positions using this mode.
                </li>
            </ul>
            <button type="button" class="button-confirm-margin w-full bg-tealCustom-500 text-white h-10 rounded-md">Confirm</button>
        </div>
    </div>

    {{--Leverage Modal--}}
    <div data-modal="modal_leverage" class="modal-margin hidden fixed z-[999] top-1/2 left-1/2 w-full -translate-x-1/2 -translate-y-1/2">
        <div class="modal-content bg-white dark:bg-grayCustom-800 h-screen overflow-auto lg:h-auto mx-auto p-7 lg:rounded-lg max-w-md">
            {{-- title --}}
            <div class="flex justify-between dark:border-grayCustom-600 items-center pb-2 pt-3 lg:pt-0">
                <h2 class="dark:text-white font-bold text-xl">Adjust Leverage</h2>
                <span class="close close-modal-reverage cursor-pointer">
                    <i class="fa-solid fa-xmark text-redCustom-500 dark:text-grayCustom-300 text-2xl"></i>
                </span>
            </div>

            <!-- content -->
            <span class="text-grayCustom-400 text-xs">Leverage</span>
            <div dir="ltr" class="flex items-center justify-between bg-grayCustom-200 dark:bg-grayCustom-700 text-grayCustom-400 p-3 rounded-md mt-3">
                <button type="button" class="decrease-leverage"><i class="fa-solid fa-minus text-xl hover:dark:text-white hover:text-darkBody"></i></button>
                <span class="leverage-amount text-darkBody dark:text-white">1X</span>
                <button type="button" class="increase-leverage"><i class="fa-solid fa-plus text-xl hover:dark:text-white hover:text-darkBody"></i></button>
            </div>
            <div class="slider-input relative my-5" dir="ltr">
                <div class="tooltip-step-slider-leverage absolute left-0 -translate-x-3 -top-6 bg-grayCustom-500 text-xs w-9 h-5 hidden items-center justify-center text-white">1X</div>
                <input
                    type="range"
                    min="1"
                    max="125"
                    step="1"
                    value="1"
                    class="input-leverage block bg-grayCustom-200 shadow-none focus:shadow-none border-0 cursor-pointer text-grayCustom-900 text-sm rounded-lg w-full my-1 py-2"
                />
                <!--progress-->
                <div id="progressStepLeverage" class="bg-[#474d57] dark:bg-white left-0 h-[5px] block absolute top-2"></div>
                <ul class="slider-input-steps-container-leverage flex justify-between absolute w-full pointer-events-none">
                    <li class="slider-input-step active"></li>
                    <li class="slider-input-step"></li>
                    <li class="slider-input-step"></li>
                    <li class="slider-input-step"></li>
                    <li class="slider-input-step"></li>
                    <li class="slider-input-step"></li>
                </ul>
                <ul class="flex justify-between absolute w-full pointer-events-none text-white">
                    <li class="slider-input-step">1X</li>
                    <li class="slider-input-step">25X</li>
                    <li class="slider-input-step">50X</li>
                    <li class="slider-input-step">75X</li>
                    <li class="slider-input-step">100X</li>
                    <li class="slider-input-step">125X</li>
                </ul>
            </div>
            <ul class="text-xs text-grayCustom-400 flex flex-col gap-y-3 mt-14 mb-5 px-5">
                <li class="list-disc">Maximum position at current leverage: <span>500,000,000</span> USDT</li>
                <li class="list-disc">Please note that leverage changing will also apply for open positions and open orders.</li>
                <li class="flex gap-x-2 text-red-500">
                    <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                    Selecting higher leverage such as [10x] increases your liquidation risk. Always manage your risk levels. See our help article for more information.
                </li>
            </ul>
            <button type="button" class="button-confirm-leverage w-full bg-tealCustom-500 text-white h-10 rounded-md mt-5">Confirm</button>
        </div>
    </div>

@endpush



@push('scripts')
    {{--Chart::Start--}}
    <script>
        const symbol = 'BTC';
        const baseSymbol = 'USDT';
        window.symbol = symbol;
        window.baseSymbol = baseSymbol;
    </script>
    {{--Chart::End--}}
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
    <script src="{{ asset('assets/futures/js/trdChart.js') }}"></script>
    <script src="{{ asset('assets/futures/js/modal.js') }}"></script>

    <script>
        //input Range limit
        const slider = document.querySelector('.slider-input input');
        const progressStep = document.querySelector('#progressStep');
        const steps = document.querySelectorAll('.slider-input-steps-container li');
        const tooltipRang = document.querySelector('.tooltip-step-slider');
        const inputValueSize = document.querySelector('.input-size-limit');
        const min = slider.min;
        const max = slider.max;

        function updateSliderBackground(value) {
            const percentage = ((value - min) / (max - min)) * 100;
            progressStep.style.width = `${percentage}% `;
            const newPosition = 7 - (percentage * 0.2);
            tooltipRang.innerHTML = `<span>%${value}</span>`;
            tooltipRang.style.left = `calc(${percentage}% + (${newPosition}px))`
        }
        function updateActiveStep(value) {
            steps.forEach(step => step.classList.remove('active'));
            switch (true) {
                case value >= 100:
                    steps[4].classList.add('active');
                case value >= 75:
                    steps[3].classList.add('active');
                case value >= 50:
                    steps[2].classList.add('active');
                case value >= 25:
                    steps[1].classList.add('active');
                case value >= 0:
                    steps[0].classList.add('active');
                default:
                    break;
            }
        }
        function initSlider() {
            const value = slider.value;
            updateSliderBackground(value);
            updateActiveStep(value);
        }

        slider.addEventListener('input', () => {
            const value = slider.value;
            updateSliderBackground(value);
            updateActiveStep(value);
            inputValueSize.value = `% ${value}`
        });
        inputValueSize.addEventListener('focus', () => {
            inputValueSize.value = '';
            updateSliderBackground(0);
            updateActiveStep(0);
            slider.value = 0
        })
        initSlider();




        //input Range market
        const sliderMarket = document.querySelector('.slider-input .input-rang-market');
        const progressStepMarket = document.querySelector('#progressStepMarkte');
        const stepsMarket = document.querySelectorAll('.slider-steps-market li');
        const tooltipRangMarket = document.querySelector('.tooltip-step-slider-market');
        const inputValueSizeMarket = document.querySelector('.input-size-market');

        function updateSliderBackgroundMarket(value) {
            const percentage = ((value - sliderMarket.min) / (sliderMarket.max - sliderMarket.min)) * 100;
            progressStepMarket.style.width = `${percentage}% `;
            const newPosition = 7 - (percentage * 0.2);
            tooltipRangMarket.innerHTML = `<span>%${value}</span>`;
            tooltipRangMarket.style.left = `calc(${percentage}% + (${newPosition}px))`
        }
        function updateActiveStepMarket(value) {
            stepsMarket.forEach(step => step.classList.remove('active'));
            switch (true) {
                case value >= 100:
                    stepsMarket[4].classList.add('active');
                case value >= 75:
                    stepsMarket[3].classList.add('active');
                case value >= 50:
                    stepsMarket[2].classList.add('active');
                case value >= 25:
                    stepsMarket[1].classList.add('active');
                case value >= 0:
                    stepsMarket[0].classList.add('active');
                default:
                    break;
            }
        }
        function initSliderMarket() {
            const value = sliderMarket.value;
            updateSliderBackgroundMarket(value);
            updateActiveStepMarket(value);
        }

        sliderMarket.addEventListener('input', () => {
            const value = sliderMarket.value;
            updateSliderBackgroundMarket(value);
            updateActiveStepMarket(value);
            inputValueSizeMarket.value = `% ${value}`
        });
        inputValueSizeMarket.addEventListener('focus', () => {
            inputValueSizeMarket.value = '';
            updateSliderBackgroundMarket(0);
            updateActiveStepMarket(0);
            sliderMarket.value = 0
        })
        initSliderMarket();




        //Modal Margin
        const selectedMargin = document.querySelector('.element-margin-cross');
        const elementButtonsMargin = document.querySelectorAll('.modal-margin-button button');
        const buttonChooseCross = document.querySelector('.modal-margin-button .cross');
        const buttonChooseIsolate = document.querySelector('.modal-margin-button .isolate');
        const buttonConfirmMargin = document.querySelector('.button-confirm-margin');
        const modalMargin = document.querySelector('.close');
        let selectedValue = 'Cross';

        buttonChooseCross.addEventListener('click', () => handleChooseMargin(buttonChooseCross, 'Cross'));
        buttonChooseIsolate.addEventListener('click', () => handleChooseMargin(buttonChooseIsolate, 'Isolate'));
        buttonConfirmMargin.addEventListener('click', handleConfirmMargin);

        function handleChooseMargin (button, value) {
            elementButtonsMargin.forEach(button => button.classList.remove('active'));
            button.classList.add('active');
            selectedValue = value;
        }
        function handleConfirmMargin () {
            selectedMargin.innerHTML = selectedValue;
            modalMargin.click();
        }


        //Modal Range Leverage
        const sliderLeverage = document.querySelector('.input-leverage');
        const progressStepLeverage = document.querySelector('#progressStepLeverage');
        const stepsLeverage = document.querySelectorAll('.slider-input-steps-container-leverage li');
        const tooltipRangLeverage = document.querySelector('.tooltip-step-slider-leverage');
        const leverageAmount = document.querySelector('.leverage-amount');
        const buttonDecrease = document.querySelector('.decrease-leverage');
        const buttonIncrease = document.querySelector('.increase-leverage');
        const leverage = document.querySelector('#modal_leverage');
        const buttonConfirmLeverage = document.querySelector('.button-confirm-leverage');
        const closeModalLeverage = document.querySelector('.close-modal-reverage')


        function updateActiveStepLeverage(value) {
            stepsLeverage.forEach(step => step.classList.remove('active'));
            switch (true) {
                case value >= 100:
                    stepsLeverage[4].classList.add('active');
                case value >= 75:
                    stepsLeverage[3].classList.add('active');
                case value >= 50:
                    stepsLeverage[2].classList.add('active');
                case value >= 25:
                    stepsLeverage[1].classList.add('active');
                case value >= 0:
                    stepsLeverage[0].classList.add('active');
                default:
                    break;
            }
        }
        function updateProgressLeverage(value) {
            const precent = value - (value * 0.2);
            progressStepLeverage.style.width = `${precent}%`;
            const newPosition = 7 - (precent * 0.2);
            tooltipRangLeverage.style.left = `calc(${precent}% + (${newPosition}px))`
        }
        function initSliderLeverage() {
            const value = sliderLeverage.value;
            updateActiveStepLeverage(value);
        }
        function decreaseLeverage () {
            let value = sliderLeverage.value
            sliderLeverage.value = value - 1;
            updateActiveStepLeverage(sliderLeverage.value);
            updateProgressLeverage(sliderLeverage.value);
            leverageAmount.innerHTML = `${sliderLeverage.value}X`;
            tooltipRangLeverage.innerHTML = `<span>X${sliderLeverage.value}</span>`;
        }
        function increaseLeverage () {
            let int = parseInt(sliderLeverage.value)
            let valuePlus = int + 1;
            console.log(valuePlus)
            updateActiveStepLeverage(valuePlus);
            updateProgressLeverage(valuePlus);
            leverageAmount.innerHTML = `${valuePlus}X`;
            sliderLeverage.value = valuePlus;
            tooltipRangLeverage.innerHTML = `<span>X${valuePlus}</span>`;
        }

        sliderLeverage.addEventListener('input', () => {
            const value = sliderLeverage.value;
            updateActiveStepLeverage(value);
            updateProgressLeverage(value)
            leverageAmount.innerHTML = `${value}X`;
            tooltipRangLeverage.innerHTML = `<span>X${value}</span>`;
        });
        buttonDecrease.addEventListener('click', decreaseLeverage)
        buttonIncrease.addEventListener('click', increaseLeverage)
        buttonConfirmLeverage.addEventListener('click', () => {
            leverage.innerHTML = 'X' + sliderLeverage.value;
            closeModalLeverage.click();
        })
        initSliderLeverage();


        //Tabs
        const tabChangeList = document.querySelectorAll('.tabs-report-order button');
        const contentTable = document.querySelectorAll('.content-table');
        const tabChangeForm = document.querySelectorAll('.tab-exchange-form button');
        const contentFormExchange = document.querySelectorAll('.content-form-exchange');
        //mobile
        const tabToggleOrders = document.querySelectorAll('.button-tabs-orders button');
        const contentOrder = document.querySelectorAll('.content-order');


        function handleToggleTab(tabs, content) {
            tabs.forEach(tab => {
                function toggleTab () {
                    tabs.forEach(ele => ele.classList.remove('active'));
                    content.forEach(ele => ele.classList.add('hidden'));

                    this.classList.add('active');
                    let dataTab = document.querySelector(`[data-tab-content=${this.id}]`);
                    dataTab.classList.remove('hidden')
                }

                tab.addEventListener('click', toggleTab)
            })
        }
        handleToggleTab(tabChangeList, contentTable)
        handleToggleTab(tabChangeForm, contentFormExchange)
        //mobile
        handleToggleTab(tabToggleOrders, contentOrder)



        //Check signal
        const elementColor = document.querySelector('.check-network')
        const iconSignal = document.querySelector('.check-network i');
        const textCheckSignal = document.querySelector('.check-network span');

        function online() {
            const newText = textCheckSignal.innerText.replace('Unstable', 'Stable');
            const newColor = elementColor.className.replace('text-red-500', 'text-tealCustom-500');
            const icon = iconSignal.className.replace('fa-signal-slash', 'fa-signal')
            textCheckSignal.innerHTML = newText;
            elementColor.className = newColor;
            iconSignal.className = icon;
        }
        function offline() {
            const newText = textCheckSignal.innerText.replace('Stable', 'Unstable');
            const newColor = elementColor.className.replace('text-tealCustom-500', 'text-red-500');
            const icon = iconSignal.className.replace('fa-signal', 'fa-signal-slash')
            textCheckSignal.innerHTML = newText;
            elementColor.className = newColor;
            iconSignal.className = icon;
        }
        window.navigator.onLine ? online() : offline()
        window.addEventListener('online', online)
        window.addEventListener('offline', offline)



        //////////////////////  Mobile js  ///////////////////////
        const selectCurrency = document.querySelector('.market-selection .item-coin');
        const dropCurrency = document.querySelector('.market-selection .drop');
        const closeDropCurrency = document.querySelector('.close-drop-currency');
        const buttonOpenOrder = document.querySelectorAll('.button-open-orders button');
        const contentAddOrder = document.querySelector('.content-add-order');
        const backDropmarket = document.querySelector('.back-drop-market');

        //DropDown choose market
        selectCurrency.addEventListener('click', () => {
            dropCurrency.classList.add('show');
            backDropmarket.classList.remove('hidden');
            document.querySelector('body').classList.add('overflow-hidden');
        })
        closeDropCurrency.addEventListener('click', () => {
            dropCurrency.classList.remove('show');
            backDropmarket.classList.add('hidden');
            document.querySelector('body').classList.remove('overflow-hidden');
        })

        //open modal add order
        buttonOpenOrder.forEach(button => {
            button.addEventListener('click', () => {
                contentAddOrder.classList.add('show');
                backDropmarket.classList.remove('hidden');
                document.querySelector('body').classList.add('overflow-hidden');
            })
        })
        backDropmarket.addEventListener('click', () => {
            contentAddOrder.classList.remove('show');
            dropCurrency.classList.remove('show');
            backDropmarket.classList.add('hidden');
            document.querySelector('body').classList.remove('overflow-hidden');
        })

        //slide header chart
        let button = document.getElementById('slideNext');
            button.onclick = function () {
                let leftAmount = document.getElementById('containerSilde').scrollLeft += 100;
                if(leftAmount > 0) {
                    back.classList.remove('hidden')
                }
            };

            var back = document.getElementById('slideBack');
            back.onclick = function () {
                let leftAmount = document.getElementById('containerSilde').scrollLeft -= 100;
                if(leftAmount < 0) {
                    back.classList.add('hidden')
                }
            };

    </script>
@endpush
