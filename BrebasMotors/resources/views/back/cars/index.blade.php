@extends('layouts.back-admin')

@section('title', 'Gestao de Veiculos')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-1">Lista de Veículos</h4>
                        <p class="text-muted mb-0">Gestão do catálogo de veículos</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('back.cars.create') }}" class="btn btn-primary btn-sm">Adicionar Veículo</a>
                        <a href="{{ route('back.dashboard') }}" class="btn btn-light btn-sm">Voltar</a>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Ano</th>
                                <th>Preço</th>
                                <th>Combustível</th>
                                <th>Estado</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cars as $car)
                                <tr>
                                    <td>{{ $car->id }}</td>
                                    <td>{{ $car->brand }}</td>
                                    <td>{{ $car->model }}</td>
                                    <td>{{ $car->year ?? '-' }}</td>
                                    <td>{{ number_format((float) $car->price, 0, ',', '.') }} EUR</td>
                                    <td>{{ $car->fuel ?? '-' }}</td>
                                    <td>{{ $car->is_new ? 'Novo' : 'Usado' }}</td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('back.cars.show', $car) }}" class="btn btn-outline-secondary btn-sm">Ver</a>
                                            <a href="{{ route('back.cars.edit', $car) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                                            <form method="POST" action="{{ route('back.cars.destroy', $car) }}" onsubmit="return confirm('Tem a certeza que pretende remover este veículo?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Apagar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Sem veículos registados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $cars->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
