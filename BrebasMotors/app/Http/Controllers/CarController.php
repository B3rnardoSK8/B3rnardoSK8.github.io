<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CarController extends Controller
{
    private const DEFAULT_CAR_IMAGE = 'resources/images/car.png';

    private function normalizeImageReference(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'resources/')) {
            return $path;
        }

        if (str_starts_with($path, 'storage/images/cars/')) {
            return substr($path, strlen('storage/images/cars/'));
        }

        if (str_starts_with($path, 'images/cars/')) {
            return substr($path, strlen('images/cars/'));
        }

        return $path;
    }

    private function ensureAdmin(): void
    {
        abort_unless((int) (Auth::user()?->tipo_id ?? 0) === 1, 403);
    }

    private function normalizeImages(Car $car): array
    {
        $images = is_array($car->images) ? $car->images : [];
        $images = array_map(fn ($image) => $this->normalizeImageReference($image), $images);
        $images = array_values(array_filter($images));

        $mainImage = $this->normalizeImageReference($car->image_path);
        if ($mainImage && !in_array($mainImage, $images, true)) {
            $images[] = $mainImage;
        }

        return array_values(array_unique($images));
    }

    private function storeUploadedImages(array $files, string $title, int $carId, int $startCounter = 1): array
    {
        $uploaded = [];
        $designacao = preg_replace(
            [
                '/(á|à|ã|â|ä)/',
                '/(Á|À|Ã|Â|Ä)/',
                '/(é|è|ê|ë)/',
                '/(É|È|Ê|Ë)/',
                '/(í|ì|î|ï)/',
                '/(Í|Ì|Î|Ï)/',
                '/(ó|ò|õ|ô|ö)/',
                '/(Ó|Ò|Õ|Ô|Ö)/',
                '/(ú|ù|û|ü)/',
                '/(Ú|Ù|Û|Ü)/',
                '/(ñ)/',
                '/(Ñ)/',
            ],
            ['a', 'A', 'e', 'E', 'i', 'I', 'o', 'O', 'u', 'U', 'n', 'N'],
            $title ?: 'carro'
        );
        $designacao = str_replace(' ', '', $designacao);
        if ($designacao === '') {
            $designacao = 'carro';
        }

        $counter = $startCounter;

        foreach ($files as $file) {
            $extension = $file->getClientOriginalExtension();
            $filename = $carId.'_'.$designacao.'_'.$counter.'.'.$extension;
            $file->storeAs('images/cars', $filename, 'public');
            $uploaded[] = $filename;
            $counter++;
        }

        return $uploaded;
    }

    private function deleteStoredImage(string $path): void
    {
        if (str_starts_with($path, 'resources/')) {
            return;
        }

        if (str_starts_with($path, 'storage/')) {
            Storage::disk('public')->delete(substr($path, 8));
            return;
        }

        Storage::disk('public')->delete('images/cars/'.$path);
    }

    private function reorderImages(array $images, string $orderString): array
    {
        if (empty($orderString)) {
            return $images;
        }

        $order = explode(',', $orderString);
        $reordered = [];

        // Processa a ordem enviada
        foreach ($order as $orderItem) {
            $orderItem = trim($orderItem);

            if (str_starts_with($orderItem, 'existing_')) {
                // Imagem existente - encontra no array original
                $index = (int) substr($orderItem, 9);
                if (isset($images[$index])) {
                    $reordered[] = $images[$index];
                }
            } elseif (str_starts_with($orderItem, 'new_')) {
                // Imagens novas permanecem na ordem, já estão adicionadas ao array
            }
        }

        // Adiciona imagens que não estavam na ordem (novas imagens)
        foreach ($images as $image) {
            if (!in_array($image, $reordered, true)) {
                $reordered[] = $image;
            }
        }

        return array_values($reordered);
    }

    public function index(Request $request)
    {
        if ($request->routeIs('back.cars.index')) {
            $this->ensureAdmin();

            $cars = Car::query()
                ->orderByDesc('id')
                ->paginate(15);

            return view('back.cars.index', [
                'cars' => $cars,
            ]);
        }

        $filters = $request->only([
            'condition',
            'segment',
            'brand',
            'model',
            'price_max',
            'mileage_max',
            'engine',
            'power_min',
            'fuel',
            'transmission',
            'doors',
            'seats',
        ]);

        $query = Car::query();

        if ($request->filled('condition')) {
            $query->where('is_new', $request->input('condition') === 'new');
        }

        foreach (['segment', 'brand', 'model', 'engine', 'fuel'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->input($field));
            }
        }

        if ($request->filled('transmission')) {
            $transmission = strtolower($request->input('transmission'));

            if ($transmission === 'manual') {
                $query->whereRaw('LOWER(transmission) LIKE ?', ['%manual%']);
            } else {
                $query->whereRaw('transmission IS NULL OR LOWER(transmission) NOT LIKE ?', ['%manual%']);
            }
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->input('price_max'));
        }

        if ($request->filled('mileage_max')) {
            $query->where('mileage', '<=', (int) $request->input('mileage_max'));
        }

        foreach (['doors', 'seats'] as $numericField) {
            if ($request->filled($numericField)) {
                $query->where($numericField, (int) $request->input($numericField));
            }
        }

        $cars = $query
            ->orderByDesc('price')
            ->paginate(9)
            ->appends($filters);

        $modelOptions = collect();
        if ($request->filled('brand')) {
            $modelOptions = Car::select('model')
                ->where('brand', $request->input('brand'))
                ->distinct()
                ->orderBy('model')
                ->pluck('model');
        }

        $options = [
            'segments' => Car::select('segment')->whereNotNull('segment')->distinct()->orderBy('segment')->pluck('segment'),
            'brands' => Car::select('brand')->distinct()->orderBy('brand')->pluck('brand'),
            'models' => $modelOptions,
            'fuels' => Car::select('fuel')->whereNotNull('fuel')->distinct()->orderBy('fuel')->pluck('fuel'),
            'transmissions' => collect(['Automática', 'Manual']),
            'prices' => collect([50000, 100000, 150000, 250000, 500000]),
            'mileages' => collect([0, 1000, 2500, 5000, 10000, 25000, 50000]),
        ];

        $modelsByBrand = Car::select('brand', 'model')
            ->whereNotNull('brand')
            ->whereNotNull('model')
            ->distinct()
            ->orderBy('brand')
            ->orderBy('model')
            ->get()
            ->groupBy('brand')
            ->map(function ($group) {
                return $group->pluck('model')->values();
            });

        return view('cars', [
            'cars' => $cars,
            'filters' => $filters,
            'options' => $options,
            'modelsByBrand' => $modelsByBrand,
        ]);
    }

    public function create()
    {
        $this->ensureAdmin();

        return view('back.cars.create');
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'is_new' => ['required', 'boolean'],
            'segment' => ['nullable', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'mileage' => ['required', 'integer', 'min:0'],
            'engine' => ['nullable', 'string', 'max:255'],
            'power' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'fuel' => ['nullable', 'string', 'max:255'],
            'transmission' => ['nullable', 'string', 'max:255'],
            'doors' => ['nullable', 'integer', 'min:1', 'max:255'],
            'seats' => ['nullable', 'integer', 'min:1', 'max:255'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'max:4096'],
            'image_order' => ['nullable', 'string'],
        ]);

        $car = new Car();
        $car->title = $request->input('title');
        $car->is_new = (bool) $request->input('is_new');
        $car->segment = $request->input('segment');
        $car->brand = $request->input('brand');
        $car->model = $request->input('model');
        $car->price = $request->input('price');
        $car->mileage = $request->input('mileage');
        $car->engine = $request->input('engine');
        $car->power = $request->input('power');
        $car->fuel = $request->input('fuel');
        $car->transmission = $request->input('transmission');
        $car->doors = $request->input('doors');
        $car->seats = $request->input('seats');
        $car->description = $request->input('description');
        $car->images = [];
        $car->image_path = self::DEFAULT_CAR_IMAGE;

        $car->save();

        $uploadedImages = [];
        if ($request->hasFile('images')) {
            $uploadedImages = $this->storeUploadedImages($request->file('images'), $car->title, $car->id);
            
            // Reordenar imagens baseado na ordem enviada pelo frontend
            if ($request->filled('image_order')) {
                $orderString = $request->input('image_order');
                $orderParts = explode(',', $orderString);
                
                $reorderedImages = [];
                $used = [];
                
                // Processa a ordem desejada (apenas new_X para criar)
                foreach ($orderParts as $orderItem) {
                    if (str_starts_with($orderItem, 'new_')) {
                        $index = (int) substr($orderItem, 4);
                        if (isset($uploadedImages[$index]) && !in_array($index, $used)) {
                            $reorderedImages[] = $uploadedImages[$index];
                            $used[] = $index;
                        }
                    }
                }
                
                // Adiciona imagens não usadas (fallback)
                foreach ($uploadedImages as $idx => $image) {
                    if (!in_array($idx, $used)) {
                        $reorderedImages[] = $image;
                    }
                }
                
                $uploadedImages = array_values($reorderedImages);
            }
            
            $car->images = $uploadedImages;
            $car->image_path = $uploadedImages[0] ?? self::DEFAULT_CAR_IMAGE;
            $car->save();
        }

        return redirect()
            ->route('back.cars.show', $car)
            ->with('status', 'Veículo criado com sucesso.');
    }

    public function show(Request $request, Car $car)
    {
        if ($request->routeIs('back.cars.*')) {
            $this->ensureAdmin();

            return view('back.cars.show', [
                'car' => $car,
            ]);
        }

        return view('cardetails', [
            'car' => $car,
        ]);
    }

    public function edit(Car $car)
    {
        $this->ensureAdmin();

        return view('back.cars.edit', [
            'car' => $car,
        ]);
    }

    public function update(Request $request, Car $car)
    {
        $this->ensureAdmin();

        $existingImages = $this->normalizeImages($car);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'is_new' => ['required', 'boolean'],
            'segment' => ['nullable', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'mileage' => ['required', 'integer', 'min:0'],
            'engine' => ['nullable', 'string', 'max:255'],
            'power' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'fuel' => ['nullable', 'string', 'max:255'],
            'transmission' => ['nullable', 'string', 'max:255'],
            'doors' => ['nullable', 'integer', 'min:1', 'max:255'],
            'seats' => ['nullable', 'integer', 'min:1', 'max:255'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'max:4096'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['string', 'distinct', Rule::in($existingImages)],
            'image_order' => ['nullable', 'string'],
        ]);

        $car->title = $request->input('title');
        $car->is_new = (bool) $request->input('is_new');
        $car->segment = $request->input('segment');
        $car->brand = $request->input('brand');
        $car->model = $request->input('model');
        $car->price = $request->input('price');
        $car->mileage = $request->input('mileage');
        $car->engine = $request->input('engine');
        $car->power = $request->input('power');
        $car->fuel = $request->input('fuel');
        $car->transmission = $request->input('transmission');
        $car->doors = $request->input('doors');
        $car->seats = $request->input('seats');
        $car->description = $request->input('description');

        $imagesToRemove = array_values(array_filter($request->input('remove_images', [])));
        $galleryImages = array_values(array_diff($existingImages, $imagesToRemove));

        foreach ($imagesToRemove as $imageToRemove) {
            $this->deleteStoredImage($imageToRemove);
        }

        if ($request->hasFile('images')) {
            // Se a galeria contém apenas a imagem padrão, removê-la
            if (
                count($galleryImages) === 1
                && ($galleryImages[0] === self::DEFAULT_CAR_IMAGE
                    || str_ends_with($galleryImages[0], 'car.png'))
            ) {
                $galleryImages = [];
            }

            $uploadedImages = $this->storeUploadedImages(
                $request->file('images'),
                $car->title,
                $car->id,
                count($galleryImages) + 1
            );

            // Reordenar imagens baseado na ordem enviada pelo frontend (antes de fazer merge)
            if ($request->filled('image_order')) {
                $orderString = $request->input('image_order');
                $orderParts = explode(',', $orderString);
                
                $reorderedAll = [];
                $existingUsed = [];
                $newUsed = [];
                
                // Processa a ordem desejada
                foreach ($orderParts as $orderItem) {
                    if (str_starts_with($orderItem, 'existing_')) {
                        // Imagem existente
                        $index = (int) substr($orderItem, 9);
                        if (isset($galleryImages[$index]) && !in_array($index, $existingUsed)) {
                            $reorderedAll[] = $galleryImages[$index];
                            $existingUsed[] = $index;
                        }
                    } elseif (str_starts_with($orderItem, 'new_')) {
                        // Imagem nova
                        $index = (int) substr($orderItem, 4);
                        if (isset($uploadedImages[$index]) && !in_array($index, $newUsed)) {
                            $reorderedAll[] = $uploadedImages[$index];
                            $newUsed[] = $index;
                        }
                    }
                }
                
                // Adiciona imagens não usadas (fallback)
                foreach ($galleryImages as $idx => $image) {
                    if (!in_array($idx, $existingUsed)) {
                        $reorderedAll[] = $image;
                    }
                }
                foreach ($uploadedImages as $idx => $image) {
                    if (!in_array($idx, $newUsed)) {
                        $reorderedAll[] = $image;
                    }
                }
                
                $galleryImages = array_values($reorderedAll);
            } else {
                $galleryImages = array_values(array_merge($galleryImages, $uploadedImages));
            }
        }

        $car->images = $galleryImages;
        $car->image_path = $galleryImages[0] ?? self::DEFAULT_CAR_IMAGE;

        $car->save();

        return redirect()
            ->route('back.cars.show', $car)
            ->with('status', 'Veículo atualizado com sucesso.');
    }

    public function destroy(Car $car)
    {
        $this->ensureAdmin();

        $images = $this->normalizeImages($car);
        foreach ($images as $image) {
            $this->deleteStoredImage($image);
        }

        $car->delete();

        return redirect()
            ->route('back.cars.index')
            ->with('status', 'Veículo removido com sucesso.');
    }
}
