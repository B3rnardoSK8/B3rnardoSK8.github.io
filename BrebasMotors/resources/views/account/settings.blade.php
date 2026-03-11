@extends('layouts.app')

@section('content')
<div class="account-settings-page">
    <video class="account-settings-bg-video" autoplay muted loop playsinline>
        <source src="resources/images/video2.mp4" type="video/mp4">
    </video>
    <div class="account-settings-bg-overlay"></div>

    <section class="account-settings-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="account-settings-card">
                        <div class="account-settings-header text-center">
                            <h2>Definições da Conta</h2>
                            <p>Atualize os seus dados pessoais e a sua foto de perfil.</p>
                        </div>

                    @if (session('status'))
                        <div class="account-settings-alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('account.settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4 text-center account-settings-photo-col">
                                @php
                                    $photo = Auth::user()->profile_photo_path
                                        ? asset(Auth::user()->profile_photo_path)
                                        : asset('resources/images/loginPerson.png');
                                @endphp
                                <img src="{{ $photo }}" alt="Foto de perfil" class="account-settings-photo">
                                <div class="account-settings-photo-field">
                                    <label for="profile_photo" class="account-settings-photo-label">Foto de perfil</label>
                                    <input id="profile_photo" type="file" name="profile_photo" accept="image/*" class="form-control mt-2 @error('profile_photo') is-invalid @enderror">
                                    @error('profile_photo')
                                        <div class="invalid-feedback account-settings-invalid">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Nome</label>
                                    <input id="name" type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                    @error('name')
                                        <div class="invalid-feedback account-settings-invalid">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                                    @error('email')
                                        <div class="invalid-feedback account-settings-invalid">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone">Telefone</label>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" class="form-control @error('phone') is-invalid @enderror" placeholder="Ex: +351 912 345 678">
                                    @error('phone')
                                        <div class="invalid-feedback account-settings-invalid">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="city">Cidade</label>
                                    <input id="city" type="text" name="city" value="{{ old('city', Auth::user()->city) }}" class="form-control @error('city') is-invalid @enderror">
                                    @error('city')
                                        <div class="invalid-feedback account-settings-invalid">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="bio">Sobre si</label>
                                    <textarea id="bio" name="bio" rows="4" class="form-control @error('bio') is-invalid @enderror" placeholder="Fale um pouco sobre si...">{{ old('bio', Auth::user()->bio) }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback account-settings-invalid">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="main-button account-settings-submit">
                                    Guardar alterações
                                </button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
