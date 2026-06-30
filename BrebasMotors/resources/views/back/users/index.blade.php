@extends('layouts.back-admin')

@section('title', 'Gestão de Utilizadores')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-1">Gestão de utilizadores</h4>
                <p class="text-muted mb-3">Lista de contas registadas</p>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Tipo</th>
                                <th>Registado em</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ collect($tipos)->firstWhere('id', (int) $user->tipo_id)->nome ?? 'Desconhecido' }}</td>
                                    <td>{{ optional($user->created_at)->format('d/m/Y H:i') }}</td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('back.users.tipo.update', $user) }}" class="d-inline-flex align-items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <select name="tipo_id" class="form-select form-select-sm" style="min-width: 140px;" {{ (int) auth()->id() === (int) $user->id ? 'disabled' : '' }}>
                                                @foreach ($tipos as $tipo)
                                                    <option value="{{ $tipo->id }}" @selected((int) $user->tipo_id === (int) $tipo->id)>
                                                        {{ $tipo->nome }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-outline-primary btn-sm" {{ (int) auth()->id() === (int) $user->id ? 'disabled' : '' }}>Guardar cargo</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Sem utilizadores registados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
