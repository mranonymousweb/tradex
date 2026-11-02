@extends('admin.layout.master')

@section('title', trans('setting.futures'))

@section('contents')

    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-background-dark">
        <!-- Page header -->

        @include('admin.pages.future.page-header')

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">

            <!-- Inventory increase form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft dark:shadow-soft-dark p-6 border border-gray-100 dark:border-gray-700">
                    <form action="{{ route('admin.future.asset.transaction') }}" method="post">
                        @csrf

                        <!-- Amount Section -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.amount') (USDT)</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @foreach([100, 150, 200,250] as $preset)
                                    <button type="button"
                                            class="py-2 px-3 border rounded-lg text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 border-gray-300 dark:border-gray-600 amount-btn"
                                            data-amount="{{ $preset }}">
                                        {{ number_format($preset) }}
                                    </button>
                                @endforeach
                            </div>

                            <div class="mt-3 relative">
                                <input type="text" name="amount" id="amount" value="{{ old('amount') }}"
                                       class="w-full sm:pr-24 pr-4 pl-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="">
                                <span class="absolute left-3 top-2.5 text-gray-500 dark:text-gray-400">USDT</span>
                                @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Type Section -->
                        <div class="mb-6">
                            <div class="space-y-3">
                                @foreach([1 => __('general.deposit'), 2 => __('general.withdraw')] as $value => $label)
                                    <div class="flex items-center">
                                        <input type="radio" id="type_{{ $value }}" name="type" value="{{ $value }}"
                                               class="h-4 w-4 text-primary dark:text-primary-dark focus:ring-primary border-gray-300 dark:border-gray-600"
                                            {{ old('type', 1) == $value ? 'checked' : '' }}>
                                        <label for="type_{{ $value }}" class="ms-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $label }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.description')</label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                      >{{ old('description') }}</textarea>
                        </div>

                        <button type="submit"
                                class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                            @lang('general.save')
                        </button>
                    </form>
                    <br>
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 dark:text-gray-400">@lang('general.inventory'):</span>
                            <span class="font-medium text-success dark:text-success-dark">{{toCurrency($inventory)}} USDT</span>
                        </div>
                    </div>
                    <br>
                    <table class="w-full">
                        <thead class="">
                        <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                            <th class="table-header py-4 font-medium">@lang('general.id')</th>
                            <th class="table-header py-4 font-medium">@lang('general.amount')</th>
                            <th class="table-header py-4 font-medium">@lang('general.type')</th>
                            <th class="table-header py-4 font-medium">@lang('general.date')</th>
                            <th class="table-header py-4 font-medium">@lang('general.description')</th>

                        </tr>
                        </thead>
                        <tbody class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                        @forelse( $transactions as $transaction)
                            <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    <span>{{$transaction?->id ?? ''}}</span>
                                </td>

                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    <span>{{ toCurrency($transaction?->amount ?? 0)}}</span>
                                </td>

                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    <span>{!! $transaction?->getType() ?? '' !!}</span>
                                </td>
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    <span>{{$transaction?->getCreatedAt() ?? ''}}</span>
                                </td>
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    <span>{{$transaction?->description ?? ''}}</span>
                                </td>

                            </tr>
                        @empty
                            <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                                <td class="whitespace-nowrap" colspan="4">
                                    <span> @lang('general.not_found')</span>
                                </td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table>

                </div>

                <!-- Pagination -->
                {{ $transactions->withQueryString()->links('pagination::tailwind') }}

            </div>
        </div>
    </main>

@endsection


