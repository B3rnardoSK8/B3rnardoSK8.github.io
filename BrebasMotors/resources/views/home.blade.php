@extends('layouts.app')

@section('content')

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
            <div class="row">
                @forelse($featuredCars ?? [] as $car)
                    <div class="col-lg-4">
                        <a href="{{ route('cars.show', $car) }}" class="trainer-item d-block" style="text-decoration: none; color: inherit;">
                            <div class="image-thumb">
                                <img src="{{ asset($car->image_path ?? 'resources/images/product-1-720x480.jpg') }}" alt="{{ $car->title }}">
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


    <section class="section section-bg" id="schedule"
        style="background-image: url('resources/images/car-image-1-1200x600.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="section-heading dark-bg">
                        <h2><em>Sobre Nós</em></h2>
                        <img src="resources/images/line-dec.png" alt="">
                        <p>Olá! Somos a Br3basMotors, a mais nova empresa que veio mostrar a Portugal tudo o que o
                            mercado automóvel tem de melhor a oferecer.</p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="cta-content text-center">
                        <p>Temos viaturas para os gostos mais requintados.<br> Na Br3basMotors a desportividade, o conforto e o
                            luxo encontram-se num só lugar.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="section section-bg" id="call-to-action" style="background-image: url('resources/images/banner-image-1-1920x500.jpg')">
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