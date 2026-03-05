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
                                <img src="{{ asset('resources/images/logo.png') }}" alt="BR3BAS Motors Logo" style="height: 150px;">
                            </a>
                            <ul class="nav">
                                <li><a href="/" class="active">Início</a></li>
                                <li><a href="/cars">Carros</a></li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-haspopup="true" aria-expanded="false" href="">Sobre</a>

                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/team">A Nossa Equipa</a>
                                        <a class="dropdown-item" href="/faq">FAQ</a>
                                    </div>
                                </li>
                                <li><a href="/contact">Contacto</a></li>
                            </ul>
                            <ul class="nav" style="display:inline-block; margin-left:10px;">
                                @guest
                                    <li class="login-item"><a href="{{ route('login') }}"><img src="{{ asset('resources/images/loginPerson.png') }}" alt="Login" style="height:36px;"></a></li>
                                @else
                                    <li class="login-item" style="display:flex; align-items:center; gap:8px;">
                                        <span style="color:#fff;">Olá, {{ Auth::user()->name }}</span>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:inline; margin:0;">
                                            @csrf
                                            <button type="submit" class="btn btn-link" style="color:#fff; padding:0;">Sair</button>
                                        </form>
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