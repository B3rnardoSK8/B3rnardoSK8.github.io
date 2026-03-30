@extends('layouts.back-admin')

@section('title', 'Criar Veiculo')

@section('content')
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-1">Adicionar Veículo</h4>
                <p class="text-muted mb-4">Preencha os dados para adicionar um novo veículo ao catálogo.</p>

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

                <form method="POST" action="{{ route('back.cars.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Titulo *</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Ex.: BMW M3 Competition" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado *</label>
                            <select name="is_new" class="form-select" required>
                                <option value="1" @selected(old('is_new', '0') === '1')>Novo</option>
                                <option value="0" @selected(old('is_new', '0') === '0')>Usado</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Segmento</label>
                            <input type="text" name="segment" class="form-control" value="{{ old('segment') }}" placeholder="Ex.: Sedan">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Marca *</label>
                            <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" placeholder="Ex.: BMW" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modelo *</label>
                            <input type="text" name="model" class="form-control" value="{{ old('model') }}" placeholder="Ex.: M3" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Preço (EUR) *</label>
                            <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price') }}" placeholder="Ex.: 85000" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quilometragem *</label>
                            <input type="number" min="0" name="mileage" class="form-control" value="{{ old('mileage', 0) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Motor</label>
                            <input type="text" name="engine" class="form-control" value="{{ old('engine') }}" placeholder="Ex.: 3.0 TwinPower Turbo">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Potência (cv)</label>
                            <input type="number" min="0" name="power" class="form-control" value="{{ old('power') }}" placeholder="Ex.: 510">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Combustível</label>
                            <input type="text" name="fuel" class="form-control" value="{{ old('fuel') }}" placeholder="Ex.: Gasolina">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Transmissão</label>
                            <input type="text" name="transmission" class="form-control" value="{{ old('transmission') }}" placeholder="Ex.: Automática">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Portas</label>
                            <input type="number" min="1" name="doors" class="form-control" value="{{ old('doors') }}" placeholder="Ex.: 4">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lugares</label>
                            <input type="number" min="1" name="seats" class="form-control" value="{{ old('seats') }}" placeholder="Ex.: 5">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Imagens</label>
                            <input type="file" id="imageInput" name="images[]" class="form-control" accept="image/*" multiple>
                        </div>
                        <div class="col-12 mb-3">
                            <p class="mb-2 text-muted">Galeria de imagens <small>(arraste as imagens para mudar a ordem)</small></p>
                            <input type="hidden" id="imageOrder" name="image_order" value="">
                            <div id="galleryContainer" class="d-flex flex-wrap gap-2" style="min-height: 100px; padding: 8px; border: 1px dashed #ddd; border-radius: 4px; background-color: #f9f9f9;">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Descrição</label>
                            <textarea name="description" rows="4" class="form-control" placeholder="Detalhes, equipamentos, histórico, etc.">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="{{ route('back.cars.index') }}" class="btn btn-light">Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('imageInput');
    const galleryContainer = document.getElementById('galleryContainer');
    const imageOrderInput = document.getElementById('imageOrder');
    let newImages = [];
    let draggedElement = null;

    // Handle file input change
    imageInput.addEventListener('change', function (e) {
        newImages = Array.from(e.target.files);
        updateGalleryPreview();
    });

    // Update gallery preview with selected files
    function updateGalleryPreview() {
        // Clear container
        galleryContainer.innerHTML = '';

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

        // If no files selected, still call attachDragListeners
        if (totalFiles === 0) {
            attachDragListeners();
        }
    }

    // Attach drag and drop listeners
    function attachDragListeners() {
        const items = document.querySelectorAll('.gallery-item');
        
        // Remove all old listeners by cloning
        items.forEach(item => {
            const clone = item.cloneNode(true);
            item.parentNode.replaceChild(clone, item);
        });
        
        // Re-select after cloning
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

    // Update order when form is submitted
    document.querySelector('form').addEventListener('submit', function () {
        updateImageOrder();
    });
});
</script>
