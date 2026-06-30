@extends('layouts.back-admin')

@section('title', 'Gestao de Veiculos')

@section('content')
@php
    $shouldOpenReserveModal = $errors->has('customer_email') || $errors->has('reserved_until');
@endphp
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-1">Lista de Veículos</h4>
                        <p class="text-muted mb-0">Gestão do catálogo de veículos</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('back.cars.create') }}" class="btn btn-primary btn-sm">Adicionar Veículo</a>
                        <a href="{{ route('back.dashboard') }}" class="btn btn-light btn-sm no-hover-white">Voltar</a>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->has('customer_email'))
                    <div class="alert alert-danger">
                        {{ $errors->first('customer_email') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Ano</th>
                                <th>Preço</th>
                                <th>Disponibilidade</th>
                                <th>Reserva</th>
                                <th>Estado</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($cars as $car)
                                <tr>
                                    <td>{{ $car->id }}</td>
                                    <td>{{ $car->brand }}</td>
                                    <td>{{ $car->model }}</td>
                                    <td>{{ $car->year ?? '-' }}</td>
                                    <td>{{ number_format((float) $car->price, 0, ',', '.') }} EUR</td>
                                    <td>
                                        @if ($car->isCurrentlyReserved())
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-warning js-open-reserve-modal"
                                                data-reserve-url="{{ route('back.cars.reserve', $car) }}"
                                                data-car-title="{{ $car->brand }} {{ $car->model }}"
                                                data-car-id="{{ $car->id }}"
                                                data-reserve-mode="update"
                                                data-reserve-email="{{ $car->reserved_email }}"
                                                data-reserved-until="{{ optional($car->reserved_until)->format('Y-m-d') }}"
                                                title="Editar reserva até {{ $car->reserved_until->format('d/m/Y') }}"
                                            >
                                                Reservado até {{ $car->reserved_until->format('d/m') }}
                                            </button>
                                        @else
                                            <form method="POST" action="{{ route('back.cars.availability.toggle', $car) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    type="submit"
                                                    class="btn btn-sm {{ $car->is_sold ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                    title="Clique para alternar disponibilidade"
                                                >
                                                    {{ $car->is_sold ? 'Vendido' : 'Disponível' }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($car->isCurrentlyReserved())
                                            <form method="POST" action="{{ route('back.cars.reserve', $car) }}" id="cancel-reserve-form-{{ $car->id }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="reservation_action" value="cancel">
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-outline-danger no-hover-white js-confirm-action"
                                                    title="Clique para cancelar a reserva"
                                                    data-target-form="#cancel-reserve-form-{{ $car->id }}"
                                                    data-message="Tem a certeza que pretende cancelar a reserva deste veículo?"
                                                >
                                                    Cancelar
                                                </button>
                                            </form>
                                        @else
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-warning js-open-reserve-modal"
                                                data-reserve-url="{{ route('back.cars.reserve', $car) }}"
                                                data-car-title="{{ $car->brand }} {{ $car->model }}"
                                                data-car-id="{{ $car->id }}"
                                                data-reserve-mode="create"
                                                data-reserve-email=""
                                                data-reserved-until="{{ now()->addDays(14)->format('Y-m-d') }}"
                                                {{ $car->is_sold ? 'disabled' : '' }}
                                            >
                                                Reservar
                                            </button>
                                        @endif
                                    </td>
                                    <td>{{ $car->is_new ? 'Novo' : 'Usado' }}</td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('back.cars.show', $car) }}" class="btn btn-outline-secondary btn-sm no-hover-white">Ver</a>
                                            @if ($car->isCurrentlyReserved())
                                                <a href="{{ route('back.cars.edit', $car) }}" class="btn btn-outline-primary btn-sm disabled no-hover-white" tabindex="-1" aria-disabled="true">Editar</a>
                                                <button type="button" class="btn btn-outline-danger btn-sm no-hover-white" disabled>Apagar</button>
                                            @else
                                                <a href="{{ route('back.cars.edit', $car) }}" class="btn btn-outline-primary btn-sm no-hover-white {{ $car->is_sold ? 'disabled' : '' }}" {{ $car->is_sold ? 'tabindex=-1 aria-disabled=true' : '' }}>Editar</a>
                                                <form method="POST" action="{{ route('back.cars.destroy', $car) }}" id="delete-car-form-{{ $car->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-outline-danger btn-sm no-hover-white js-confirm-action" data-target-form="#delete-car-form-{{ $car->id }}" data-message="Tem a certeza que pretende remover este veículo?">Apagar</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Sem automóveis registados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $cars->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reserveCarModal" tabindex="-1" role="dialog" aria-labelledby="reserveCarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reserveCarModalLabel">Reservar viatura</h5>
                <button type="button" class="close js-reserve-modal-close" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="reserve-car-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="reserve_url" id="reserve-url-input" value="{{ old('reserve_url') }}">
                <input type="hidden" name="reserve_car_id" id="reserve-car-id-input" value="{{ old('reserve_car_id') }}">
                <input type="hidden" name="reservation_action" id="reservation-action-input" value="{{ old('reservation_action', 'save') }}">
                <input type="hidden" name="reserve_mode" id="reserve-mode-input" value="{{ old('reserve_mode', 'create') }}">
                <div class="modal-body">
                    <p class="text-muted mb-3" id="reserve-car-modal-copy">Introduza o email do cliente e a data de término da reserva.</p>
                    <div class="form-group">
                        <label for="customer-email-input">Email do cliente</label>
                        <input
                            type="email"
                            class="form-control @error('customer_email') is-invalid @enderror"
                            id="customer-email-input"
                            name="customer_email"
                            value="{{ old('customer_email', '') }}"
                            placeholder="cliente@exemplo.com"
                            required
                        >
                        @error('customer_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label for="reserved-until-input">Data de término da reserva</label>
                        <input
                            type="date"
                            class="form-control @error('reserved_until') is-invalid @enderror"
                            id="reserved-until-input"
                            name="reserved_until"
                            value="{{ old('reserved_until', now()->addDays(14)->format('Y-m-d')) }}"
                            min="{{ now()->addDay()->format('Y-m-d') }}"
                            required
                        >
                        @error('reserved_until')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="text-muted d-block">Por omissão pode usar 2 semanas, mas pode ajustar esta data antes de confirmar.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light js-reserve-modal-close no-hover-white">Cancelar</button>
                    <button type="submit" class="btn btn-warning" id="reserve-modal-submit">Confirmar reserva</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation modal for destructive actions (cancel reservation, delete) -->
<div class="modal fade" id="confirmActionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar ação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="confirmActionModalMessage">Tem a certeza?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light no-hover-white" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmActionModalConfirm">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<div
    id="reserve-modal-state"
    data-open="{{ $shouldOpenReserveModal ? '1' : '0' }}"
    data-default-reserved-until="{{ old('reserved_until', now()->addDays(14)->format('Y-m-d')) }}"
    style="display:none;"
></div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reserveModalElement = document.getElementById('reserveCarModal');
        const reserveModalState = document.getElementById('reserve-modal-state');
        const reserveForm = document.getElementById('reserve-car-form');
        const reserveUrlInput = document.getElementById('reserve-url-input');
        const reserveCarIdInput = document.getElementById('reserve-car-id-input');
        const reserveModeInput = document.getElementById('reserve-mode-input');
        const reservationActionInput = document.getElementById('reservation-action-input');
        const reserveModalTitle = document.getElementById('reserveCarModalLabel');
        const reserveModalCopy = document.getElementById('reserve-car-modal-copy');
        const customerEmailInput = document.getElementById('customer-email-input');
        const reservedUntilInput = document.getElementById('reserved-until-input');
        const reserveModalSubmit = document.getElementById('reserve-modal-submit');
        const openButtons = document.querySelectorAll('.js-open-reserve-modal');
        const closeButtons = document.querySelectorAll('.js-reserve-modal-close');

        function setModalMode(mode, carTitle) {
            const isEditMode = mode === 'update';

            reserveModeInput.value = mode;
            reserveModalTitle.textContent = isEditMode ? 'Editar reserva' : 'Reservar viatura';
            reserveModalCopy.textContent = isEditMode
                ? 'Atualize o email do cliente e a data de término da reserva da viatura ' + carTitle + '.'
                : 'Introduza o email do cliente e a data de término da reserva da viatura ' + carTitle + '.';
            reserveModalSubmit.textContent = isEditMode ? 'Guardar alterações' : 'Confirmar reserva';
        }

        if (window.jQuery && reserveModalElement) {
            const modal = window.jQuery(reserveModalElement);
            modal.appendTo('body');

                // Ensure modal is above any other overlay and receives pointer events
                modal.on('show.bs.modal', function () {
                    const $m = window.jQuery(this);
                    $m.css('z-index', 2147483647);
                    // put backdrop just below modal
                    window.jQuery('.modal-backdrop').last().css('z-index', 2147483646);
                    $m.find('.modal-dialog, .modal-content').css('pointer-events', 'auto');
                });

            openButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    const mode = this.dataset.reserveMode || 'create';
                    reserveForm.action = this.dataset.reserveUrl;
                    reserveUrlInput.value = this.dataset.reserveUrl;
                    reserveCarIdInput.value = this.dataset.carId;
                    reservationActionInput.value = 'save';
                    customerEmailInput.value = this.dataset.reserveEmail || '';
                    reservedUntilInput.value = this.dataset.reservedUntil || '';
                    setModalMode(mode, this.dataset.carTitle || '');
                    modal.modal('show');

                    window.setTimeout(function () {
                        customerEmailInput.focus();
                    }, 250);
                });
            });

            closeButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    modal.modal('hide');
                });
            });

            if (reserveUrlInput.value) {
                reserveForm.action = reserveUrlInput.value;
                modal.modal('show');
            }

            if (reserveModalState && reserveModalState.dataset.open === '1') {
                if (reserveUrlInput.value) {
                    reserveForm.action = reserveUrlInput.value;
                }

                if (!reservedUntilInput.value) {
                    reservedUntilInput.value = reserveModalState.dataset.defaultReservedUntil || '';
                }

                setModalMode(reserveModeInput.value || 'create', reserveModalCopy.textContent || '');

                modal.modal('show');

                window.setTimeout(function () {
                    customerEmailInput.focus();
                }, 250);
            }

            /* Confirmation modal for destructive actions */
            const confirmModalElement = document.getElementById('confirmActionModal');
            const confirmMessageEl = document.getElementById('confirmActionModalMessage');
            const confirmButton = document.getElementById('confirmActionModalConfirm');
            let confirmTargetForm = null;

            if (window.jQuery && confirmModalElement) {
                const confirmModal = window.jQuery(confirmModalElement);
                confirmModal.appendTo('body');

                    // Ensure confirm modal is above any other overlay and receives pointer events
                    confirmModal.on('show.bs.modal', function () {
                        const $cm = window.jQuery(this);
                        $cm.css('z-index', 2147483647);
                        window.jQuery('.modal-backdrop').last().css('z-index', 2147483646);
                        $cm.find('.modal-dialog, .modal-content').css('pointer-events', 'auto');
                    });

                    // Fallback: force hide when clicking elements that should dismiss the modal
                    window.jQuery(document).on('click', '.modal .close, .modal [data-dismiss="modal"]', function (e) {
                        try {
                            const $m = window.jQuery(this).closest('.modal');
                            if ($m && $m.length) {
                                $m.modal('hide');
                            }
                        } catch (err) {
                            // swallow
                        }
                    });

                    // Ensure backdrop accepts pointer events so clicks can reach it (to close when configured)
                    window.jQuery(document).on('shown.bs.modal', '.modal', function () {
                        window.jQuery('.modal-backdrop').css('pointer-events', 'auto');
                    });

                document.querySelectorAll('.js-confirm-action').forEach(function (button) {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const selector = this.dataset.targetForm;
                        confirmTargetForm = selector ? document.querySelector(selector) : this.closest('form');
                        confirmMessageEl.textContent = this.dataset.message || 'Tem a certeza?';
                        confirmModal.modal('show');
                    });
                });

                confirmButton.addEventListener('click', function () {
                    if (confirmTargetForm) {
                        confirmModal.modal('hide');
                        confirmTargetForm.submit();
                        confirmTargetForm = null;
                    }
                });
            }
        }
    });
</script>
@endpush
@endsection
