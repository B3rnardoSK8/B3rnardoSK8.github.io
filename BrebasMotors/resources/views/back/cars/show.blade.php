@extends('layouts.back-admin')

@section('title', 'Detalhe do Veiculo')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h4 class="card-title mb-1">{{ $car->title }}</h4>
                        <p class="text-muted mb-0">Detalhes do Veículo</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('back.cars.edit', $car) }}" class="btn btn-primary btn-sm no-hover-white">Editar</a>
                        <a href="{{ route('back.cars.index') }}" class="btn btn-light btn-sm no-hover-white">Voltar</a>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @php
                    $galleryImages = is_array($car->images) ? $car->images : [];
                    if ($car->image_path && !in_array($car->image_path, $galleryImages, true)) {
                        $galleryImages[] = $car->image_path;
                    }

                    $resolveImagePath = static function (?string $image): string {
                        if (!$image) {
                            return 'resources/images/car.png';
                        }

                        if (str_starts_with($image, 'storage/') || str_starts_with($image, 'resources/')) {
                            return $image;
                        }

                        return 'storage/images/cars/'.$image;
                    };
                @endphp

                <div class="row">
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <img
                            src="{{ asset($resolveImagePath($car->image_path)) }}"
                            alt="{{ $car->title }}"
                            class="img-fluid rounded border"
                        >

                        @if (count($galleryImages) > 1)
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach ($galleryImages as $image)
                                    <img src="{{ asset($resolveImagePath($image)) }}" alt="{{ $car->title }}" class="img-thumbnail" style="height: 75px; width: auto;">
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-7">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr><th width="180">Marca</th><td>{{ $car->brand }}</td></tr>
                                    <tr><th>Modelo</th><td>{{ $car->model }}</td></tr>
                                    <tr><th>Ano</th><td>{{ $car->year ?: '-' }}</td></tr>
                                    <tr><th>Estado</th><td>{{ $car->is_new ? 'Novo' : 'Usado' }}</td></tr>
                                    <tr><th>Preço</th><td>{{ number_format((float) $car->price, 0, ',', '.') }} EUR</td></tr>
                                    <tr><th>Quilometragem</th><td>{{ number_format((int) $car->mileage, 0, ',', '.') }} km</td></tr>
                                    <tr><th>Segmento</th><td>{{ $car->segment ?: '-' }}</td></tr>
                                    <tr><th>Motor</th><td>{{ $car->engine ?: '-' }}</td></tr>
                                    <tr><th>Potência</th><td>{{ $car->power ? $car->power.' cv' : '-' }}</td></tr>
                                    <tr><th>Combustível</th><td>{{ $car->fuel ?: '-' }}</td></tr>
                                    <tr><th>Transmissão</th><td>{{ $car->transmission ?: '-' }}</td></tr>
                                    <tr><th>Portas</th><td>{{ $car->doors ?: '-' }}</td></tr>
                                    <tr><th>Lugares</th><td>{{ $car->seats ?: '-' }}</td></tr>
                                </tbody>
                            </table>
                        </div>

                        @if ($car->description)
                            <hr>
                            <h6>Descrição</h6>
                            <p class="mb-0">{{ $car->description }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
