@extends('layouts.back-admin')

@section('title', 'Gestao de Destaques')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="card-title mb-1">Destaques</h4>
                        <p class="text-muted mb-0">Selecione ate 3 veiculos para a home page.</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('back.cars.index') }}" class="btn btn-light btn-sm">Voltar para Veiculos</a>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('back.cars.highlights.update') }}" id="highlights-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="featured_order" id="featuredOrderInput" value="{{ implode(',', $featuredIds) }}">

                    <div class="table-responsive">
                        <table class="table table-striped align-middle highlights-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 100px;">Destaque</th>
                                    <th>ID</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Ano</th>
                                    <th>Preco</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cars as $car)
                                    <tr class="js-selectable-row" data-car-id="{{ $car->id }}">
                                        <td class="text-center align-middle">
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input js-featured-checkbox"
                                                    type="checkbox"
                                                    name="featured_ids[]"
                                                    value="{{ $car->id }}"
                                                    id="featured_{{ $car->id }}"
                                                    {{ in_array($car->id, $featuredIds, true) ? 'checked' : '' }}
                                                >
                                            </div>
                                        </td>
                                        <td>{{ $car->id }}</td>
                                        <td>{{ $car->brand }}</td>
                                        <td>{{ $car->model }}</td>
                                        <td>{{ $car->year ?? '-' }}</td>
                                        <td>{{ number_format((float) $car->price, 0, ',', '.') }} EUR</td>
                                        <td>{{ $car->is_new ? 'Novo' : 'Usado' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Sem veiculos registados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">Pode selecionar no maximo 3 veiculos. Arraste as linhas selecionadas para definir a ordem na home page.</small>
                        <button type="submit" class="btn btn-primary btn-sm">Guardar Destaques</button>
                    </div>
                </form>

                <div class="mt-3">
                    {{ $cars->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .highlights-table .js-selectable-row {
        cursor: pointer;
    }

    .highlights-table .js-selectable-row.is-selected {
        cursor: grab;
    }

    .highlights-table .js-selectable-row.is-selected.is-dragging {
        opacity: 0.6;
        cursor: grabbing;
    }

    .highlights-table tbody td:first-child {
        text-align: center;
    }

    .highlights-table .form-check {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        min-height: auto;
        padding-left: 0;
    }

    .highlights-table .form-check-input {
        float: none;
        margin: 0;
    }

    .highlights-table .js-selectable-row.is-selected > td {
        background-color: #f3e2de !important;
        color: #232d39 !important;
    }

    .highlights-table .js-selectable-row.is-disabled-choice > td {
        background-color: #f5f6f8 !important;
        color: #a8afb8 !important;
    }

    .highlights-table .js-selectable-row.is-disabled-choice {
        cursor: not-allowed;
    }

    .highlights-table .js-selectable-row.is-disabled-choice .form-check-input {
        opacity: 0.45;
    }

    .highlights-table .js-selectable-row.is-selected > td .form-check-input {
        border-color: #c6cbd4;
        opacity: 1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableBody = document.querySelector('.highlights-table tbody');
        const checkboxes = Array.from(document.querySelectorAll('.js-featured-checkbox'));
        const featuredOrderInput = document.getElementById('featuredOrderInput');
        let draggedRow = null;

        function getRows() {
            return Array.from(document.querySelectorAll('.js-selectable-row'));
        }

        function updateFeaturedOrderInput() {
            if (!featuredOrderInput) {
                return;
            }

            const orderedIds = getRows()
                .filter((row) => {
                    const checkbox = row.querySelector('.js-featured-checkbox');
                    return checkbox && checkbox.checked;
                })
                .map((row) => row.dataset.carId);

            featuredOrderInput.value = orderedIds.join(',');
        }

        function updateSelectedRowState() {
            getRows().forEach((row) => {
                const checkbox = row.querySelector('.js-featured-checkbox');
                if (!checkbox) {
                    return;
                }

                row.classList.toggle('is-selected', checkbox.checked);
                row.classList.toggle('is-disabled-choice', checkbox.disabled && !checkbox.checked);
                row.setAttribute('draggable', checkbox.checked ? 'true' : 'false');
            });
        }

        function updateCheckboxState() {
            const selected = checkboxes.filter((checkbox) => checkbox.checked).length;
            const limitReached = selected >= 3;

            checkboxes.forEach((checkbox) => {
                checkbox.disabled = !checkbox.checked && limitReached;
            });

            updateSelectedRowState();
            updateFeaturedOrderInput();
        }

        getRows().forEach((row) => {
            row.addEventListener('click', function (event) {
                const target = event.target;

                if (target.closest('input, button, a, label')) {
                    return;
                }

                const checkbox = row.querySelector('.js-featured-checkbox');
                if (!checkbox || checkbox.disabled) {
                    return;
                }

                checkbox.checked = !checkbox.checked;
                checkbox.dispatchEvent(new Event('change', { bubbles: true }));
            });

            row.addEventListener('dragstart', function (event) {
                if (!row.classList.contains('is-selected')) {
                    event.preventDefault();
                    return;
                }

                draggedRow = row;
                row.classList.add('is-dragging');

                if (event.dataTransfer) {
                    event.dataTransfer.effectAllowed = 'move';
                }
            });

            row.addEventListener('dragover', function (event) {
                if (!draggedRow || row === draggedRow || !row.classList.contains('is-selected')) {
                    return;
                }

                event.preventDefault();

                const bounds = row.getBoundingClientRect();
                const shouldInsertBefore = event.clientY < bounds.top + bounds.height / 2;

                if (!tableBody) {
                    return;
                }

                if (shouldInsertBefore) {
                    tableBody.insertBefore(draggedRow, row);
                } else {
                    tableBody.insertBefore(draggedRow, row.nextSibling);
                }
            });

            row.addEventListener('drop', function (event) {
                event.preventDefault();
                updateFeaturedOrderInput();
            });

            row.addEventListener('dragend', function () {
                row.classList.remove('is-dragging');
                draggedRow = null;
                updateFeaturedOrderInput();
            });
        });

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', updateCheckboxState);
        });

        updateCheckboxState();
    });
</script>
@endsection
