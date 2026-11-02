@extends('admin.layout.master')

@section('title', 'Admin/Setting')

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">@lang('master.setting')</h2>
            </div>
        </div>
        <!-- Summary of cards -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.setting.site.save') }}" method="POST" enctype="multipart/form-data"
                          class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">


{{--                             Description --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.challenge_rules')
                                </label>
                                <textarea name="{{ config('constants.meta.user_challenge_rule') }}"
                                          rows="7"
                                          class="text-editor w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                          placeholder="@lang('general.meta.description')">{{ old(config('constants.meta.user_challenge_rule'), optional($options)[config('constants.meta.user_challenge_rule')]) }}</textarea>
                                @error(config('constants.meta.user_challenge_rule'))
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        {{-- Submit --}}
                        @can('setting_update')
                            <div class="pt-4">
                                <button type="submit"
                                        class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                    @lang('general.save')
                                </button>
                            </div>
                        @endcan
                    </form>


                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="{{ asset('general/js/tinymce/tinymce.min.js') }}"></script>
    <script>
        const lang = @js(app()->getLocale());
        tinymce.init({
            selector: 'textarea.text-editor',
            plugins: 'advlist autolink link lists preview table code pagebreak textcolor',
            menubar: false,
            language: lang,
            height: 400,
            relative_urls: false,
            toolbar: 'undo redo | removeformat | fontsizeselect forecolor backcolor  | alignleft aligncenter alignright alignjustify ',
        });

    </script>
@endsection
