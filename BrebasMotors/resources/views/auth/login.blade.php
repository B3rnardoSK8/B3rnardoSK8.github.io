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
                <h2>Bem-vindo de volta!</h2>
                <p>Entre na sua conta</p>
            </div>
            
            <form class="login-form" id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
                @csrf
                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder=" ">
                        <label for="email">Email</label>
                        <span class="focus-border"></span>
                    </div>
                    @error('email')
                        <span class="error-message show">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-wrapper password-wrapper">
                        <input type="password" id="password" name="password" required autocomplete="current-password" placeholder=" ">
                        <label for="password">Password</label>
                        <button type="button" class="password-toggle" id="passwordToggle" aria-label="Toggle password visibility">
                            <span class="eye-icon"></span>
                        </button>
                        <span class="focus-border"></span>
                    </div>
                    @error('password')
                        <span class="error-message show">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-options">
                    <label class="remember-wrapper">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkbox-label">
                            <span class="checkmark"></span>
                            Lembrar-me
                        </span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-password">Esqueceu-se da password?</a>
                </div>

                <button type="submit" class="login-btn btn">
                    <span class="btn-text">Entrar</span>
                    <span class="btn-loader"></span>
                </button>
            </form>

            <div class="signup-link">
                <p>Não tem uma conta? <a href="{{ route('register') }}">Registrar-me</a></p>
            </div>

            <div class="success-message" id="successMessage">
                <div class="success-icon">✓</div>
                <h3>Login Efetuado Com Sucesso!</h3>
                <p>Redirecionando-o...</p>
            </div>
            </div>
        </div>
    </div>

@endsection