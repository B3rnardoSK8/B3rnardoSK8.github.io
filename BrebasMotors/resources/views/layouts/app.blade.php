<!DOCTYPE html>
<html lang="pt-PT">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <base href="{{ url('/') }}/">
        <title>Br3basMotors</title>
        <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('resources/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('resources/css/font-awesome.css') }}">
        <link rel="stylesheet" href="{{ asset('resources/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('resources/css/app.css') }}">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="shortcut icon" href="{{ asset('resources/images/logo.png') }}" type="image/x-icon">
        @if(request()->is('login') || request()->is('register'))
            <link rel="stylesheet" href="{{ asset('resources/css/login-style.css') }}">
        @endif
<!--         <style>
            html, body { margin:0; padding:0; }
            /* ensure header is positioned above page content and no top white line */
            .header-area, .background-header { border-top: none !important; }
            .header-area { top: 0 !important; z-index: 1000 !important; }
        </style> -->
    </head>
    <body class="@if(request()->is('login') || request()->is('register')) auth-page @endif">
        @if(request()->is('login') || request()->is('register'))
            <video autoplay muted loop id="auth-bg-video">
                <source src="{{ asset('resources/images/video1.mp4') }}" type="video/mp4">
            </video>
            <div class="auth-bg-overlay"></div>
        @endif
        <x-header />

        <main class="py-4">
            @yield('content')
        </main>

        <x-footer />

        <script src="{{ asset('resources/js/jquery-2.1.0.min.js') }}"></script>
        <script src="{{ asset('resources/js/popper.js') }}"></script>
        <script src="{{ asset('resources/js/bootstrap.min.js') }}"></script>
        @if(request()->is('login') || request()->is('register'))
            <script src="{{ asset('resources/js/login.js') }}"></script>
        @endif
    </body>
</html>
