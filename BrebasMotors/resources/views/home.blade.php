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

    <div class="main-banner" id="top">
        <video autoplay muted loop id="bg-video">
            <source src="resources/images/video1.mp4" type="video/mp4" />
        </video>

        <div class="video-overlay header-text">
            <div class="caption">
                <h2>onde os sonhos se<em> tornam realidade</em></h2>
            </div>
        </div>
    </div>



    <section class="section" id="trainers">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading">
                        <h2>As nossas máquinas em <em>destaque</em></h2>
                        <img src="resources/images/line-dec.png" alt="Line Decoration">
                    </div>
                </div>
            </div>
            <div class="row cars-grid">
                @forelse($featuredCars ?? [] as $car)
                    <div class="col-lg-4">
                        <div class="car-card-wrapper">
                            @auth
                                @php
                                    $isFavorite = in_array($car->id, $favoriteCarIds ?? [], true);
                                @endphp
                                <form action="{{ route('cars.favorite.toggle', $car) }}" method="POST" class="favorite-toggle-form">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="car-favorite-button {{ $isFavorite ? 'is-favorite' : '' }}"
                                        title="{{ $isFavorite ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}"
                                        aria-label="{{ $isFavorite ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}"
                                    >
                                        <i class="fa {{ $isFavorite ? 'fa-heart' : 'fa-heart-o' }}" aria-hidden="true"></i>
                                    </button>
                                </form>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="car-favorite-button car-favorite-login"
                                    title="Inicie sessão para guardar favoritos"
                                    aria-label="Inicie sessão para guardar favoritos"
                                >
                                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                                </a>
                            @endauth

                            <a href="{{ route('cars.show', $car) }}" class="trainer-item d-block" style="text-decoration: none; color: inherit;">
                                <div class="image-thumb" style="position: relative;">
                                    <img src="{{ asset($resolveImagePath($car->image_path)) }}" alt="{{ $car->title }}">
                                    @if($car->is_sold)
                                        <div style="position: absolute; top: -15px; right: -50px; width: 200px; height: 60px; background: #ff7300d9; transform: rotate(45deg); display: flex; align-items: center; justify-content: center; z-index: 10; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);">
                                            <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; font-size: 16px; font-weight: bold; text-transform: uppercase; text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);">Vendido</span>
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
                                        @if($car->fuel)
                                            <li><span class="badge bg-light text-dark">{{ $car->fuel }}</span></li>
                                        @endif
                                        @if($car->segment)
                                            <li><span class="badge bg-light text-dark">{{ $car->segment }}</span></li>
                                        @endif
                                    </ul>
                                </div>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">Sem carros em destaque de momento.</div>
                    </div>
                @endforelse
            </div>

            <br>

            <div class="main-button text-center">
                <a href="/cars">Ver Mais</a>
            </div>
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


    <section class="section section-bg" id="schedule"
        style="background-image: url('resources/images/banner-image.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading dark-bg">
                        <h2><em>Sobre Nós</em></h2>
                        <img src="resources/images/line-dec.png" alt="">
                        <p><br>Na Br3basMotors, damos-lhe acesso a uma seleção exclusiva de viaturas pensadas para os gostos mais exigentes, do desportivo ao mais sofisticado exemplar de luxo.</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="cta-content text-center">
                        <p>Mais do que automóveis, oferecemos uma experiência onde performance, conforto e elegância se unem de forma irrepreensível.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="section section-bg" id="call-to-action" style="background-image: url('resources/images/bannerimage2.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="cta-content">
                        <h2>Envie-nos uma <em>mensagem!</em></h2>
                        <p></p>
                        <div class="main-button">
                            <a href="/contact">Contacte-nos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection