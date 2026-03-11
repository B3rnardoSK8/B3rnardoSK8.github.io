@extends('layouts.app')

@section('content')
<section class="section section-bg" id="call-to-action" style="background-image: url('resources/images/banner-image-1-1920x500.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 offset-lg-1 text-center">
                <h2>Definições da Conta</h2>
                <p>Atualize os seus dados pessoais e a sua foto de perfil.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding: 60px 0; background:#f6f6f6;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div style="background:#fff; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,.08); padding:30px;">
                    @if (session('status'))
                        <div style="margin-bottom:20px; padding:12px 14px; border-radius:8px; background:#e8f7ee; color:#1f6b3a; font-weight:500;">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('account.settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4 text-center" style="margin-bottom:20px;">
                                @php
                                    $photo = Auth::user()->profile_photo_path
                                        ? asset(Auth::user()->profile_photo_path)
                                        : asset('resources/images/loginPerson.png');
                                @endphp
                                <img src="{{ $photo }}" alt="Foto de perfil" style="width:150px; height:150px; object-fit:cover; border-radius:50%; border:4px solid #ececec;">
                                <div style="margin-top:12px;">
                                    <label for="profile_photo" style="font-weight:600; color:#232d39;">Foto de perfil</label>
                                    <input id="profile_photo" type="file" name="profile_photo" accept="image/*" class="form-control mt-2 @error('profile_photo') is-invalid @enderror">
                                    @error('profile_photo')
                                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Nome</label>
                                    <input id="name" type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                    @error('name')
                                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                                    @error('email')
                                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="phone">Telefone</label>
                                    <input id="phone" type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" class="form-control @error('phone') is-invalid @enderror" placeholder="Ex: +351 912 345 678">
                                    @error('phone')
                                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="city">Cidade</label>
                                    <input id="city" type="text" name="city" value="{{ old('city', Auth::user()->city) }}" class="form-control @error('city') is-invalid @enderror">
                                    @error('city')
                                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="bio">Sobre si</label>
                                    <textarea id="bio" name="bio" rows="4" class="form-control @error('bio') is-invalid @enderror" placeholder="Fale um pouco sobre si...">{{ old('bio', Auth::user()->bio) }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback" style="display:block;">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="main-button" style="border:none; cursor:pointer;">
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
@endsection
