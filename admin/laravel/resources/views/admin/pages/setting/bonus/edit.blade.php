@extends('admin.layout.master')

@section('title', trans('general.bonus_details').' : '.\App\Models\Bonus::getSection($section))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{trans('general.bonus_details').' : '.\App\Models\Bonus::getSection($section)}}
                </h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.setting.bonus.index')}}"
                   class="flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-light hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <form action="{{route('admin.setting.bonus.update')}}" method="POST" id="bonusForm">
            @csrf
            @method('POST')

            <!-- Hidden Input for Section -->
            <input type="hidden" name="section" value="{{ $section }}">

            <!-- Days Input (Fixed) -->
            <div
                class="mb-6 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    @lang('general.max_days')
                </label>
                <input type="number" name="days" value="{{ old('days',$bonuses?->first()?->days) }}" required
                       oninvalid="this.setCustomValidity('@lang('general.max_days_required')')"
                       oninput="setCustomValidity('')"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="@lang('general.max_days_count')">
                @error("days")
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            @if($section == \App\Models\Bonus::SECTION_MONTHLY_TOTAL_TRANSACTIONS)
                <!-- Days Input (Fixed) -->
                <div
                    class="mb-6 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        @lang('general.expected_amount')
                    </label>
                    <input type="text" name="expected_amount"
                           value="{{ old('expected_amount',toCurrency($bonuses?->first()?->expected_amount)) }}" required
                           oninvalid="this.setCustomValidity('@lang('general.expected_amount_required')')"
                           oninput="setCustomValidity('')"
                           class="separator w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="@lang('general.expected_amount')">
                    @error("expected_amount")
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <!-- Dynamic Currencies List -->
            <div id="currencies-container" class="space-y-4">
                <!-- Initial Row -->
                <div id="container"
                     class="currency-row bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 relative group">
                    @php
                        $index = 0;
                        $bonus = null;
                    @endphp

                    @if(old('currencies'))
                        @foreach(old('currencies') as $index => $old)
                                <?php
                                $index++;
                                $bonus = (object)$old;
                                ?>
                            @include('admin.pages.setting.bonus.inputs')
                        @endforeach
                    @elseif($bonuses && count($bonuses) > 0)
                        @foreach($bonuses as $index => $bonus)
                                <?php
                                $index++;
                                ?>
                            @include('admin.pages.setting.bonus.inputs')
                        @endforeach
                    @endif

                    @if(!count($bonuses) && !old('currencies'))
                        @include('admin.pages.setting.bonus.inputs')
                    @endif


                </div>
            </div>

            <!-- Add Button -->
            <div class="mt-6">
                <button type="button" id="add_btn"
                        class="flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    @lang('general.add_currency_row')
                </button>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit"
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg shadow-lg transition-transform transform hover:scale-105">
                    @lang('general.save')
                </button>
            </div>
        </form>
    </main>
@endsection

@section('hidden_template')
    <div class="hidden" id="template">
        @include('admin.pages.setting.bonus.inputs')
    </div>
@endsection
@section('scripts')
    <script>
        const button = document.getElementById('add_btn');
        const container = document.getElementById('container');
        let index = {{$index}} ?? 0;
        button.addEventListener("click", (event) => {
            index++;
            const element = `
<div class="container_row grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
    <!-- Currency Select -->
    <div class="md:col-span-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang('general.currency')</label>
        <select name="currencies[${index}][currency_id]" required
          oninvalid="this.setCustomValidity('@lang('general.currency_id_required')')"
                oninput="setCustomValidity('')"
                class="select2 w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
            <option selected value="">{{ __('general.select') }}</option>
            @foreach($currencies as $id => $name)
            <option value="{{ $id }}">
                    {{ $name }}
            </option>
@endforeach
            </select>
            </div>

            <!-- Amount -->
            <div class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang('general.amount')</label>
        <input type="text" step="0.01" name="currencies[${index}][amount]" required
         oninvalid="this.setCustomValidity('@lang('general.amount_required')')"
               oninput="setCustomValidity('')"
               class="separator w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white @error('currencies.'.$index.'.amount') is-invalid @enderror"
               placeholder="0.00">
        </div>

        <!-- Inventory -->
        <div class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">@lang('general.inventory')</label>
        <input type="text" name="currencies[${index}][inventory]" required
            oninvalid="this.setCustomValidity('@lang('general.inventory_required')')"
               oninput="setCustomValidity('')"
               class="separator w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white @error('currencies.'.$index.'.inventory') is-invalid @enderror"
               placeholder="100">
        </div>

        <!-- Actions -->
        <div class="md:col-span-2 flex gap-2 delete_btn">
            <button type="button"
                    class="flex items-center px-4 py-2 bg-red-500 hover:bg-red-700 text-white rounded-lg transition-colors">
@lang('actions.delete')
            </button>
        </div>
    </div>
`;
            container.insertAdjacentHTML('beforeend', element);

            separator();


            $('.select2').select2({
                placeholder: "{{ __('general.choose_option') }}",
                allowClear: true,
                width: '100%',
            });
        })
        $(document).on('click', '.delete_btn button', function (e) {
            $(this).closest('.container_row').remove();
            e.preventDefault();
        });

    </script>
@endsection
