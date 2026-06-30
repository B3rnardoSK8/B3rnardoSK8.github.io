@extends('layouts.app')

@section('content')

  <section class="section section-bg" id="call-to-action" style="background-image: url('resources/images/bannerimage3.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="cta-content">
                        <br>
                        <br>
              <h2>{{ $car->title }}</h2>
              <p><strong><sup>€</sup>{{ number_format($car->price, 0, ',', ' ') }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    

    <section class="section" id="trainers">
        <div class="container">
            <br>
            <br>

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

                    if (count($galleryImages) === 0) {
                        $galleryImages[] = 'resources/images/car.png';
                    }
                @endphp

            <div class="car-card-wrapper car-details-gallery-wrapper">
                @auth
                    <form action="{{ route('cars.favorite.toggle', $car) }}" method="POST" class="favorite-toggle-form">
                        @csrf
                        <button
                            type="submit"
                            class="car-favorite-button {{ !empty($isFavorite) ? 'is-favorite' : '' }}"
                            title="{{ !empty($isFavorite) ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}"
                            aria-label="{{ !empty($isFavorite) ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}"
                        >
                            <i class="fa {{ !empty($isFavorite) ? 'fa-heart' : 'fa-heart-o' }}" aria-hidden="true"></i>
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

                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                  <ol class="carousel-indicators">
                        @foreach ($galleryImages as $index => $image)
                          <li data-target="#carouselExampleIndicators" data-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
                        @endforeach
                  </ol>
                  <div class="carousel-inner">
                        @foreach ($galleryImages as $index => $image)
                          <div class="carousel-item {{ $index === 0 ? 'active' : '' }} car-details-carousel-item">
                            <img class="d-block w-100" src="{{ asset($resolveImagePath($image)) }}" alt="{{ $car->title }}">
                            @if($car->is_sold)
                                <div class="car-sold-ribbon car-sold-ribbon--details">
                                    <span>Vendido</span>
                                </div>
                            @endif
                          </div>
                        @endforeach
                  </div>
                  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Anterior</span>
                  </a>
                  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Próximo</span>
                  </a>
                </div>
            </div>
            
            <br>
            <br>
            <div class="row justify-content-center">
              <div class="col-lg-10 col-12">
                <section class='tabs-content car-details-tabs'>
                     <article id='tabs-1'>
                    <h4>Detalhes do Automóvel</h4>

                    <div class="row">
                       <div class="col-sm-6">
                         <label>Tipo</label>
                         <p>{{ $car->is_new ? 'Novo' : 'Usado' }}</p>
                       </div>

                       <div class="col-sm-6">
                         <label>Ano</label>
                         <p>{{ $car->year ?? '-' }}</p>
                       </div>

                       <div class="col-sm-6">
                         <label>Marca</label>
                         <p>{{ $car->brand }}</p>
                       </div>

                       <div class="col-sm-6">
                         <label>Modelo</label>
                         <p>{{ $car->model }}</p>
                       </div>

                       <div class="col-sm-6">
                         <label>Segmento</label>
                         <p>{{ $car->segment ?? '-' }}</p>
                       </div>

                       <div class="col-sm-6">
                         <label>Quilometragem</label>
                         <p>{{ number_format($car->mileage, 0, ',', ' ') }} km</p>
                       </div>

                       <div class="col-sm-6">
                         <label>Combustível</label>
                         <p>{{ $car->fuel ?? '-' }}</p>
                       </div>

                       <div class="col-sm-6">
                         <label>Motor</label>
                         <p>{{ $car->engine ?? '-' }}</p>
                       </div>

                       <div class="col-sm-6">
                         <label>Transmissão</label>
                         <p>{{ $car->transmission ?? '-' }}</p>
                       </div>

                       <div class="col-sm-6">
                         <label>Potência</label>
                         <p>{{ $car->power ? $car->power.' cv' : '-' }}</p>
                       </div>

                       <div class="col-sm-6">
                         <label>Lugares</label>
                         <p>{{ $car->seats ?? '-' }}</p>
                       </div>

                       <div class="col-sm-6">
                         <label>Portas</label>
                         <p>{{ $car->doors ?? '-' }}</p>
                       </div>
                    </div>
                     </article>
                     <article id='tabs-2'>
                        <h4>Descrição</h4>
                        
                        <p>{{ $car->description ?? 'Sem descrição.' }}</p> 
                      </article>
                  <article id='tabs-4'>
                    <h4>Detalhes de Contato</h4>

                    <div class="row">   
                        <div class="col-sm-6">
                            <label>Vendedor</label>

                            <p>Bernardo Ângelo</p>
                        </div>
                        <div class="col-sm-6">
                            <label>Telemóvel</label>

                            <p>+351 911787311</p>
                        </div>
                        <div class="col-sm-6">
                            <label>Email</label>

                            <p><a href="mailto:bernardo.angelo@br3basmotors.pt">bernardo.angelo@br3basmotors.pt</a></p>
                        </div>
                    </div>
                  </article>
                </section>
              </div>
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
    
    

@endsection