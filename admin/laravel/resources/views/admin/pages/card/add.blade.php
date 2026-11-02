@extends('admin.layout.master')

@section('title', trans('master.user_card'))
@push('styles')
    <style>

        .select2-container--default .select2-selection--single {
            height: 46px !important;
            border-radius: 0.75rem !important;
            border-color: #D1D5DB !important;
            display: flex;
            align-items: center;
        }
        .dark .select2-container--default .select2-selection--single {
            background-color: #374151 !important;
            border-color: #4B5563 !important;
            color: white !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
        }
    </style>
@endpush
@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900">

        <!-- Header Section -->
        <div class="max-w-full mx-auto mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    @lang('general.add_new_card')
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @lang('general.fill_required_fields_to_register_card')
                </p>
            </div>
            <div>
                <a href="{{route('admin.card.index')}}"
                   class="inline-flex items-center px-4 py-2.5 text-sm font-medium rounded-xl bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 shadow-sm transition-all dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <div class="max-w-full mx-auto">
            <form action="{{ route('admin.card.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">

                    <!-- Section 1: Ownership -->
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center mb-6">
                        <span class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-lg text-primary-600 mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">@lang('general.owner_information')</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- User Select -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.user') <span class="text-red-500">*</span></label>
                                <select name="user_id" id="user_id" class="select2 w-full rounded-xl border-gray-300 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600">
                                    <option value="">-- @lang('general.select_user') --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->getFullInfo() }}</option>
                                    @endforeach
                                </select>
                                @error('user_id') <p class="mt-1 text-xs text-red-500">{{$message}}</p> @enderror
                            </div>

                            <!-- Bank Select -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.bank') <span class="text-red-500">*</span></label>
                                <select name="bank_id" id="bank_id" class="select2 w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                                    <option value="">-- @lang('general.all') --</option>
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                                @error('bank_id') <p class="mt-1 text-xs text-red-500">{{$message}}</p> @enderror
                            </div>

                            <!-- Currency Select -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.currency') <span class="text-red-500">*</span></label>
                                <select name="currency_id" id="currency_id" class="select2 w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                                    <option value="">-- @lang('general.currency') --</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                @error('currency_id') <p class="mt-1 text-xs text-red-500">{{$message}}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Card Details -->
                    <div class="p-6 bg-gray-50/50 dark:bg-gray-800/50">
                        <div class="flex items-center mb-6">
                        <span class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-blue-600 mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </span>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">@lang('general.card_details')</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Card Number -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.card_number') <span class="text-red-500">*</span></label>
                                <input type="text" name="card_number" value="{{old('card_number')}}"
                                       class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-mono tracking-widest text-center"
                                       placeholder="XXXX-XXXX-XXXX-XXXX">
                                @error('card_number') <p class="mt-1 text-xs text-red-500">{{$message}}</p> @enderror
                            </div>

                            <!-- IBAN -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.iban') <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 font-mono text-sm pointer-events-none">IR</span>
                                    <input type="text" name="iban" value="{{old('iban')}}"
                                           class="w-full pl-9 pr-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-mono"
                                           placeholder="24-digit number">
                                </div>
                                @error('iban') <p class="mt-1 text-xs text-red-500">{{$message}}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Footer -->
                    <div class="p-6 bg-white dark:bg-gray-800 flex items-center justify-between border-t border-gray-100 dark:border-gray-700">
                        <span class="text-xs text-gray-500"><span class="text-red-500">*</span> @lang('general.indicates_required_fields')</span>
                        <button type="submit" class="inline-flex items-center px-8 py-3 text-sm font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 shadow-lg shadow-primary-500/30 transition-all active:scale-95">
                            @lang('general.save')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>


@endsection
