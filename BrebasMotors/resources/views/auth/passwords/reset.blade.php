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
                    <h2>Redefinir Password</h2>
                    <p>Insira a nova password da sua conta</p>
                </div>

                <form class="login-form" id="resetForm" method="POST" action="{{ route('password.update') }}" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    @error('token')
                        <div class="alert alert-danger" role="alert">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-group">
                        <div class="input-wrapper password-wrapper">
                            <input type="password" id="password" name="password" required autocomplete="new-password">
                            <label for="password">Nova Password</label>
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
                        <span class="btn-text">Guardar Nova Password</span>
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