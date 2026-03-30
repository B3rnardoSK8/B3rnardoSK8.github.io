<div class="row mb-4">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Veículos ativos</h4>
                <h2 class="mb-0">{{ $carsCount }}</h2>
                <p class="text-muted mb-3">no catálogo</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('back.cars.index') }}" class="btn btn-outline-primary btn-sm">Listar Veículos</a>
                    <a href="{{ route('back.cars.create') }}" class="btn btn-primary btn-sm">Adicionar Veículo</a>
                </div>
            </div>
        </div>
    </div>
    @if ((int) (Auth::id() ?? 0) === 1)
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Utilizadores</h4>
                <h2 class="mb-0">{{ $usersCount }}</h2>
                <p class="text-muted mb-3">Contas registadas</p>
                <a href="{{ route('back.users.index') }}" class="btn btn-outline-primary btn-sm">Gerir Utilizadores</a>
            </div>
        </div>
    </div>
    @endif
</div>