<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-chevron-double-left"></span>
        </button>
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <span class="fw-bold text-white d-inline-flex align-items-center gap-2">
                <img src="{{ asset('resources/images/logo.png') }}" alt="BR3BAS Motors Logo" style="height: 85px; width: auto;">
            </span>
        </div>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-logout d-none d-lg-block">
                <form method="GET" action="{{ url('/') }}" class="d-inline">
                    <button type="submit" class="nav-link border-0 bg-transparent" title="Voltar à Página Principal">
                        <i class="mdi mdi-logout"></i>
                    </button>
                </form>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>