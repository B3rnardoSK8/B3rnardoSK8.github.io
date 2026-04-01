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
                                    $defaultPhoto = asset('resources/images/loginPerson.png');
                                    $photo = Auth::user()->profile_photo_path
                                        ? asset(Auth::user()->profile_photo_path)
                                        : $defaultPhoto;
                                    $hasProfilePhoto = !empty(Auth::user()->profile_photo_path);
                                @endphp
                                <img id="profile_photo_preview" src="{{ $photo }}" alt="Foto de perfil" class="account-settings-photo">
                                <div class="account-settings-photo-field">
                                    <label class="account-settings-photo-label">Foto de perfil</label>
                                    <input type="hidden" id="remove_profile_photo" name="remove_profile_photo" value="{{ old('remove_profile_photo', '0') }}">
                                    <input id="profile_photo" type="file" name="profile_photo" accept="image/*" class="account-settings-photo-input @error('profile_photo') is-invalid @enderror">
                                    <div class="account-settings-photo-actions">
                                        <label for="profile_photo" class="account-settings-photo-picker">Escolher imagem</label>
                                        <button
                                            type="button"
                                            id="remove_profile_photo_button"
                                            class="account-settings-photo-remove {{ $hasProfilePhoto ? '' : 'is-disabled' }}"
                                            @if (!$hasProfilePhoto) disabled @endif
                                            aria-label="Remover foto de perfil"
                                            title="Remover foto de perfil"
                                        >
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
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

                                <div class="account-settings-actions">
                                    <button type="submit" class="main-button account-settings-submit">
                                        Guardar alterações
                                    </button>
                                    <button type="button" class="account-settings-delete-trigger" data-toggle="modal" data-target="#deleteAccountModal">
                                        Eliminar conta
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content account-settings-delete-modal">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title" id="deleteAccountModalLabel">Confirmar eliminação da conta</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body pt-2">
                                    <p class="account-settings-delete-text">Esta ação é permanente. Para confirmar, introduza o seu email e palavra-passe.</p>

                                    <form method="POST" action="{{ route('account.settings.destroy') }}">
                                        @csrf
                                        @method('DELETE')

                                        <div class="form-group">
                                            <label for="delete_email">Email da conta</label>
                                            <input id="delete_email" type="email" name="delete_email" value="{{ old('delete_email') }}" class="form-control @error('delete_email', 'deleteAccount') is-invalid @enderror" required>
                                            @error('delete_email', 'deleteAccount')
                                                <div class="invalid-feedback account-settings-invalid">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="delete_password">Palavra-passe</label>
                                            <input id="delete_password" type="password" name="delete_password" class="form-control @error('delete_password', 'deleteAccount') is-invalid @enderror" required>
                                            @error('delete_password', 'deleteAccount')
                                                <div class="invalid-feedback account-settings-invalid">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        @error('delete_credentials', 'deleteAccount')
                                            <div class="account-settings-delete-error">{{ $message }}</div>
                                        @enderror

                                        <div class="account-settings-delete-actions">
                                            <button type="button" class="account-settings-delete-cancel" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="account-settings-delete-confirm">Eliminar conta</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var modal = document.getElementById('deleteAccountModal');
                            var photoInput = document.getElementById('profile_photo');
                            var photoPreview = document.getElementById('profile_photo_preview');
                            var removePhotoInput = document.getElementById('remove_profile_photo');
                            var removePhotoButton = document.getElementById('remove_profile_photo_button');
                            var defaultPhoto = '{{ $defaultPhoto }}';

                            if (!modal || !window.jQuery) {
                                if (!photoInput || !photoPreview) {
                                    return;
                                }
                            } else {
                                var $modal = window.jQuery(modal);
                                $modal.appendTo('body');
                            }

                            if (!photoInput || !photoPreview) {
                                return;
                            }

                            var updateRemoveButtonState = function (isEnabled) {
                                if (!removePhotoButton) {
                                    return;
                                }

                                removePhotoButton.disabled = !isEnabled;
                                removePhotoButton.classList.toggle('is-disabled', !isEnabled);
                            };

                            if (removePhotoInput && removePhotoInput.value === '1') {
                                photoPreview.src = defaultPhoto;
                                updateRemoveButtonState(false);
                            }

                            photoInput.addEventListener('change', function (event) {
                                var file = event.target.files && event.target.files[0];

                                if (!file) {
                                    return;
                                }

                                if (removePhotoInput) {
                                    removePhotoInput.value = '0';
                                }

                                var previewUrl = URL.createObjectURL(file);
                                photoPreview.src = previewUrl;
                                photoPreview.onload = function () {
                                    URL.revokeObjectURL(previewUrl);
                                };

                                updateRemoveButtonState(true);
                            });

                            if (removePhotoButton && removePhotoInput) {
                                removePhotoButton.addEventListener('click', function () {
                                    photoInput.value = '';
                                    removePhotoInput.value = '1';
                                    photoPreview.src = defaultPhoto;
                                    updateRemoveButtonState(false);
                                });
                            }
                        });
                    </script>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
