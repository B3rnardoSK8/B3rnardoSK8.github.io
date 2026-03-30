<nav class="sidebar sidebar-offcanvas" id="sidebar">
    @php
        $profileImage = Auth::user()?->profile_photo_path ?: 'resources/images/car.png';
    @endphp

    <ul class="nav">
        <li class="nav-item nav-profile border-bottom">
            <a href="#" class="nav-link flex-column">
                <div class="nav-profile-image">
                    <img src="{{ asset($profileImage) }}" alt="profile">
                </div>
                <div class="nav-profile-text d-flex ms-0 mb-3 flex-column">
                    <span class="fw-semibold mb-1 mt-2 text-center">{{ Auth::user()->name ?? 'Administrador' }}</span>
                </div>
            </a>
        </li>

        <li class="pt-2 pb-1"><span class="nav-item-head">Dashboard</span></li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('back.dashboard') }}">
                <i class="mdi mdi-compass-outline menu-icon"></i>
                <span class="menu-title">Visão geral</span>
            </a>
        </li>

        <li class="pt-2 pb-1"><span class="nav-item-head">Gestão de Veículos</span></li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('back.cars.index') }}">
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                <span class="menu-title">Veículos</span>
            </a>
        </li>
        @if ((int) (Auth::id() ?? 0) === 1)
            <li class="pt-2 pb-1"><span class="nav-item-head">Gestão de Utilizadores</span></li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('back.users.index') }}">
                    <i class="mdi mdi-account-multiple menu-icon"></i>
                    <span class="menu-title">Utilizadores</span>
                </a>
            </li>
        @endif
    </ul>
</nav>