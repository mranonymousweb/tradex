@extends('admin.layout.master')

@section('title', trans('general.license'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">

        {{-- HEADER --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-1 text-xs text-gray-400">
                        <li>@lang('general.settings')</li>
                        <li><svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/></svg></li>
                        <li class="text-primary-600 font-bold">@lang('general.license')</li>
                    </ol>
                </nav>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">@lang('general.license')</h2>
                    @if($hasValidLicense)
                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700 border border-green-200 uppercase">@lang('general.active')</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200 uppercase">@lang('general.inactive')</span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">@lang('general.license_description')</p>
            </div>

            <a href="{{route('admin.setting.index')}}"
               class="inline-flex items-center px-3 py-2 text-sm font-bold rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                @lang('master.back')
            </a>
        </div>

        {{-- MAIN CARD --}}
        <div class="max-w-2xl bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6">

                @if(session('message'))
                    <div class="mb-5 p-3 rounded-lg flex items-center border {{ session('status') === 'success' ? 'bg-green-50 border-green-100 text-green-800' : 'bg-red-50 border-red-100 text-red-800' }}">
                        <p class="font-bold text-sm">{{ session('message') }}</p>
                    </div>
                @endif

                <form action="{{ route('admin.setting.license.update') }}" method="post" enctype="multipart/form-data" onsubmit="handleFormSubmit(this)">
                    @csrf

                    <div class="group relative p-8 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl text-center bg-gray-50/30 dark:bg-gray-900/10 hover:border-primary-500 transition-all" id="drop-zone">
                        <input required type="file" name="file" id="file-upload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="updateFileName()">

                        <div class="mb-3">
                            <div class="w-12 h-12 mx-auto bg-white dark:bg-gray-700 rounded-lg shadow-sm flex items-center justify-center border border-gray-100 dark:border-gray-600">
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                        </div>

                        <p id="file-name-text" class="text-sm font-bold text-gray-700 dark:text-gray-200">@lang('general.select_csv_xlsx')</p>
                        <p id="file-size-info" class="text-xs text-gray-400 uppercase font-semibold">@lang('general.drop_file_here')</p>
                    </div>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <button type="submit" id="btn-submit" class="flex items-center justify-center px-4 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm font-bold shadow-md transition-all">
                            <span id="submit-text" class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                @lang('general.upload_and_process')
                            </span>
                        </button>

                        <a href="{{ route('admin.setting.license.test') }}"
                           class="flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-bold border {{ $hasValidLicense ? 'text-green-600 border-green-200 bg-green-50 hover:bg-green-100' : 'text-primary-600 border-primary-200 bg-primary-50 hover:bg-primary-100' }}">
                            @lang('general.test_license')
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        function handleFormSubmit(form) {
            const btn = document.getElementById('btn-submit');
            const text = document.getElementById('submit-text');
            btn.disabled = true;
            text.innerHTML = `<svg class="animate-spin h-4 w-4 mr-2 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> @lang('general.processing')...`;
        }

        function updateFileName() {
            const fileInput = document.getElementById('file-upload');
            const fileNameText = document.getElementById('file-name-text');
            if (fileInput.files[0]) {
                fileNameText.innerText = fileInput.files[0].name;
                fileNameText.classList.add('text-primary-600');
            }
        }
    </script>
@endsection
