<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ getCurrentLang('direction') }}">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('admin.partial.theme')
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="description" content="{{ optional($metaData)[config('constants.meta.description')] ?? '' }}">
    <meta name="keywords" content="{{ optional($metaData)[config('constants.meta.keywords')] ?? '' }}">
    <meta name="robots" content="index, follow">
    <meta name="author" content="{{ optional($metaData)[config('constants.meta.author')] ?? '' }}">
    <meta name="copyright" content="{{ optional($metaData)[config('constants.meta.copyright')] ?? '' }}">
    <link rel="icon" type="image/png" href="{{ optional($metaData)[config('constants.meta.favicon')] ?? ''  }}" sizes="96x96"/>
    <link rel="canonical" href="{{route('login')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugin/swiper/swiper-bundle.min.css')}}">
    @vite('resources/src/css/app.css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/font-awesome/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/plugin/select2/select2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/plugin/date-picker/datePicker.css') }}">
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
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark transition-colors duration-200">

<div class="flex h-screen overflow-hidden max-w-[1920px] mx-auto">
    <!-- sidebar -->
    @include('admin.partial.sidebar')
    <!-- main content -->
    <div class="flex flex-col flex-1 overflow-hidden">
        <!-- header -->
        @include('admin.partial.header')
        <!-- page content -->
        @yield('contents')
    </div>
</div>
<!-- /end page divider -->

<!-- start canvas responsive menu -->
@include('admin.partial.responsive-menu')
<!-- Overlay -->
<div class="overlay transition fixed inset-0 z-40 bg-black/70 hidden" onclick="closeOffcanvas()" role="presentation" aria-hidden="true">

</div>
@stack('modals')
@yield('modal')
<!-- /canvas responsive menu -->
<script src="{{asset('assets/js/dependencies/app.js')}}"></script>
<script src="{{asset('general/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{ asset('general/js/sweetalert2@11.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/select2/select2.js') }}"></script>
<script src="{{ asset('assets/js/plugin/date-picker/datePicker.js') }}"></script>
@vite('resources/src/js/app.js')

