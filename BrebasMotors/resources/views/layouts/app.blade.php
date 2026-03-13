<!DOCTYPE html>
<html lang="pt-PT">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <base href="{{ url('/') }}/">
        <title>Br3basMotors</title>
        <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="resources/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="resources/css/font-awesome.css">
        <link rel="stylesheet" href="resources/css/style.css">
        <link rel="stylesheet" href="resources/css/app.css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="shortcut icon" href="resources/images/logo.png" type="image/x-icon">
        @if (Request::is('login') || Request::is('register') || Request::is('password/*'))
            <link rel="stylesheet" href="resources/css/login-style.css">
        @endif
    </head>



    <body>  
        <div id="js-preloader" class="js-preloader">
            <div class="preloader-inner">
                <span class="dot"></span>
                <div class="dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>

        <header class="header-area header-sticky">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="main-nav">
                            <a href="/" class="logo">
                                <img src="resources/images/logo.png" alt="BR3BAS Motors Logo" style="height:150px;">
                            </a>
                            <ul class="nav">
                                <li><a href="/" class="active">Início</a></li>
                                <li><a href="/cars">Carros</a></li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-haspopup="true" aria-expanded="false" href="#">Sobre</a>

                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/team">A Nossa Equipa</a>
                                        <a class="dropdown-item" href="/faq">FAQ</a>
                                    </div>
                                </li>
                                <li><a href="/contact">Contacto</a></li>
                                
                                @guest
                                    <li class="login-item auth-switch-item">
                                        <div class="auth-switch">
                                            <a href="{{ route('register') }}">Criar conta</a>
                                            <span class="auth-divider" aria-hidden="true">|</span>
                                            <a href="{{ route('login') }}">Iniciar sessão</a>
                                        </div>
                                    </li>
                                @else
                                    <li class="dropdown profile-menu-item">
                                        @php
                                            $headerProfilePhoto = Auth::user()->profile_photo_path
                                                ? asset(Auth::user()->profile_photo_path)
                                                : asset('resources/images/loginPerson.png');
                                        @endphp
                                        <div class="profile-menu-actions">
                                            <a class="dropdown-toggle profile-menu-toggle" data-toggle="dropdown" role="button"
                                                aria-haspopup="true" aria-expanded="false" href="#" title="Conta">
                                                <img src="{{ $headerProfilePhoto }}" alt="Foto de perfil" class="profile-avatar"
                                                width="36" height="36" style="width:36px; height:36px; min-width:36px; max-width:36px; object-fit:cover; border-radius:50%;">
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('account.settings') }}">Definições</a>
                                                <a class="dropdown-item" href="{{ route('account.favorites') }}">Favoritos</a>
                                            </div>
                                            <form action="{{ route('logout') }}" method="POST" class="profile-header-logout-form">
                                                @csrf
                                                <button type="submit" class="profile-header-logout-button">Sair</button>
                                            </form>
                                        </div>
                                    </li>
                                @endguest
                            </ul>
                            <a class='menu-trigger'>
                                <span>Menu</span>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </header>


        <main>
            @yield('content')
        </main>


        @if (Request::is('login') || Request::is('register') || Request::is('password/*'))
            <footer style="display:none;"></footer>
            <script src="resources/js/login.js"></script>
        @else
            <footer>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <p>Copyright © 2026 Br3basMotors. Todos os direitos reservados.<br></p>
                        </div>
                    </div>
                </div>
            </footer>
        @endif

        
        <script src="resources/js/jquery-2.1.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="resources/js/popper.js"></script>
        <script src="resources/js/bootstrap.min.js"></script>
        <script src="resources/js/scrollreveal.min.js"></script>
        <script src="resources/js/waypoints.min.js"></script>
        <script src="resources/js/jquery.counterup.min.js"></script>
        <script src="resources/js/imgfix.min.js"></script>
        <script src="resources/js/mixitup.js"></script>
        <script src="resources/js/accordions.js"></script>
        <script src="resources/js/custom.js"></script>
    </body>
</html>
