@extends('layouts.back-admin')

@section('title', 'Editar Veiculo')

@section('content')
<style>
    .car-form .form-control:not(textarea):not([type="file"]):not([type="hidden"]),
    .car-form .form-select {
        min-height: 58px;
        padding: 0.9rem 1rem;
        line-height: 1.35;
        font-size: 1rem;
    }

    .car-form .form-control[type="file"] {
        display: none;
    }

    .car-file-upload {
        position: relative;
        height: 58px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        overflow: hidden;
        background: #fff;
    }

    .car-file-upload-display {
        display: flex;
        align-items: stretch;
        height: 100%;
        width: 100%;
        pointer-events: none;
    }

    .car-file-upload-button {
        display: inline-flex;
        align-items: center;
        padding: 0 0.75rem;
        background: #e9ecef;
        border-right: 1px solid #ced4da;
        white-space: nowrap;
        flex: 0 0 auto;
        font-size: 1rem;
        color: #6c757d;
    }

    .car-file-upload-text {
        display: flex;
        align-items: center;
        padding: 0 0.75rem;
        flex: 1 1 auto;
        min-width: 0;
        color: #6c757d;
        font-size: 1rem;
    }

    .car-file-upload.is-placeholder .car-file-upload-text {
        color: #adb5bd;
    }

    .car-file-upload-input {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .car-auto-field {
        min-height: 58px;
        font-size: 1rem;
    }

    .form-control[readonly],
    .form-control:disabled,
    .form-select:disabled {
        background-color: #e9ecef;
        color: #adb5bd;
        opacity: 1;
    }

    .form-select:disabled {
        -webkit-text-fill-color: #adb5bd;
        background-image: none;
    }

    .form-control[readonly] {
        -webkit-text-fill-color: #adb5bd;
    }
</style>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h4 class="card-title mb-1">Editar Veículo</h4>
                        <p class="text-muted mb-4">Atualize os dados do veículo</p>
                    </div>
                    <a href="{{ route('back.cars.show', $car) }}" class="btn btn-light btn-sm no-hover-white">Voltar</a>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Corrija os erros abaixo:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @php
                    $galleryImages = is_array($car->images) ? $car->images : [];
                    $galleryImages = array_map(static function ($image) {
                        if (str_starts_with($image, 'storage/images/cars/')) {
                            return substr($image, strlen('storage/images/cars/'));
                        }

                        if (str_starts_with($image, 'images/cars/')) {
                            return substr($image, strlen('images/cars/'));
                        }

                        return $image;
                    }, $galleryImages);
                    $galleryImages = array_values(array_unique(array_filter($galleryImages)));

                    $mainImage = $car->image_path;
                    if (str_starts_with((string) $mainImage, 'storage/images/cars/')) {
                        $mainImage = substr((string) $mainImage, strlen('storage/images/cars/'));
                    } elseif (str_starts_with((string) $mainImage, 'images/cars/')) {
                        $mainImage = substr((string) $mainImage, strlen('images/cars/'));
                    }

                    if ($mainImage && !in_array($mainImage, $galleryImages, true)) {
                        $galleryImages[] = $mainImage;
                    }
                    $imagesMarkedForRemoval = old('remove_images', []);

                    $resolveImagePath = static function (?string $image): string {
                        if (!$image) {
                            return 'resources/images/car.png';
                        }

                        if (str_starts_with($image, 'storage/') || str_starts_with($image, 'resources/')) {
                            return $image;
                        }

                        return 'storage/images/cars/'.$image;
                    };
                @endphp

                <form method="POST" action="{{ route('back.cars.update', $car) }}" enctype="multipart/form-data" class="car-form">
                    @csrf
                    @method('PUT')
                    @php
                        $selectedBrand = old('brand', $car->brand);
                        $selectedModel = old('model', $car->model);
                        $selectedSegment = old('segment', $car->segment);
                    @endphp
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Marca</label>
                            <select name="brand" id="brandSelect" class="form-select" required>
                                <option value="">Selecionar marca</option>
                                @foreach ($catalogOptions['brands'] as $brand)
                                    <option value="{{ $brand }}" @selected($selectedBrand === $brand)>{{ $brand }}</option>
                                @endforeach
                                <option value="__add_brand">Adicionar marca...</option>
                            </select>
                            <input type="text" id="brandCustom" name="brand_custom" class="form-control mt-2 d-none" placeholder="Escreva a nova marca" value="{{ old('brand_custom', $car->brand_custom ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modelo</label>
                            <select name="model" id="modelSelect" class="form-select" required disabled>
                                <option value="">Selecionar marca</option>
                            </select>
                            <input type="text" id="modelCustom" name="model_custom" class="form-control mt-2 d-none" placeholder="Escreva o novo modelo" value="{{ old('model_custom', $car->model_custom ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Segmento</label>
                            <input type="hidden" name="segment" id="segmentHidden" value="{{ old('segment', $car->segment) }}">
                            <select id="segmentSelect" class="form-select car-auto-field">
                                <option value="">Selecionar segmento</option>
                                @foreach ($catalogOptions['segments'] as $segment)
                                    <option value="{{ $segment }}" @selected($selectedSegment === $segment)>{{ $segment }}</option>
                                @endforeach
                                <option value="__add_segment">Adicionar segmento...</option>
                            </select>
                            <input type="text" id="segmentCustom" name="segment_custom" class="form-control mt-2 d-none" placeholder="Escreva o novo segmento" value="{{ old('segment_custom', $car->segment_custom ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ano</label>
                            <input type="number" name="year" id="yearInput" class="form-control car-auto-field" value="{{ old('year', $car->year) }}" min="1900" max="{{ now()->year + 1 }}" placeholder="Selecionar modelo" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Preço (EUR)</label>
                            <input type="number" step="500" min="0" name="price" class="form-control" value="{{ old('price', $car->price) }}" placeholder="Ex.: 85000" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quilometragem</label>
                            <input type="number" id="mileageInput" min="0" name="mileage" class="form-control" value="{{ old('mileage', $car->mileage) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Motor</label>
                            <input type="text" name="engine" id="engineInput" class="form-control car-auto-field" value="{{ old('engine', $car->engine) }}" readonly required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Potência (cv)</label>
                            <input type="number" min="0" name="power" id="powerInput" class="form-control car-auto-field" value="{{ old('power', $car->power) }}" readonly required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Combustível</label>
                            <input type="hidden" name="fuel" id="fuelHidden" value="{{ old('fuel', $car->fuel) }}">
                            <select id="fuelSelect" class="form-select car-auto-field" disabled required>
                                <option value="">Selecionar combustível</option>
                                @foreach ($catalogOptions['fuelOptions'] as $fuel)
                                    <option value="{{ $fuel }}" @selected(old('fuel', $car->fuel) === $fuel)>{{ $fuel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Transmissão</label>
                            <input type="hidden" name="transmission" id="transmissionHidden" value="{{ old('transmission', $car->transmission) }}">
                            <select id="transmissionSelect" class="form-select car-auto-field" required disabled>
                                <option value="">Selecionar modelo</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Portas</label>
                            <input type="number" min="1" name="doors" id="doorsInput" class="form-control car-auto-field" value="{{ old('doors', $car->doors) }}" readonly required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lugares</label>
                            <input type="number" min="1" name="seats" id="seatsInput" class="form-control car-auto-field" value="{{ old('seats', $car->seats) }}" readonly required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Adicionar novas imagens</label>
                            <div class="car-file-upload is-placeholder" id="imageUpload">
                                <div class="car-file-upload-display">
                                    <span class="car-file-upload-button">Choose Files</span>
                                    <span id="imageInputText" class="car-file-upload-text">No file chosen</span>
                                </div>
                                <input type="file" id="imageInput" name="images[]" class="car-file-upload-input" accept="image/*" multiple>
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <p class="mb-2 text-muted">Galeria <small>(arraste as imagens para mudar a ordem)</small></p>
                            <input type="hidden" id="imageOrder" name="image_order" value="">
                            <div id="galleryContainer" class="d-flex flex-wrap gap-2" style="min-height: 100px; padding: 8px; border: 1px dashed #ddd; border-radius: 4px; background-color: #f9f9f9;">
                                @foreach ($galleryImages as $image)
                                    @php
                                        $isMarkedForRemoval = in_array($image, $imagesMarkedForRemoval, true);
                                    @endphp
                                    <div class="gallery-item border rounded p-2 position-relative car-image-card" data-image="existing_{{ $loop->index }}" data-existing="true" draggable="true" @if($isMarkedForRemoval) style="opacity: 0.55; border-color: #dc3545 !important;" @endif>
                                        <input
                                            class="d-none car-image-remove-input"
                                            type="checkbox"
                                            name="remove_images[]"
                                            value="{{ $image }}"
                                            id="remove-image-{{ $loop->index }}"
                                            @checked($isMarkedForRemoval)
                                        >
                                        <label
                                            class="btn btn-danger btn-sm position-absolute"
                                            for="remove-image-{{ $loop->index }}"
                                            title="Marcar para remover"
                                            style="top: 6px; right: 6px; line-height: 1; z-index: 10;"
                                        >
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                            <span class="visually-hidden">Remover imagem</span>
                                        </label>
                                        <img src="{{ asset($resolveImagePath($image)) }}" alt="{{ $car->title }}" class="img-thumbnail d-block" style="height: 80px; width: auto; cursor: grab;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea name="description" rows="4" class="form-control">{{ old('description', $car->description) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Guardar Alterações</button>
                        <a href="{{ route('back.cars.index') }}" class="btn btn-light no-hover-white">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

            <script type="application/json" id="edit-car-models-by-brand">{!! json_encode($catalogOptions['modelsByBrand']) !!}</script>
            <script type="application/json" id="edit-car-specs-by-brand-model">{!! json_encode($catalogOptions['specsByBrandModel']) !!}</script>
            <script type="application/json" id="edit-car-selected-model">{!! json_encode(old('model', $car->model)) !!}</script>
            <script type="application/json" id="edit-car-selected-transmission">{!! json_encode(old('transmission', $car->transmission)) !!}</script>
            <script type="application/json" id="edit-car-selected-segment">{!! json_encode(old('segment', $car->segment)) !!}</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const carForm = document.querySelector('form.car-form');
    const mileageInput = document.getElementById('mileageInput');
    const brandSelect = document.getElementById('brandSelect');
    const modelSelect = document.getElementById('modelSelect');
    const segmentHidden = document.getElementById('segmentHidden');
    const segmentSelect = document.getElementById('segmentSelect');
    const yearInput = document.getElementById('yearInput');
    const engineInput = document.getElementById('engineInput');
    const powerInput = document.getElementById('powerInput');
    const fuelHidden = document.getElementById('fuelHidden');
    const fuelSelect = document.getElementById('fuelSelect');
    const transmissionHidden = document.getElementById('transmissionHidden');
    const transmissionSelect = document.getElementById('transmissionSelect');
    const doorsInput = document.getElementById('doorsInput');
    const seatsInput = document.getElementById('seatsInput');
    const imageInput = document.getElementById('imageInput');
    const imageInputText = document.getElementById('imageInputText');
    const imageUpload = document.getElementById('imageUpload');
    const galleryContainer = document.getElementById('galleryContainer');
    const imageOrderInput = document.getElementById('imageOrder');
    const modelsByBrand = JSON.parse(document.getElementById('edit-car-models-by-brand').textContent);
    const specsByBrandModel = JSON.parse(document.getElementById('edit-car-specs-by-brand-model').textContent);
    const selectedModel = JSON.parse(document.getElementById('edit-car-selected-model').textContent);
    const selectedTransmission = JSON.parse(document.getElementById('edit-car-selected-transmission').textContent);
    const brandCustom = document.getElementById('brandCustom');
    const modelCustom = document.getElementById('modelCustom');
    const segmentCustom = document.getElementById('segmentCustom');
    const selectedSegment = JSON.parse(document.getElementById('edit-car-selected-segment').textContent || 'null');
    let newImages = [];
    let draggedElement = null;

    function getTransmissionOptions(specs) {
        if (!specs) {
            return [];
        }

        if (Array.isArray(specs.transmissionOptions) && specs.transmissionOptions.length > 0) {
            return specs.transmissionOptions;
        }

        return specs.transmission ? [specs.transmission] : [];
    }

    function clearAutoFilledFields() {
        segmentSelect.value = '';
        segmentHidden.value = '';
        yearInput.value = '';
        engineInput.value = '';
        powerInput.value = '';
        fuelHidden.value = '';
        fuelSelect.value = '';
        transmissionHidden.value = '';
        transmissionSelect.innerHTML = '';
        transmissionSelect.disabled = true;
        transmissionSelect.required = false;
        transmissionSelect.setCustomValidity('');
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Selecionar modelo';
        transmissionSelect.appendChild(placeholder);
        doorsInput.value = '';
        seatsInput.value = '';
    }

    function applySpecs(brand, model) {
        const specs = specsByBrandModel?.[brand]?.[model];

        if (!specs) {
            clearAutoFilledFields();
            return;
        }

        segmentSelect.value = specs.segment || '';
        segmentHidden.value = specs.segment || '';
        yearInput.value = specs.year ?? '';
        engineInput.value = specs.engine || '';
        powerInput.value = specs.power ?? '';
        fuelSelect.value = specs.fuel || '';
        fuelHidden.value = specs.fuel || '';
        const transmissionOptions = getTransmissionOptions(specs);
        transmissionSelect.innerHTML = '';
        transmissionSelect.disabled = transmissionOptions.length <= 1;
        transmissionSelect.required = transmissionOptions.length > 1;
        transmissionSelect.setCustomValidity('');

        if (transmissionOptions.length === 0) {
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Selecionar modelo';
            transmissionSelect.appendChild(placeholder);
        } else {
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Selecionar transmissão';
            transmissionSelect.appendChild(placeholder);

            transmissionOptions.forEach((optionValue) => {
                const option = document.createElement('option');
                option.value = optionValue;
                option.textContent = optionValue;
                transmissionSelect.appendChild(option);
            });
        }

        if (transmissionOptions.includes(selectedTransmission)) {
            transmissionSelect.value = selectedTransmission;
        } else if (transmissionOptions.length === 1) {
            transmissionSelect.value = transmissionOptions[0];
        }
        transmissionHidden.value = transmissionSelect.value || transmissionOptions[0] || '';
        doorsInput.value = specs.doors ?? '';
        seatsInput.value = specs.seats ?? '';
    }

    function renderModelOptions(brand) {
        const models = modelsByBrand[brand] || [];

        modelSelect.innerHTML = '';

        if (!brand || models.length === 0) {
            modelSelect.disabled = true;
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Selecionar marca';
            modelSelect.appendChild(placeholder);
            clearAutoFilledFields();
            return;
        }

        modelSelect.disabled = false;

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Selecionar modelo';
        modelSelect.appendChild(placeholder);

        models.forEach((model) => {
            const option = document.createElement('option');
            option.value = model;
            option.textContent = model;
            if (selectedModel === model) {
                option.selected = true;
            }
            modelSelect.appendChild(option);
        });

            // allow adding a custom model
            const addOption = document.createElement('option');
            addOption.value = '__add_model';
            addOption.textContent = 'Adicionar modelo...';
            modelSelect.appendChild(addOption);

            if (selectedModel && !models.includes(selectedModel)) {
            modelSelect.value = '';
        }

        applySpecs(brand, modelSelect.value);
    }

    function setCustomModelSelection() {
        modelSelect.innerHTML = '';

        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.textContent = 'Selecionar marca';
        modelSelect.appendChild(placeholder);

        const addOption = document.createElement('option');
        addOption.value = '__add_model';
        addOption.textContent = 'Adicionar modelo...';
        addOption.selected = true;
        modelSelect.appendChild(addOption);

        modelSelect.disabled = true;
        modelSelect.value = '__add_model';
    }

    function enableCustomModelMode() {
        modelCustom.classList.remove('d-none');
        modelCustom.required = true;
        modelCustom.placeholder = 'Escreva o novo modelo';
        yearInput.readOnly = false;
        engineInput.readOnly = false;
        powerInput.readOnly = false;
        doorsInput.readOnly = false;
        seatsInput.readOnly = false;
        fuelSelect.disabled = false;
        transmissionSelect.disabled = false;
        transmissionSelect.required = true;
        transmissionSelect.setCustomValidity('');
        if (!transmissionSelect.options.length || transmissionSelect.options.length <= 1) {
            transmissionSelect.innerHTML = '';
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Selecionar transmissão';
            transmissionSelect.appendChild(placeholder);

            ['Manual', 'Automática'].forEach((value) => {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = value;
                transmissionSelect.appendChild(option);
            });
        }
        transmissionHidden.value = transmissionSelect.value || '';
    }

    function disableCustomModelMode() {
        modelCustom.classList.add('d-none');
        modelCustom.required = false;
        yearInput.readOnly = true;
        engineInput.readOnly = true;
        powerInput.readOnly = true;
        doorsInput.readOnly = true;
        seatsInput.readOnly = true;
        fuelSelect.disabled = true;
    }

    function updateConditionFromMileage() {
        const mileage = parseInt(mileageInput.value || '0', 10);
        return Number.isNaN(mileage) || mileage <= 10 ? 'Novo' : 'Usado';
    }

    mileageInput.addEventListener('input', updateConditionFromMileage);
    updateConditionFromMileage();

    renderModelOptions(brandSelect.value);

    // initial custom toggles
    if (brandSelect.value === '__add_brand') {
        brandCustom.classList.remove('d-none');
        brandCustom.required = true;
        modelSelect.disabled = true;
        modelCustom.classList.remove('d-none');
        modelCustom.required = true;
        setCustomModelSelection();
        enableCustomModelMode();
    }
    if (modelSelect.value === '__add_model') {
        enableCustomModelMode();
    }

    brandSelect.addEventListener('change', function () {
        if (this.value === '__add_brand') {
            brandCustom.classList.remove('d-none');
            brandCustom.required = true;
            setCustomModelSelection();
            modelCustom.classList.remove('d-none');
            modelCustom.required = true;
            clearAutoFilledFields();
            enableCustomModelMode();
        } else {
            brandCustom.classList.add('d-none');
            brandCustom.required = false;
            modelCustom.classList.add('d-none');
            modelCustom.required = false;
            disableCustomModelMode();
            renderModelOptions(this.value);
        }
    });

    modelSelect.addEventListener('change', function () {
        if (this.value === '__add_model') {
            enableCustomModelMode();
            segmentHidden.value = '';
        } else {
            disableCustomModelMode();
            applySpecs(brandSelect.value, this.value);
        }
    });

    segmentSelect.addEventListener('change', function () {
        if (this.value === '__add_segment') {
            segmentCustom.classList.remove('d-none');
            segmentCustom.required = true;
            segmentCustom.value = '';
            segmentHidden.value = '';
            segmentCustom.focus();
        } else {
            segmentCustom.classList.add('d-none');
            segmentCustom.required = false;
            segmentHidden.value = this.value;
        }
    });

    segmentCustom.addEventListener('input', function () {
        segmentHidden.value = this.value;
    });

    transmissionSelect.addEventListener('change', function () {
        transmissionHidden.value = this.value;
        this.setCustomValidity('');
    });

    if (selectedSegment) {
        const exists = Array.from(segmentSelect.options).some((opt) => opt.value === selectedSegment);
        if (!exists) {
            segmentSelect.value = '__add_segment';
            segmentCustom.classList.remove('d-none');
            segmentCustom.required = true;
            segmentCustom.value = selectedSegment;
            segmentHidden.value = selectedSegment;
        } else {
            segmentSelect.value = selectedSegment;
            segmentHidden.value = selectedSegment;
        }
    } else {
        segmentHidden.value = segmentSelect.value || '';
    }

    if (carForm) {
        carForm.addEventListener('submit', function (event) {
            if (!transmissionSelect.disabled && transmissionSelect.required && !transmissionSelect.value) {
                transmissionSelect.setCustomValidity('Selecione a transmissão');
                transmissionSelect.reportValidity();
                event.preventDefault();
                return;
            }

            transmissionSelect.setCustomValidity('');
        });
    }

    // Handle file input change
    imageInput.addEventListener('change', function (e) {
        newImages = Array.from(e.target.files);
        if (imageUpload && imageInputText) {
            if (newImages.length === 0) {
                imageUpload.classList.add('is-placeholder');
                imageInputText.textContent = 'No file chosen';
            } else {
                imageUpload.classList.remove('is-placeholder');
                imageInputText.textContent = newImages.length === 1 ? '1 file selected' : `${newImages.length} files selected`;
            }
        }
        updateGalleryPreview();
    });

    // Update gallery preview with selected files
    function updateGalleryPreview() {
        // Remove existing preview items (but keep the existing images)
        document.querySelectorAll('.gallery-item[data-existing="false"]').forEach(el => el.remove());

        // Counter to track when all images are loaded
        let loadedCount = 0;
        const totalFiles = newImages.length;

        // Add new preview items
        newImages.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (event) {
                const div = document.createElement('div');
                div.className = 'gallery-item border rounded p-2 position-relative car-image-card';
                div.setAttribute('data-image', 'new_' + index);
                div.setAttribute('data-existing', 'false');
                div.draggable = true;
                div.innerHTML = `
                    <img src="${event.target.result}" alt="Preview" class="img-thumbnail d-block" style="height: 80px; width: auto; cursor: grab;">
                    <small class="d-block text-center mt-1" style="font-size: 10px; color: #28a745;">Novo</small>
                `;
                galleryContainer.appendChild(div);
                loadedCount++;
                
                // Attach listeners once all images are loaded
                if (loadedCount === totalFiles) {
                    attachDragListeners();
                }
            };
            reader.readAsDataURL(file);
        });

        // If no files selected, still call attachDragListeners for existing images
        if (totalFiles === 0) {
            attachDragListeners();
        }
    }

    // Attach drag and drop listeners
    function attachDragListeners() {
        const items = document.querySelectorAll('.gallery-item');
        
        // Remove todos os listeners antigos
        items.forEach(item => {
            const clone = item.cloneNode(true);
            item.parentNode.replaceChild(clone, item);
        });
        
        // Re-select após clonar
        const freshItems = document.querySelectorAll('.gallery-item');
        
        freshItems.forEach(item => {
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragend', handleDragEnd);
            item.addEventListener('dragover', handleDragOver);
            item.addEventListener('drop', handleDrop);
            item.addEventListener('dragenter', handleDragEnter);
            item.addEventListener('dragleave', handleDragLeave);
        });
    }

    function handleDragStart(e) {
        draggedElement = this;
        this.style.opacity = '0.5';
        this.style.cursor = 'grabbing';
        e.dataTransfer.effectAllowed = 'move';
    }

    function handleDragEnd(e) {
        this.style.opacity = '1';
        this.style.cursor = 'grab';
        draggedElement = null;
        
        // Remove visual feedback from all items
        document.querySelectorAll('.gallery-item').forEach(item => {
            item.style.borderColor = '';
            item.style.borderWidth = '';
        });
        
        updateImageOrder();
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    }

    function handleDragEnter(e) {
        if (draggedElement && draggedElement !== this) {
            this.style.borderColor = '#0d6efd';
            this.style.borderWidth = '3px';
        }
    }

    function handleDragLeave(e) {
        this.style.borderColor = '';
        this.style.borderWidth = '';
    }

    function handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (draggedElement && draggedElement !== this) {
            const allItems = Array.from(document.querySelectorAll('.gallery-item'));
            const draggedIndex = allItems.indexOf(draggedElement);
            const thisIndex = allItems.indexOf(this);

            if (draggedIndex < thisIndex) {
                // Moving forward (down/right)
                this.parentNode.insertBefore(draggedElement, this.nextSibling);
            } else {
                // Moving backward (up/left)
                this.parentNode.insertBefore(draggedElement, this);
            }
        }
    }

    // Update the hidden input with image order
    function updateImageOrder() {
        const items = document.querySelectorAll('.gallery-item');
        const order = Array.from(items)
            .map(item => item.getAttribute('data-image'))
            .join(',');
        imageOrderInput.value = order;
    }

    // Update order when removing images
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('car-image-remove-input')) {
            const card = e.target.closest('.car-image-card');
            if (e.target.checked) {
                card.style.opacity = '0.55';
                card.style.borderColor = '#dc3545';
            } else {
                card.style.opacity = '1';
                card.style.borderColor = '';
            }
            updateImageOrder();
        }
    });

    // Set initial order
    updateImageOrder();
    
    // Attach listeners to existing items
    attachDragListeners();

    // Update order when form is submitted
    document.querySelector('form').addEventListener('submit', function () {
        updateImageOrder();
    });
});
</script>
@endsection
