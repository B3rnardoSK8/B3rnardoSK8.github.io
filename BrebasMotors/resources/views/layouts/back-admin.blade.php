<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Br3basMotors Admin')</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('plusadmin/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plusadmin/assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('plusadmin/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('plusadmin/assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plusadmin/assets/vendors/jquery-bar-rating/css-stars.css') }}">
    <link rel="stylesheet" href="{{ asset('plusadmin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('resources/css/backoffice-theme.css') }}?v={{ @filemtime(public_path('resources/css/backoffice-theme.css')) }}">

    <link rel="shortcut icon" href="resources/images/logo.png" type="image/x-icon">
    @stack('styles')
</head>
<body class="backoffice-theme">
    <div class="container-scroller">
        @include('back.partials.sidebar')

        <div class="container-fluid page-body-wrapper">
            @include('back.partials.navbar')

            <div class="main-panel">
                <div class="content-wrapper pb-0">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('plusadmin/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('plusadmin/assets/vendors/jquery-bar-rating/jquery.barrating.min.js') }}"></script>
    <script src="{{ asset('plusadmin/assets/vendors/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('plusadmin/assets/vendors/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('plusadmin/assets/vendors/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('plusadmin/assets/vendors/flot/jquery.flot.categories.js') }}"></script>
    <script src="{{ asset('plusadmin/assets/vendors/flot/jquery.flot.fillbetween.js') }}"></script>
    <script src="{{ asset('plusadmin/assets/vendors/flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('plusadmin/assets/js/jquery.cookie.js') }}" type="text/javascript"></script>

    <script src="{{ asset('plusadmin/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('plusadmin/assets/js/misc.js') }}"></script>
    <script src="{{ asset('plusadmin/assets/js/settings.js') }}"></script>
    <script src="{{ asset('plusadmin/assets/js/todolist.js') }}"></script>
    <script src="{{ asset('plusadmin/assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('plusadmin/assets/js/proBanner.js') }}"></script>
    <script src="{{ asset('plusadmin/assets/js/dashboard.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.body;
            const toggles = document.querySelectorAll('[data-toggle="minimize"]');

            toggles.forEach(function (toggle) {
                toggle.addEventListener('click', function () {
                    if (body.classList.contains('sidebar-toggle-display') || body.classList.contains('sidebar-absolute')) {
                        body.classList.toggle('sidebar-hidden');
                    } else {
                        body.classList.toggle('sidebar-icon-only');
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>