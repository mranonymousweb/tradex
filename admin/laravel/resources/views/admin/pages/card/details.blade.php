@extends('admin.layout.master')

@section('title', trans('master.user_card'))
@push('styles')

    <style>

        .select2-container--default .select2-selection--single {
            height: 42px !important;
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
    </style>

@endpush
@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900">

        <!-- Page Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-xs text-gray-500">
                        <li>@lang('general.admin_panel')</li>
                        <li><div class="flex items-center"><svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg> @lang('general.cards')</div></li>
                    </ol>
                </nav>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    @lang('general.details') <span class="text-primary-600">(@lang('master.user_card'))</span>
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{route('admin.card.index')}}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition-all dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    @lang('master.back')
                </a>
                <a href="{{ route('admin.card.inquiry',$card) }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-md transition-all">
                    @lang('general.card_inquiry_title')
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            <!-- Main Form Column -->
            <div class="xl:col-span-2 space-y-8">
                <form action="{{ route('admin.card.update',$card) }}" method="post" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    @csrf
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">@lang('general.edit_information')</h3>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- Section 1: User & Identification -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.user') <span class="text-red-500">*</span></label>
                                <select name="user_id" class="select2 w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:border-gray-600 focus:ring-primary-500">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id',$card->user_id) == $user->id ? 'selected' : '' }}>{{ $user->getFullInfo() }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.bank') <span class="text-red-500">*</span></label>
                                <select name="bank_id" class="select2 w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                                    @foreach($banks as $bank)
                                        <option value="{{ $bank->id }}" {{ $card->bank_id == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.currency') <span class="text-red-500">*</span></label>
                                <select name="currency_id" class="select2 w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ old('currency_id',$card->currency_id) == $currency->id ? 'selected' : '' }}>{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="border-gray-100 dark:border-gray-700">

                        <!-- Section 2: Account Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.card_number')</label>
                                <input type="text" name="card_number" value="{{old('card_number',$card->card_number)}}"
                                       class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-mono tracking-widest text-center"
                                       placeholder="XXXX-XXXX-XXXX-XXXX">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.iban')</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold">IR</span>
                                    <input type="text" name="iban" value="{{old('iban',$card->iban)}}"
                                           class="w-full pl-10 pr-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-mono"
                                           placeholder="24-digit IBAN">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.account_number')</label>
                                <input type="text" name="account_number" value="{{ old('account_number', $card->account_number) }}"
                                       class="w-full px-4 py-2.5 border rounded-xl dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.account_number')">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.status')</label>
                                <select name="status" class="select2 w-full rounded-xl border-gray-300 dark:bg-gray-700 dark:border-gray-600">
                                    @foreach($options as $key => $option)
                                        <option value="{{ $key }}" {{ old('status',$card->status) == $key ? 'selected' : '' }}>{!! strip_tags($option) !!}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.description')</label>
                                <textarea rows="3" name="description" class="w-full px-4 py-2.5 border rounded-xl dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="...">{{old('description',$card->description)}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 dark:bg-gray-800/50 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-8 py-3 text-sm font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-700 focus:ring-4 focus:ring-primary-200 shadow-lg shadow-primary-500/30 transition-all">
                            @lang('general.save')
                        </button>
                    </div>
                </form>
            </div>

            <!-- History / Log Column -->
            <div class="space-y-6">
                <section class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 flex flex-col h-full">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">@lang('general.authentication_requests')</h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-start">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 uppercase text-xs text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold text-start">@lang('general.request_date')</th>
                                <th class="px-4 py-3 font-semibold text-start">@lang('general.description')</th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($authenticationRequests as $req)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-4 py-4 text-start">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-200 text-start">{{$req->getCreatedAt()}}</div>
                                        <div class="text-xs text-gray-500 text-start">@lang('general.id'): #{{$req->id}}</div>
                                    </td>
                                    <td class="px-4 py-4 text-start">
                                        <span class="inline-flex items-start px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                            {{trans($req->description)}}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        <p class="mt-2 text-sm text-gray-500">@lang('general.not_found')</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </main>


@endsection
