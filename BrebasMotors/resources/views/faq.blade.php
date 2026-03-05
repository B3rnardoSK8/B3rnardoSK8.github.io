@extends('layouts.app')

@section('content')

    <section class="section section-bg" id="call-to-action" style="background-image: url({{ asset('resources/images/banner-image-1-1920x500.jpg') }})">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="cta-content">
                        <br>
                        <br>
                        <h2>As nossas <em>perguntas mais frequentes</em></h2>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="section" id="our-classes">
        <div class="container">
            <br>
            <br>
            <br>
          
            <section class='tabs-content'>
              <article>
                <h4><i class="fa fa-question-circle"></i> Qual o vosso horário de funcionamento?</h4>
                <p>Estamos disponíveis de segunda a sexta-feira, das 9h00 às 18h00, e sábados das 9h00 às 13h00.</p>
                
                <br>
                    
                <h4><i class="fa fa-question-circle"></i> Fazem financiamento dos vossos veículos? Se sim, aceitam sem entrada?</h4>
                <p>Sim! Oferecemos opções de financiamento flexíveis, incluindo a possibilidade de adquirir um veículo sem entrada inicial, sujeitas a aprovação de crédito.</p>

                <br>
                
                <h4><i class="fa fa-question-circle"></i> Aceitam retomas de veículos?</h4>
                <p>Sim! Na compra de um novo veículo, a opção de retoma de veículos como parte do pagamento é possível, sujeita sempre a avaliação prévia do mesmo.</p>

                <br>
                
                <h4><i class="fa fa-question-circle"></i> Os vossos carros possuem garantia?</h4>
                <p>Sim! Após a compra dum veículo, oferecemos até 12 meses de garantia para todos os componentes mecânicos e até 24 meses para toda a carroçaria.</p>
              </article>
            </section>
        </div>
    </section>

@endsection