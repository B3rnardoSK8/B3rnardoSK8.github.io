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
                <h2>Seja Bem-vindo!</h2>
                <p>Crie a sua conta</p>
            </div>
            
            <form class="login-form" id="registerForm" method="POST" action="{{ route('register') }}" novalidate>
                @csrf
                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">
                        <label for="name">Nome</label>
                        <span class="focus-border"></span>
                    </div>
                    @error('name')
                        <span class="error-message show">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">
                        <label for="email">Email</label>
                        <span class="focus-border"></span>
                    </div>
                    @error('email')
                        <span class="error-message show">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-wrapper password-wrapper">
                        <input type="password" id="password" name="password" required autocomplete="new-password">
                        <label for="password">Password</label>
                        <button type="button" class="password-toggle" aria-label="Mostrar/Ocultar password">
                            <span class="eye-icon"></span>
                        </button>
                        <span class="focus-border"></span>
                    </div>
                    @error('password')
                        <span class="error-message show">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-wrapper password-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                        <label for="password_confirmation">Confirmar Password</label>
                        <button type="button" class="password-toggle" aria-label="Mostrar/Ocultar confirmação da password">
                            <span class="eye-icon"></span>
                        </button>
                        <span class="focus-border"></span>
                    </div>
                </div>

                <button type="submit" class="login-btn btn">
                    <span class="btn-text">Registar</span>
                    <span class="btn-loader"></span>
                </button>
            </form>

            <div class="signup-link">
                <p>Já possui uma conta? <a href="{{ route('login') }}">Entrar</a></p>
            </div>

            <div class="success-message" id="successMessage">
                <div class="success-icon">✓</div>
                <h3>Registo Efetuado Com Sucesso!</h3>
                <p>Redirecionando-o...</p>
            </div>
            </div>
        </div>
    </div>
    

@endsection