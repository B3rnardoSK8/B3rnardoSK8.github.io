@extends('layouts.app')

@section('content')

    @php
        $resolveImagePath = static function (?string $image): string {
            if (!$image) {
                return 'resources/images/logo.png';
            }

            if (str_starts_with($image, 'storage/') || str_starts_with($image, 'resources/')) {
                return $image;
            }

            return 'storage/images/cars/'.$image;
        };
    @endphp

    <section class="section section-bg" id="call-to-action" style="background-image: url('resources/images/banner-image-1-1920x500.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="cta-content">
                        <br>
                        <br>
                        <h2>Os nossos <em>Carros</em></h2>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </section>
    

    
    <section class="section" id="trainers">
        <div class="container">
            <br>
            <br>
            <div class="contact-form">
                <form action="{{ route('cars.index') }}" method="GET" id="car-filter-form">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Usado/Novo:</label>
                                 <select name="condition" class="form-control">
                                      <option value="">-- Todos --</option>
                                      <option value="used" {{ ($filters['condition'] ?? '') === 'used' ? 'selected' : '' }}>Usado</option>
                                      <option value="new" {{ ($filters['condition'] ?? '') === 'new' ? 'selected' : '' }}>Novo</option>
                                 </select>
                            </div>
                        </div>
                
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Segmento:</label>
                                 <select name="segment" class="form-control">
                                      <option value="">-- Todos --</option>
                                      @foreach ($options['segments'] as $segment)
                                        <option value="{{ $segment }}" {{ ($filters['segment'] ?? '') === $segment ? 'selected' : '' }}>{{ $segment }}</option>
                                      @endforeach
                                 </select>
                            </div>
                        </div>
                
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Marca:</label>
                                 <select name="brand" class="form-control" id="brand-select">
                                      <option value="">-- Todos --</option>
                                      @foreach ($options['brands'] as $brand)
                                        <option value="{{ $brand }}" {{ ($filters['brand'] ?? '') === $brand ? 'selected' : '' }}>{{ $brand }}</option>
                                      @endforeach
                                 </select>
                            </div>
                        </div>
                
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Modelo:</label>
                                   <select name="model" class="form-control" id="model-select" {{ empty($filters['brand'] ?? '') ? 'disabled' : '' }}>
                                       <option value="">-- Escolha uma marca primeiro --</option>
                                       @foreach ($options['models'] as $model)
                                        <option value="{{ $model }}" {{ ($filters['model'] ?? '') === $model ? 'selected' : '' }}>{{ $model }}</option>
                                       @endforeach
                                   </select>
                            </div>
                        </div>
                
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Preço até:</label>
                                 <select name="price_max" class="form-control">
                                      <option value="">-- Todos --</option>
                                      @foreach ($options['prices'] as $price)
                                        <option value="{{ $price }}" {{ ($filters['price_max'] ?? '') == $price ? 'selected' : '' }}>€ {{ number_format($price, 0, ',', ' ') }}</option>
                                      @endforeach
                                 </select>
                            </div>
                        </div>
                
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Kilometragem até:</label>
                                 <select name="mileage_max" class="form-control">
                                      <option value="">-- Todos --</option>
                                      @foreach ($options['mileages'] as $mileage)
                                        <option value="{{ $mileage }}" {{ ($filters['mileage_max'] ?? '') == $mileage ? 'selected' : '' }}>{{ number_format($mileage, 0, ',', ' ') }} km</option>
                                      @endforeach
                                 </select>
                            </div>
                        </div>
                
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                  <label>Combustível:</label>
                                   <select name="fuel" class="form-control">
                                       <option value="">-- Todos --</option>
                                       @foreach ($options['fuels'] as $fuel)
                                        <option value="{{ $fuel }}" {{ ($filters['fuel'] ?? '') === $fuel ? 'selected' : '' }}>{{ $fuel }}</option>
                                       @endforeach
                                   </select>
                            </div>
                        </div>
                
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            <div class="form-group">
                                  <label>Transmissão:</label>
                                   <select name="transmission" class="form-control">
                                       <option value="">-- Todos --</option>
                                       @foreach ($options['transmissions'] as $transmission)
                                        <option value="{{ $transmission }}" {{ ($filters['transmission'] ?? '') === $transmission ? 'selected' : '' }}>{{ $transmission }}</option>
                                       @endforeach
                                   </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-sm-4 offset-sm-4">
                      <div class="main-button text-center">
                          <button type="submit" class="btn btn-primary" style="width: 100%;">Procurar</button>
                      </div>
                    </div>
                    <br><br><br><br>
                </form>
            </div>

            <div class="row cars-grid">
                @forelse ($cars as $car)
                    <div class="col-lg-4">
                        <a href="{{ route('cars.show', $car) }}" class="trainer-item d-block" style="text-decoration: none; color: inherit;">
                            <div class="image-thumb">
                                <img src="{{ asset($resolveImagePath($car->image_path)) }}" alt="{{ $car->title }}">
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
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">Nenhum carro encontrado.</div>
                    </div>
                @endforelse
            </div>

            <br>
            
            <div class="d-flex justify-content-center">
                {{ $cars->links('pagination::bootstrap-4') }}
            </div>

        </div>
    </section>

<script>
    (function() {
        const brandSelect = document.getElementById('brand-select');
        const modelSelect = document.getElementById('model-select');
        // Parse from a JSON string so the editor won't treat Blade directives as JS
        const modelsByBrand = JSON.parse('{!! json_encode($modelsByBrand ?? []) !!}');

        const currentBrand = brandSelect.value;
        const currentModel = "{{ $filters['model'] ?? '' }}";

        function populateModels(brand) {
            // Clear current options
            modelSelect.innerHTML = '';

            if (!brand || !modelsByBrand[brand] || modelsByBrand[brand].length === 0) {
                modelSelect.disabled = true;
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = '-- Escolha uma marca primeiro --';
                modelSelect.appendChild(opt);
                return;
            }

            modelSelect.disabled = false;
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = '-- Todos --';
            modelSelect.appendChild(placeholder);

            modelsByBrand[brand].forEach((model) => {
                const opt = document.createElement('option');
                opt.value = model;
                opt.textContent = model;
                if (model === currentModel) {
                    opt.selected = true;
                }
                modelSelect.appendChild(opt);
            });
        }

        // Init on load
        populateModels(currentBrand);

        // On brand change, reset model selection and repopulate
        brandSelect.addEventListener('change', function () {
            // Clear current selection
            modelSelect.value = '';
            populateModels(this.value);
        });
    })();
</script>

@endsection