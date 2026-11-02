@extends('admin.layout.master')

@php
    $title = $type === \App\Models\WalletAddressList::TYPE_WHITE
        ? __('general.wallet_address_whitelist')
        : __('general.wallet_address_blacklist');
@endphp

@section('title', $title)

@section('contents')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-900">

        <div class="max-w-7xl mx-auto mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <nav class="flex mb-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                        <span>@lang('setting.mange_currency_and_network')</span>
                        <span class="mx-2">/</span>
                        <span class="text-primary-600">{{ $title }}</span>
                    </nav>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        {{ $title }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">@lang('general.wallet_address_list_page_hint')</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.setting.wallet-address-list.index') }}"
                       class="inline-flex items-center px-4 py-2.5 text-sm font-semibold rounded-xl bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 shadow-sm transition-all dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        @lang('master.back')
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Manual add --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
                            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">@lang('general.wallet_address_list_add_manual')</h3>
                    </div>
                </div>

                <form action="{{ route('admin.setting.wallet-address-list.store', ['type' => $type]) }}" method="post"
                      class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                        <div class="md:col-span-5">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.wallet_address')</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                   placeholder="@lang('general.enter_wallet_address')"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:ring-2 focus:ring-primary-500 transition-all outline-none text-sm font-mono @error('address') border-red-500 @enderror"/>
                            @error('address')
                            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-5">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">@lang('general.description')</label>
                            <input type="text" name="description" value="{{ old('description') }}"
                                   placeholder="@lang('general.description')"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:ring-2 focus:ring-primary-500 transition-all outline-none text-sm @error('description') border-red-500 @enderror"/>
                            @error('description')
                            <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 md:pt-7">
                            <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-500/20 transition-all active:scale-95 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4v16m8-8H4"/>
                                </svg>
                                @lang('general.save')
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Excel import --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between gap-3 flex-wrap">
                        <div class="flex items-center gap-2">
                            <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white">@lang('general.wallet_address_list_import_excel')</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">@lang('general.wallet_address_list_excel_hint')</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.setting.wallet-address-list.sample', ['type' => $type]) }}"
                           class="inline-flex items-center px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-sm font-semibold text-primary-600 hover:text-primary-800 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4v12m0 0l-4-4m4 4l4-4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
                            </svg>
                            @lang('general.wallet_address_list_download_sample')
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.setting.wallet-address-list.import', ['type' => $type]) }}"
                      method="post" enctype="multipart/form-data" class="p-6"
                      onsubmit="handleWalletAddressImportSubmit(this)">
                    @csrf

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        <div class="lg:col-span-5">
                            <div class="h-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-5">
                                <h4 class="text-sm font-bold text-gray-800 dark:text-white mb-4">
                                    @lang('general.wallet_address_list_guide_title')
                                </h4>
                                <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                                    <li class="flex items-start gap-3">
                                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 text-xs font-bold">1</span>
                                        <span>@lang('general.wallet_address_list_guide_step_1')</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 text-xs font-bold">2</span>
                                        <span>@lang('general.wallet_address_list_guide_step_2')</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 text-xs font-bold">3</span>
                                        <span>@lang('general.wallet_address_list_guide_step_3')</span>
                                    </li>
                                </ul>
                                <div class="mt-5 rounded-xl border border-dashed border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                                    <div class="flex items-center gap-2 text-sm font-semibold text-gray-800 dark:text-white">
                                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        @lang('general.wallet_address_list_tip_title')
                                    </div>
                                    <p class="mt-2 text-xs leading-6 text-gray-500 dark:text-gray-400">
                                        @lang('general.wallet_address_list_tip_text')
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-7">
                            <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 space-y-5">
                                <div class="group relative p-8 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl text-center bg-gray-50/30 dark:bg-gray-900/10 hover:border-primary-500 transition-all"
                                     id="wallet-address-drop-zone">
                                    <input required type="file" name="file" id="wallet-address-file-upload"
                                           accept=".xlsx,.xls,.csv"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 @error('file') border-red-500 @enderror"
                                           onchange="updateWalletAddressFileName()">

                                    <div class="mb-3">
                                        <div class="w-12 h-12 mx-auto bg-white dark:bg-gray-700 rounded-lg shadow-sm flex items-center justify-center border border-gray-100 dark:border-gray-600">
                                            <svg class="w-6 h-6 text-gray-400 group-hover:text-primary-500" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <p id="wallet-address-file-name-text"
                                       class="text-sm font-bold text-gray-700 dark:text-gray-200">@lang('general.select_csv_xlsx')</p>
                                    <p class="text-xs text-gray-400 uppercase font-semibold mt-1">@lang('general.drop_file_here')</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">@lang('general.wallet_address_list_upload_help')</p>
                                </div>

                                @error('file')
                                <p class="text-red-500 text-xs font-medium">{{ $message }}</p>
                                @enderror

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-3">
                                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            @lang('general.wallet_address_list_meta_format')
                                        </div>
                                        <div class="mt-1 text-sm font-bold text-gray-800 dark:text-white">
                                            @lang('general.wallet_address_list_meta_format_value')
                                        </div>
                                    </div>
                                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-3">
                                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            @lang('general.wallet_address_list_meta_action')
                                        </div>
                                        <div class="mt-1 text-sm font-bold text-gray-800 dark:text-white">
                                            @lang('general.wallet_address_list_meta_action_value')
                                        </div>
                                    </div>
                                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-3">
                                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            @lang('general.wallet_address_list_meta_tip')
                                        </div>
                                        <div class="mt-1 text-sm font-bold text-gray-800 dark:text-white">
                                            @lang('general.wallet_address_list_meta_tip_value')
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between pt-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-6">
                                        @lang('general.wallet_address_list_upload_footer')
                                    </p>
                                    <button type="submit" id="wallet-address-import-btn"
                                            class="inline-flex justify-center items-center px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-500/20 transition-all active:scale-95 text-sm whitespace-nowrap">
                                        <span id="wallet-address-import-btn-text" class="inline-flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                            </svg>
                                            @lang('general.upload_and_process')
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- List --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <h3 class="font-bold text-gray-800 dark:text-white">@lang('general.list')</h3>
                    <form method="get" class="flex items-center gap-2">
                        <input type="text" name="q" value="{{ request('q') }}"
                               placeholder="@lang('general.search')"
                               class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-600 dark:bg-gray-900 text-sm outline-none focus:ring-2 focus:ring-primary-500"/>
                        <button type="submit"
                                class="px-3 py-2 rounded-xl bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm font-semibold text-gray-700 dark:text-gray-200">
                            @lang('general.search')
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/20">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-start">@lang('general.wallet_address')</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-start">@lang('general.description')</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-start">@lang('general.created_at')</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-end">@lang('general.operation')</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($items as $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors group">
                                <td class="px-6 py-4 text-start">
                                    <code class="px-2 py-1 bg-gray-100 dark:bg-gray-900 rounded text-xs text-primary-600 dark:text-primary-400 font-mono break-all">
                                        {{ $item->address }}
                                    </code>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 text-start">
                                    <span class="font-semibold text-gray-900 dark:text-gray-200">{{ $item->description ?: '—' }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-start">
                                    {{ $item->getCreatedAt() }}
                                </td>
                                <td class="px-6 py-4 text-end">
                                    <button type="button"
                                            onclick="mboxDelete('{{ route('admin.setting.wallet-address-list.destroy', $item) }}')"
                                            class="text-gray-400 hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 mb-4 opacity-20" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <p>@lang('general.not_found')</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <form id="delete-form" method="POST" style="display:none">
                    @csrf
                    @method('DELETE')
                </form>

                @if($items->hasPages())
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        function updateWalletAddressFileName() {
            const fileInput = document.getElementById('wallet-address-file-upload');
            const fileNameText = document.getElementById('wallet-address-file-name-text');
            const dropZone = document.getElementById('wallet-address-drop-zone');

            if (fileInput.files[0]) {
                fileNameText.innerText = fileInput.files[0].name;
                fileNameText.classList.add('text-primary-600');
                dropZone.classList.add('border-primary-500');
            }
        }

        function handleWalletAddressImportSubmit(form) {
            const btn = document.getElementById('wallet-address-import-btn');
            const text = document.getElementById('wallet-address-import-btn-text');
            btn.disabled = true;
            text.innerHTML = `<svg class="animate-spin h-4 w-4 mr-2 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> @lang('general.processing')...`;
        }
    </script>
@endsection
