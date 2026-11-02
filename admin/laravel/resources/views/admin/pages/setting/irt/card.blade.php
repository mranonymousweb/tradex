@extends('admin.layout.master')

@section('title', trans('setting.card_settings'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-dark dark:text-light">@lang('setting.card_settings')</h2>
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


        <!-- Option form -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">

                    <form action="{{ route('admin.irt.card.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Card Number --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('setting.card_number')<span class="text-red-500">*</span>
                                </label>
                                <input required type="text" name="card_number"
                                       value="{{ old('card_number')}}"
                                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('setting.card_number')"
                                       oninvalid="this.setCustomValidity('@lang('setting.card_number_required')')"
                                       oninput="setCustomValidity('')"
                                >
                                @error('card_number')
                                <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Iban --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('setting.iban')<span class="text-red-500">*</span>
                                </label>
                                <input type="text" required
                                       name="iban"
                                       value="{{ old('iban')}}"
                                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('setting.iban')"
                                       oninvalid="this.setCustomValidity('@lang('setting.iban_required')')"
                                       oninput="setCustomValidity('')"
                                >
                                @error('iban')
                                <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Card Owner --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('setting.card_owner')<span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       required
                                       name="card_owner"
                                       value="{{ old('card_owner')}}"
                                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('setting.card_owner')"
                                       oninvalid="this.setCustomValidity('@lang('setting.card_owner_required')')"
                                       oninput="setCustomValidity('')"
                                >
                                @error('card_owner')
                                <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- Banks --}}
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('setting.bank_name')
                                    <span class="text-red-500">*</span></label>
                                <select name="bank_id"
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @foreach($banks as $bank)
                                        <option
                                            @selected(old('bank_id') == $bank->id) value="{{ $bank->id }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                                @error('priority')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>

                            {{-- Currency --}}
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"> @lang('general.currency')
                                    <span class="text-red-500">*</span></label>
                                <select name="currency_id"
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    @foreach($currencies as $currency)
                                        <option
                                            @selected(old('currency_id') == $currency->id) value="{{ $currency->id }}">{{ $currency->symbol.' '.$currency->name }}</option>
                                    @endforeach
                                </select>
                                @error('currency_id')
                                <span class="text-red-500">{{$message}}</span>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <div>
                                <div class="pt-1">
                                    <button type="submit"
                                            class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                        @lang('general.save')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between mt-5">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('setting.card_list')</h2>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <table class="w-full">
                        <thead class="">
                        <tr class="border-b border-gray-200 dark:border-gray-600  dark:text-gray-300 text-gray-700  h-16">
                            <th class="table-header py-4 font-medium">@lang('general.id')</th>
                            <th class="table-header py-4 font-medium">@lang('setting.card_number')</th>
                            <th class="table-header py-4 font-medium">@lang('setting.bank_name')</th>
                            <th class="table-header py-4 font-medium">@lang('setting.iban')</th>
                            <th class="table-header py-4 font-medium">@lang('general.currency')</th>
                            <th class="table-header py-4 font-medium">@lang('general.operation')</th>

                        </tr>
                        </thead>
                        <tbody
                            class="divide-y space-y-5 text-sm py-5 text-gray-500 dark:text-gray-400 dark:divide-gray-700">
                        @foreach($accounts as $account)
                            <tr class="border-b border-gray-200 dark:border-gray-600 text-center h-16 ">
                                <td class="whitespace-nowrap max-xl:min-w-70">{{ $account->id }}</td>
                                <td class="whitespace-nowrap max-xl:min-w-70">{{ $account->card_number }}</td>
                                <td class="whitespace-nowrap max-xl:min-w-70">{{ $account->bank->name }}</td>
                                <td class="whitespace-nowrap max-xl:min-w-70">{{ $account->iban }}</td>
                                <td class="whitespace-nowrap max-xl:min-w-70">{{ $account->currency->symbol.' '.$account->currency->name }}</td>
                                <td class="whitespace-nowrap max-xl:min-w-70">
                                    <a data-modal-target="update_account_{{$account->id}}"
                                       class="modal-trigger px-3 py-1 border rounded-lg text-sm font-medium text-white bg-primary-600 dark:bg-primary-500 hover:bg-primary-500 dark:hover:bg-primary-600 border-primary-600 dark:border-primary-500">@lang('general.edit')</a>


                                    @push('modals')
                                        <div id="update_account_{{$account->id}}" role="dialog" aria-modal="true"
                                             aria-labelledby="filterModalTitle"
                                             data-modal-id="update_account_{{$account->id}}"
                                             class="modal hidden fixed inset-0 z-50 overflow-auto backdrop-blur bg-opacity-50">
                                            <div
                                                class="relative p-4 w-full max-w-xl m-auto flex items-center min-h-screen">
                                                <div
                                                    class="relative dark:bg-background-dark border-gray-300 border bg-white rounded-lg shadow-lg w-full"
                                                    itemscope itemtype="http://schema.org/Place">
                                                    <!-- Header -->
                                                    <div
                                                        class="flex justify-between items-center p-4 dark:border-b-white border-b">
                                                        <h3 id="filterModalTitle" class="text-xl  dark:text-white"
                                                            itemprop="name">@lang('setting.edit_card')
                                                            &nbsp; {{$account->id}}</h3>
                                                        <button data-modal-close
                                                                class="text-gray-500 hover:text-gray-700 cursor-pointer text-3xl"
                                                                aria-label="بستن پنجره فیلتر">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                 viewBox="0 0 24 24" stroke-width="1.5"
                                                                 stroke="currentColor" class="size-6 dark:text-white">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      d="M6 18 18 6M6 6l12 12"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <!-- Content -->
                                                    <div class="p-4">
                                                        <form action="{{ route('admin.irt.card.update',$account->id) }}"
                                                              method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                                                                {{-- Card Number --}}
                                                                <div>
                                                                    <input type="hidden" name="id"
                                                                           value="{{$account->id}}">
                                                                    <label
                                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                                        @lang('setting.card_number')<span
                                                                            class="text-red-500">*</span>
                                                                    </label>
                                                                    <input required type="text" name="card_number"
                                                                           value="{{ old('card_number',$account->card_number ?? null)}}"
                                                                           class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                           placeholder="@lang('setting.card_number')"
                                                                           oninvalid="this.setCustomValidity('@lang('setting.card_number_required')')"
                                                                           oninput="setCustomValidity('')"
                                                                    >
                                                                    @error('card_number')
                                                                    <span class="text-red-500">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                                {{-- Iban --}}
                                                                <div>
                                                                    <label
                                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                                        @lang('setting.iban')<span class="text-red-500">*</span>
                                                                    </label>
                                                                    <input type="text" required
                                                                           name="iban"
                                                                           value="{{ old('iban',$account->iban ?? null)}}"
                                                                           class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                           placeholder="@lang('setting.iban')"
                                                                           oninvalid="this.setCustomValidity('@lang('setting.iban_required')')"
                                                                           oninput="setCustomValidity('')"
                                                                    >
                                                                    @error('iban')
                                                                    <span class="text-red-500">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                                {{-- Card Owner --}}
                                                                <div>
                                                                    <label
                                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                                        @lang('setting.card_owner')<span
                                                                            class="text-red-500">*</span>
                                                                    </label>
                                                                    <input type="text"
                                                                           required
                                                                           name="card_owner"
                                                                           value="{{ old('card_owner',$account->card_owner ?? null)}}"
                                                                           class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                                                           placeholder="@lang('setting.card_owner')"
                                                                           oninvalid="this.setCustomValidity('@lang('setting.card_owner_required')')"
                                                                           oninput="setCustomValidity('')"
                                                                    >
                                                                    @error('card_owner')
                                                                    <span class="text-red-500">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                {{-- Banks --}}
                                                                <div class="mb-4">
                                                                    <label
                                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('setting.bank_name')
                                                                        <span class="text-red-500">*</span></label>
                                                                    <select name="bank_id"
                                                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                                        @foreach($banks as $bank)
                                                                            <option
                                                                                @selected(old('bank_id',$account->bank_id ?? null) == $bank->id) value="{{ $bank->id }}">{{ $bank->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('priority')
                                                                    <span class="text-red-500">{{$message}}</span>
                                                                    @enderror
                                                                </div>
                                                                {{-- Submit --}}
                                                                <div class="pt-1 flex items-center gap-3">
                                                                    <button type="submit"
                                                                            class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                                                        @lang('general.save')
                                                                    </button>

                                                                    <button type="button" data-modal-close
                                                                            class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-red-600 text-white hover:bg-red-700 shadow-sm dark:bg-red-500 dark:hover:bg-red-600">
                                                                        @lang('general.cancel_button')
                                                                    </button>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endpush

                                    <button
                                        onclick="mboxDelete('{{route('admin.irt.card.destroy',$account)}}')"
                                        type="button"
                                        class="px-3 py-1 border rounded-lg text-sm font-medium text-white bg-danger dark:bg-danger hover:bg-danger dark:hover:bg-danger border-danger dark:border-danger">
                                        @lang('general.delete')
                                    </button>

                                    <form id="delete-form" method="POST" style="display:none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection


