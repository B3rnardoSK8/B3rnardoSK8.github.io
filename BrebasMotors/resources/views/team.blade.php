@extends('layouts.app')

@section('content')

    <section class="section section-bg" id="call-to-action" style="background-image: url('resources/images/banner-image-1-1920x500.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="cta-content">
                        <br>
                        <br>
                        <h2>A nossa <em>Equipa</em></h2>
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
            <div class="row">
                <div class="col-md-6 offset-md-3 col-sm-12">
                    <div class="trainer-item" style="position: relative;">
                        <img src="{{ asset('resources/images/ceo.jpg') }}" alt="CEO" style="position: absolute; top: 10px; right: 10px; width: 120px; height: 150px; border-radius: 5px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); z-index: 10;">
                        <div class="down-content">
                            <span>CEO</span>
                            <h4>Bernardo Ângelo</h4>
                            <p>19 anos<br>Caldas da Rainha<br>Estudante da Escola Secundária Raúl Proença<br>Técnico de Gestão e Programação de Sistemas Informáticos</p>
                            <ul class="social-icons">
                                <li><a href="https://www.facebook.com/share/1AEzaSqaEV/?mibextid=wwXlfr"><i class="fa fa-facebook"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
@endsection