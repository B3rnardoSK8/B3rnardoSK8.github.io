@extends('layouts.app')

@section('content')


    <video class="login-bg-video" autoplay muted loop playsinline>
        <source src="resources/images/video1.mp4" type="video/mp4">
    </video>
    <div class="login-bg-overlay"></div>

    <div class="login-page">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <h2>Esqueceu-se da Palavra Passe?</h2>
                    <p>Vamos enviar um link para redefinir a sua password</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                        @if (session('email_sent_to'))
                            <br>
                            <strong>Email destinatário:</strong> {{ session('email_sent_to') }}
                        @endif
                    </div>
                @endif

                <form class="login-form" method="POST" action="{{ route('password.email') }}" novalidate>
                    @csrf

                    <div class="form-group">
                        <div class="input-wrapper">
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            <label for="email">Email</label>
                            <span class="focus-border"></span>
                        </div>
                        @error('email')
                            <span class="error-message show">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="login-btn btn">
                        <span class="btn-text">Enviar Link de Recuperação</span>
                        <span class="btn-loader"></span>
                    </button>
                </form>

                <div class="signup-link">
                    <p>Lembrou-se da password? <a href="{{ route('login') }}">Entrar</a></p>
                </div>
            </div>
        </div>
    </div>
    

@endsection