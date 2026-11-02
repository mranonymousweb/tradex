@extends('admin.layout.master')

@section('title', trans('general.bonuses'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">

        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    @lang('general.bonuses')
                </h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.setting.index')}}"
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

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @foreach($gifts as $value)

                <form method="POST"
                      action="{{ route('admin.option.save') }}"
                      class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 p-6 flex flex-col justify-between">
                    @csrf

                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-6">

                        <div>
                            <h3 class="font-semibold text-gray-800 dark:text-white">
                                {{ $value['label'] }}
                            </h3>

                            @if(!empty($value['route']))
                                <a href="{{ $value['route'] }}"
                                   class="text-md text-primary-600 hover:text-primary-800 font-medium">
                                    @lang('general.settings')
                                </a>
                            @endif

                            {{-- Status Badge --}}
                            @php
                                $isActive =
                                    isset($options[$value['key']]) &&
                                    $options[$value['key']] == config('constants.option.active');

                            @endphp

                            <span id="{{$value['key']}}"
                                  class="text-xs px-2 py-1 rounded-full {{ $isActive? 'bg-emerald-100 text-emerald-600': 'bg-red-100 text-red-600' }}">{{ $isActive ? __('general.active') : __('general.inactive') }}
                            </span>

                        </div>

                        {{-- Toggle --}}
                        <label class="relative inline-flex items-center cursor-pointer">

                            <input type="hidden"
                                   name="{{ $value['key'] }}"
                                   value="{{config('constants.option.inactive')}}">

                            <input type="checkbox"
                                   class="sr-only peer inputs"
                                   name="{{ $value['key'] }}"
                                   value="{{config('constants.option.active')}}"
                                   @if(isset($options[$value['key']]) && $options[$value['key']] == config('constants.option.active'))
                                       checked
                                @endif
                            >

                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full
                                      peer-checked:bg-green-600 transition-colors duration-300">
                            </div>

                            <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow
                                        transition-transform duration-300
                                        peer-checked:translate-x-full">
                            </div>

                        </label>
                    </div>

                </form>

            @endforeach
            {{-- Bonus Reports Card --}}
            <a href="{{route('admin.setting.bonus.logs')}}"
               class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 p-6 flex flex-col justify-between">

                <div class="flex items-center justify-between mb-6">

                    <div>
                        <h3 class="font-semibold text-gray-800 dark:text-white">
                            @lang('general.bonus_reports')
                        </h3>

                        <span
                            class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-600">
               @lang('general.see_reports')
            </span>
                    </div>

                    {{-- Icon --}}
                    <div class="p-3 rounded-xl bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M9 17v-6h13M9 17l-4-4m4 4l-4 4"/>
                        </svg>
                    </div>

                </div>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    @lang('general.bonus_report_description')
                </p>

            </a>

        </div>

    </main>
@endsection

@section('scripts')
    <script>

        const inputs = document.querySelectorAll('.inputs');
        inputs.forEach((element) => {
            element.addEventListener('change', async (event) => {
                const isChecked = event.currentTarget.checked;
                await setRequest(isChecked, event);
            })
        })

        async function setRequest(isChecked, event) {
            const url = "{{route('admin.option.save') }}";

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    },
                    body: JSON.stringify({
                        [event.target.name]: isChecked ? "{{ config('constants.option.active') }}" : "{{ config('constants.option.inactive') }}",
                        fetch: true
                    }),
                });

                if (!response.ok) {
                    mboxError();
                    return;
                }
                const span = document.getElementById(event.target.name)

                if (isChecked) {
                    span.classList.remove('bg-red-100', 'text-red-600');
                    span.classList.add('bg-emerald-100', 'text-emerald-600');
                    span.textContent = "{{__('general.active')}}";
                } else {
                    span.classList.remove('bg-emerald-100', 'text-emerald-600');
                    span.classList.add('bg-red-100', 'text-red-600');
                    span.textContent = "{{__('general.inactive')}}";
                }


                mboxSuccess();

            } catch (error) {
                console.error('Fetch error:', error.message);
            }
        }
    </script>
@endsection
