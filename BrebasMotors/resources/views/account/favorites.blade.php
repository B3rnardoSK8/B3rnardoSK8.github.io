@extends('layouts.app')

@section('content')

    @php
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


<section class="section section-bg" id="call-to-action" style="background-image: url('resources/images/banner-image1.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="cta-content">
                        <br>
                        <br>
                        <h2>Os seus <em>Favoritos</em></h2>
                        <p>Aqui aparecem os carros que guardou como favorito!</p>
                        <br>
                    </div>
                </div>
            </div>
        </div>
</section>

<section class="section" id="trainers">
    <div class="container">
        <br>

        <div class="row cars-grid">
            @forelse ($cars as $car)
                <div class="col-lg-4">
                    <div class="car-card-wrapper">
                        <form action="{{ route('cars.favorite.toggle', $car) }}" method="POST" class="favorite-toggle-form">
                            @csrf
                            <button
                                type="submit"
                                class="car-favorite-button is-favorite"
                                title="Remover dos favoritos"
                                aria-label="Remover dos favoritos"
                            >
                                <i class="fa fa-heart" aria-hidden="true"></i>
                            </button>
                        </form>

                        <a href="{{ route('cars.show', $car) }}" class="trainer-item d-block" style="text-decoration: none; color: inherit;">
                           <div class="image-thumb car-image-thumb">
                                    <img src="{{ asset($resolveImagePath($car->image_path)) }}" alt="{{ $car->title }}">
                                    @if($car->is_sold)
                                        <div class="car-sold-ribbon">
                                            <span>Vendido</span>
                                        </div>
                                    @endif
                                </div>
                            <div class="down-content">
                                <span>
                                    <sup>€</sup>{{ number_format($car->price, 0, ',', ' ') }}
                                </span>

                                <h4>{{ $car->title }}</h4>

                                <p>
                                    <i class="fa fa-dashboard"></i> {{ number_format($car->mileage, 0, ',', ' ') }} km &nbsp;&nbsp;&nbsp;
                                    @if($car->power)
                                        <i class="fa fa-cube"></i> {{ $car->power }} cv &nbsp;&nbsp;&nbsp;
                                    @endif
                                    @if($car->transmission)
                                        <i class="fa fa-cog"></i> {{ $car->transmission }}
                                    @endif
                                </p>

                                <ul class="social-icons">
                                    <li><span class="badge bg-secondary" style="color: #fff;">{{ $car->is_new ? 'Novo' : 'Usado' }}</span></li>
                                    <li><span class="badge bg-light text-dark">{{ $car->fuel }}</span></li>
                                    <li><span class="badge bg-light text-dark">{{ $car->segment }}</span></li>
                                </ul>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">Ainda não tem carros favoritos.</div>
                </div>
            @endforelse
        </div>

        <br>

        @if ($cars->hasPages())
            <div class="d-flex justify-content-center">
                {{ $cars->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</section>

<script>
    (function() {
        const favoriteForms = document.querySelectorAll('.favorite-toggle-form');

        favoriteForms.forEach((form) => {
            form.addEventListener('submit', async function (event) {
                event.preventDefault();

                const button = form.querySelector('.car-favorite-button');
                const icon = button ? button.querySelector('i') : null;
                if (!button || !icon || button.hasAttribute('data-loading')) {
                    return;
                }

                button.setAttribute('data-loading', '1');

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData(form)
                    });

                    if (!response.ok) {
                        throw new Error('Falha no pedido de favoritos.');
                    }

                    const payload = await response.json();
                    const isFavorite = !!payload.is_favorite;

                    button.classList.toggle('is-favorite', isFavorite);
                    icon.classList.toggle('fa-heart', isFavorite);
                    icon.classList.toggle('fa-heart-o', !isFavorite);

                    button.classList.remove('favorite-pop');
                    void button.offsetWidth;
                    button.classList.add('favorite-pop');

                    const label = isFavorite ? 'Remover dos favoritos' : 'Adicionar aos favoritos';
                    button.setAttribute('title', label);
                    button.setAttribute('aria-label', label);
                } catch (error) {
                    console.error(error);
                } finally {
                    button.removeAttribute('data-loading');
                    button.blur();
                }
            });
        });
    })();
</script>


@endsection
