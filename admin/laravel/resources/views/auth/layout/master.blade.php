<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ getCurrentLang('direction') }}">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="description" content="{{ optional($metaData)[config('constants.meta.description')] ?? '' }}">
    <meta name="keywords" content="{{ optional($metaData)[config('constants.meta.keywords')] ?? '' }}">
    <meta name="robots" content="index, follow">
    <meta name="author" content="{{ optional($metaData)[config('constants.meta.author')] ?? '' }}">
    <meta name="copyright" content="{{ optional($metaData)[config('constants.meta.copyright')] ?? '' }}">
    <link rel="icon" type="image/png" href="{{ optional($metaData)[config('constants.meta.favicon')] ??  '' }}" sizes="96x96" />
    <link rel="canonical" href="{{route('login')}}">
    @vite('resources/src/css/app.css')
    @stack('styles')
</head>
<body class="bg-light dark:bg-background-dark text-text-primary-light dark:text-text-primary-dark transition-colors duration-200">
@yield('contents')
<script src="{{asset('assets/js/dependencies/app.js')}}"></script>
<script src="{{asset('general/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{ asset('general/js/sweetalert2@11.min.js') }}"></script>
<script src="{{asset('assets/js/dependencies/TimerButton.js')}}"></script>
<script>
    function togglePassword(id) {
        const passwordInput = document.getElementById(id);
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
    }

    function mboxSuccess(caption) {
        Swal.fire({
            title: "{{trans('general.message')}}",
            text: caption,
            icon: 'success',
            confirmButtonColor: '#63d0df',
            cancelButtonColor: '#f7858d',
            confirmButtonText: "{{trans('general.ok')}}",
        })
    }

    function mboxError(caption) {
        Swal.fire({
            title: "{{trans('general.warning')}}",
            text: caption,
            icon: 'error',
            confirmButtonColor: '#63d0df',
            cancelButtonColor: '#f7858d',
            confirmButtonText: "{{trans('general.ok')}}",
        })
    }

    function toastAlert(message, icon = 'success', position = 'top-end') {
        const Toast = Swal.mixin({
            toast: true,
            showConfirmButton: false,
            timer: 5000,
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

    @if (!is_null(session('toast-success')))
    toastAlert("{{ session('toast-success') }}", 'success');
    @elseif (!is_null(session('toast-error')))
    toastAlert("{{ session('toast-error') }}", 'error');
    @endif
</script>
@yield('scripts')
@stack('scripts')
</body>
</html>