<script type="application/javascript">
    jalaliDatepicker.startWatch();

    function mboxDelete(url) {
        Swal.fire({
            title: "{{ trans('general.confirm_delete_title') }}",
            text: "{{ trans('general.confirm_delete_text') }}",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: "{{ trans('general.cancel_button') }}",
            confirmButtonColor: '#63d0df',
            cancelButtonColor: '#f7858d',
            confirmButtonText: "{{ trans('general.confirm_delete_button') }}",
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-form');
                form.action = url;
                form.submit();
            }
        });
    }

    function closeTicket(url) {
        Swal.fire({
            title: "{{ __('general.confirm_close_ticket') }}",
            text: "{{ __('general.confirm_close_ticket_text') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "{{ __('general.yes_close') }}",
            cancelButtonText: "{{ __('general.cancel') }}",
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }


    function mboxSuccess(caption = null) {
        Swal.fire({
            title: "{{trans('general.message')}}",
            text: caption ?? "{{trans('general.operation_done')}}",
            icon: 'success',
            confirmButtonColor: '#63d0df',
            cancelButtonColor: '#f7858d',
            confirmButtonText: "{{trans('general.ok')}}",
        })
    }

    function mboxError(caption = null) {
        Swal.fire({
            title: "{{trans('general.warning')}}",
            text: caption ?? "{{trans('general.operation_failed')}}",
            icon: 'error',
            confirmButtonColor: '#63d0df',
            cancelButtonColor: '#f7858d',
            confirmButtonText: "{{trans('general.ok')}}",
        })
    }

    function mboxQuestion(url) {
        Swal.fire({
            title: "{{trans('general.confirm_delete_title')}}",
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: "{{trans('general.cancel_button')}}",
            confirmButtonColor: '#63d0df',
            cancelButtonColor: '#f7858d',
            confirmButtonText: "{{trans('general.yes')}}",
        }).then((result) => {
            if (result.value) {
                window.location.href = url;
            }
        })
    }


    function confirmAction(callback = null) {
        return Swal.fire({
            title: "{{ trans('general.confirm_delete_title') }}",
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: "{{ trans('general.cancel_button') }}",
            confirmButtonColor: '#63d0df',
            cancelButtonColor: '#f7858d',
            confirmButtonText: "{{ trans('general.yes') }}",
        }).then((result) => {
            if (result.isConfirmed && typeof callback === 'function') {
                callback();
            }
            return result;
        });
    }


    function mboxHtml(html, title = "{{trans('general.notice')}}") {
        Swal.fire({
            title: title,
            html: html,
            icon: 'info',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "{{trans('general.close')}}",
        })
    }

    function mboxAlert(caption, icon = 'success') {
        Swal.fire({
            position: 'top',
            icon: icon,
            title: caption,
            showConfirmButton: false,
            timer: 1500
        })
    }

    function mboxNotice(description, icon = 'info', title = null, text = null) {
        Swal.fire({
            title: title || "{{trans('general.operation_done_successfully')}}",
            html: `
            ${text ? `<div style="margin-bottom: 10px; font-size: 14px">${text}</div>` : ''}
            <div style="padding: 10px; background: #f0f9ff; border: 1px dashed #63d0df; border-radius: 5px; font-family: monospace; word-break: break-all;">
                ${description}
            </div>
        `,
            icon: icon, // success | error | warning | info | question
            showCloseButton: true,
            confirmButtonColor: '#63d0df',
            confirmButtonText: "{{ trans('general.ok') }}",
            width: 450,
        });
    }


    function mboxSuccessToast(message) {
        Swal.fire({
            icon: 'success',
            toast: true,
            title: message,
            position: 'top',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('I was closed by the timer')
            }
        })
    }

    function mboxErrorToast(message) {
        Swal.fire({
            icon: 'error',
            toast: true,
            html: message,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        })
    }

    function toastAlert(message, icon = 'success', position = 'top-end') {
        const Toast = Swal.mixin({
            toast: true,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            showCloseButton: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: icon,
            title: message,
            position: position
        })
    }

    function separator() {
        document.querySelectorAll('.separator').forEach(input => {
            input.addEventListener('input', function () {
                const allowNegative = this.classList.contains('separator-signed');
                const isNegative = allowNegative && this.value.trim().startsWith('-');

                let raw = this.value.replace(/[^0-9.]/g, '');

                const dotIndex = raw.indexOf('.');
                if (dotIndex !== -1) {
                    raw = raw.substring(0, dotIndex + 1) + raw.substring(dotIndex + 1).replace(/\./g, '');
                }

                if (isNegative && raw === '') {this.value = '-';return;}

                const [integerPart, decimalPart] = raw.split('.');
                const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                let formatted = decimalPart !== undefined ? `${formattedInteger}.${decimalPart}` : formattedInteger;

                if (isNegative && formatted !== '') {
                    formatted = `-${formatted}`;
                }

                this.value = formatted;
                this.setSelectionRange(this.value.length, this.value.length);
            });
        });
    }

    separator();


    function showFullImage(src) {
        const modal = document.createElement('div');
        modal.classList.add(
            'fixed', 'inset-0', 'bg-black', 'bg-opacity-75',
            'flex', 'items-center', 'justify-center', 'z-50'
        );

        modal.innerHTML = `
                <div class="relative">
                    <img src="${src}" class="max-w-full max-h-screen rounded-lg shadow-lg">
                    <button onclick="this.parentElement.parentElement.remove()"
                            class="absolute top-2 right-2 bg-white text-black rounded-full px-3 py-1 text-sm font-bold">
                        ×
                    </button>
                </div>
            `;

        document.body.appendChild(modal);
    }


    $('.select2').select2({
        placeholder: "{{ __('general.choose_option') }}",
        allowClear: true,
        width: '100%'
    });


    @if (!is_null(session('toast-success')))
    toastAlert("{{ session('toast-success') }}", 'success');
    @elseif (!is_null(session('toast-error')))
    toastAlert("{{ session('toast-error') }}", 'error');
    @elseif(!is_null(session('success-alert-title')))
    mboxNotice("{{session('plain_token')}}", "info", "{{session('success-alert-title')}}", "<p s>{{session('success-alert-description')}}</p>")

    @endif
</script>
@yield('hidden_template')
@yield('scripts')
@stack('scripts')
</body>
</html>

