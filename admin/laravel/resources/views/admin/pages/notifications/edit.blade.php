@extends('admin.layout.master')


@section('title', trans('general.pop_up_notification_edit'))


@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
@endpush

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    @lang('general.pop_up_notification_edit')
                </h2>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{route('admin.notification.index',['section'=>config('constants.option.section_pop_up')])}}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 shadow-sm dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <!-- Currency form -->
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <!-- Left column - Admin info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Basic info card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl h-full shadow-md dark:shadow-gray-700 p-6 border border-gray-200 dark:border-gray-700">
                    <form action="{{ route('admin.notification.update',$notification->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="section" value="{{config('constants.option.section_pop_up')}}">
                        <div class="grid grid-cols-3 gap-6">
                            <div class=" md:col-span-1 col-span-3">
                                <div class=" md:col-span-1 col-span-3">

                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">@lang('general.user')
                                        <span class="text-red-500">*</span></label>
                                    <select disabled name="user_id[]"

                                            required
                                            oninvalid="this.setCustomValidity('@lang('general.user_required')')"
                                            oninput="setCustomValidity('')"
                                            class="select2 w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option
                                            @selected(in_array(\App\Models\Notification::SEND_ALL,old('user_id') ?? [])) value="{{  \App\Models\Notification::SEND_ALL  }}">{{ trans('general.send_all') }}</option>
                                        @foreach($packages as $package)
                                            <option
                                                @selected(in_array($package->id,old('user_id') ?? [])) value="{{ 'package_'.$package->id }}">@lang('general.package')
                                                : {{ $package->name}}
                                            </option>
                                        @endforeach
                                        @foreach($users as $key=>$value)
                                            <option
                                                @selected(in_array($key,old('user_id',[$notification?->user_id]) ?? []))
                                                value="{{ $key }}">{{ $value}}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                    <span class="text-red-500">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class=" md:col-span-1 col-span-3">
                                <label
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 ">@lang('general.title')
                                    <span class="text-red-500">*</span></label>
                                <input type="text" name="title" value="{{old('title',$notification->getTranslation('title', $lang))}}"
                                       required
                                       oninvalid="this.setCustomValidity('@lang('general.title_required')')"
                                       oninput="setCustomValidity('')"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="@lang('general.title')">
                                @error('title')
                                <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="md:col-span-3 col-span-3 !h-fit">
                                <label class=" text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    @lang('general.description')
                                    <span class="text-red-500">*</span>
                                </label>

                                <!-- Quill editor -->
                                <div id="editor"
                                     class="bg-white dark:bg-gray-700 text-gray-800 dark:text-white h-40">
                                    {!! old('description', $notification->getTranslation('description', $lang)) !!}
                                </div>


                                <!-- hidden input for form submit -->
                                <input type="hidden" name="description" id="description">

                                @error('description')
                                <span class="text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mt-8">
                                <button type="submit"
                                        class="w-32 flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg bg-primary-600 text-white hover:bg-primary-700 shadow-sm dark:bg-primary-500 dark:hover:bg-primary-600">
                                    @lang('general.save')
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    // فونت و سایز
                    [{'font': []}, {'size': ['small', false, 'large', 'huge']}],

                    // heading
                    [{'header': [1, 2, 3, 4, 5, 6, false]}],

                    // استایل متن
                    ['bold', 'italic', 'underline', 'strike'],

                    // رنگ
                    [{'color': []}, {'background': []}],

                    // اسکریپت
                    [{'script': 'sub'}, {'script': 'super'}],

                    // لیست و تورفتگی
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                    [{'indent': '-1'}, {'indent': '+1'}],

                    // چینش
                    [{'align': []}],

                    // رسانه
                    ['image', 'link'],

                    // پاک‌کن
                    ['clean']
                ]
            }
        });

        // ارسال HTML + Base64 image
        document.querySelector('form').addEventListener('submit', function () {
            document.querySelector('#description').value = quill.root.innerHTML;
        });
    </script>


    {{--    <script>--}}
    {{--        const toolbarOptions = [--}}
    {{--            ['bold', 'italic', 'underline'],--}}
    {{--            ['image', 'link'],--}}
    {{--            [{'list': 'ordered'}, {'list': 'bullet'}],--}}
    {{--            [{'align': []}],--}}
    {{--            ['clean']--}}
    {{--        ];--}}

    {{--        const quill = new Quill('#editor', {--}}
    {{--            theme: 'snow',--}}
    {{--            modules: {--}}
    {{--                toolbar: {--}}
    {{--                    container: toolbarOptions,--}}
    {{--                    handlers: {--}}
    {{--                        image: imageHandler--}}
    {{--                    }--}}
    {{--                }--}}
    {{--            }--}}
    {{--        });--}}

    {{--        function imageHandler() {--}}
    {{--            const input = document.createElement('input');--}}
    {{--            input.setAttribute('type', 'file');--}}
    {{--            input.setAttribute('accept', 'image/*');--}}
    {{--            input.click();--}}

    {{--            input.onchange = async () => {--}}
    {{--                const file = input.files[0];--}}
    {{--                if (!file) return;--}}

    {{--                const formData = new FormData();--}}
    {{--                formData.append('image', file);--}}
    {{--                formData.append('_token', '{{ csrf_token() }}');--}}

    {{--                const response = await fetch('{{ route("admin.upload.image") }}', {--}}
    {{--                    method: 'POST',--}}
    {{--                    body: formData--}}
    {{--                });--}}

    {{--                const data = await response.json();--}}

    {{--                const range = quill.getSelection();--}}
    {{--                quill.insertEmbed(range.index, 'image', data.url);--}}
    {{--            };--}}
    {{--        }--}}

    {{--        document.querySelector('form').addEventListener('submit', function () {--}}
    {{--            document.querySelector('#description').value = quill.root.innerHTML;--}}
    {{--        });--}}
    {{--    </script>--}}

@endsection


