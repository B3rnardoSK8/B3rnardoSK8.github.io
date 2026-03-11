@extends('layouts.app')

@section('content')

  <section class="section section-bg" id="call-to-action" style="background-image: url('resources/images/banner-image-1-1920x500.jpg')">
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

            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              </ol>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <img class="d-block w-100" src="{{ asset($car->image_path ?? 'resources/images/product-1-720x480.jpg') }}" alt="{{ $car->title }}">
                </div>
              </div>
              <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
            
            <br>
            <br>
              <div class="col-lg-8">
                <section class='tabs-content' style="width: 100%;">
                     <article id='tabs-1'>
                    <h4>Vehicle Specs</h4>

                    <div class="row">
                       <div class="col-sm-6">
                         <label>Tipo</label>
                         <p>{{ $car->is_new ? 'Novo' : 'Usado' }}</p>
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
                         <label>Kilometragem</label>
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
                         <label>Potência</label>
                         <p>{{ $car->power ? $car->power.' cv' : '-' }}</p>
                       </div>

                       <div class="col-sm-6">
                         <label>Transmissão</label>
                         <p>{{ $car->transmission ?? '-' }}</p>
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
                        <h4>Vehicle Description</h4>
                        
                        <p>{{ $car->description ?? 'Sem descrição.' }}</p> 
                      </article>
                  <article id='tabs-4'>
                    <h4>Contact Details</h4>

                    <div class="row">   
                        <div class="col-sm-6">
                            <label>Vendedor:</label>

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
    
    

@endsection