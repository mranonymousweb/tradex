@extends('admin.layout.master')

@section('title', trans('general.update_department'))

@section('contents')

    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-dark dark:text-light">@lang('general.update_department')</h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.department.index')}}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-light hover:bg-gray-100 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <!-- Department form -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Department info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic info card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.department.update',$department) }}" method="POST">
                        @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.title')<span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{old('name',$department->getName())}}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="@lang('general.title')">
                            @error('name')
                            <span class="text-red-500">{{$message}}</span>
                            @enderror

                        </div>
                        <div>
                            <label  class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.status')</label>
                            <select name="status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="" @selected(old('status') === null)>{{ __('general.select') }} ({{ __('general.status') }})</option>
                                @foreach($statuses as $key => $title)
                                    <option value="{{ $key }}" @selected(old('status',$department->status) == $key)>{!! $title !!} </option>
                                @endforeach
                            </select>

                            @error('status')
                            <span class="text-red-500">{{$message}}</span>
                            @enderror

                        </div>
                        <div class="pt-4">
                            <button type="submit" class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                               @lang('general.save')
                            </button>
                        </div>
                    </div>
                    </form>
                </div>

            </div>

        </div>
    </main>
    <!-- ================= end blog section ================= -->
@endsection

@push('scripts')

    <script src="{{asset('assets/js/plugin/chart-js/chart.js')}}"></script>
    <script>
        // Display the current date in Persian format
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('fa-IR');

        // General settings for Chart.js to support RTL
        Chart.defaults.font.family = 'payda, sans-serif';
        Chart.defaults.layout.padding = {
            right: 20
        };

        // Create the chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar', // Chart type (bar, line, pie, etc.)
            data: {
                labels: ['دیدن محصول', 'اضافه کردن به سبد خرید', 'خرید', 'افزودن به علاقه مندی', 'نظر'],
                datasets: [{
                    label: 'فعالیت های یک هفته گذشته شما',
                    data: [12, 8, 5, 7, 3],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(139, 92, 246, 0.7)'
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(139, 92, 246, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 20
                        }
                    },
                    title: {
                        display: true,
                        text: 'فعالیت های شما',
                        font: {
                            size: 16
                        }
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                return 'تعداد: ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Function to change the chart type dynamically
        function changeChartType(type) {
            salesChart.config.type = type;
            salesChart.update();
        }
    </script>

@endpush
