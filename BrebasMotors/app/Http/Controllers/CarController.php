<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use App\Notifications\CarPriceChangedNotification;
use App\Notifications\CarSoldNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CarController extends Controller
{
    private const DEFAULT_CAR_IMAGE = 'resources/images/car.png';
    private const FAVORITES_MAIL_DELAY_SECONDS = 8;

    private function carCatalogOptions(): array
    {
        return [
            'segments' => [
                'Coupé',
                'Cabrio',
                'Hatchback',
                'Roadster',
                'Sedan',
                'Sportbreak',
                'SUV',
            ],
            'brands' => [
                'Audi',
                'Aston Martin',
                'Bentley',
                'BMW',
                'Ferrari',
                'Lamborghini',
                'Land Rover',
                'Maserati',
                'McLaren',
                'Mercedes-Benz',
                'Porsche',
                'Tesla',
            ],
            'fuelOptions' => [
                'Gasolina',
                'Diesel',
                'Híbrido',
                'Elétrico',
            ],
            'modelsByBrand' => [
                'Audi' => [
                    'A4 Avant',
                    'R8 V10',
                    'RS6 Avant',
                    'RS7 Sportback',
                    'RS Q8',
                ],
                'Aston Martin' => [
                    'DB11',
                    'DBS Superleggera',
                    'Vantage',
                ],
                'Bentley' => [
                    'Bentayga',
                'fuelOptions' => [
                    'Gasolina',
                    'Diesel',
                    'Híbrido',
                    'Elétrico',
                ],
                    'Continental GT',
                    'Flying Spur',
                ],
                'BMW' => [
                    'F82 M4',
                    'G82 M4 CS',
                    'M3 Competition',
                    'M5 Competition',
                    'X5 M',
                ],
                'Ferrari' => [
                    '296 GTB',
                    'F8 Tributo',
                    'Roma',
                ],
                'Lamborghini' => [
                    'Aventador',
                    'Huracán',
                    'Revuelto',
                    'Urus',
                ],
                'Land Rover' => [
                    'Defender',
                    'Range Rover Sport',
                    'Range Rover Vogue',
                ],
                'Maserati' => [
                    'Ghibli',
                    'GranTurismo',
                    'Levante',
                ],
                'McLaren' => [
                    'Artura',
                    '720S',
                    '765LT',
                ],
                'Mercedes-Benz' => [
                    'AMG GT',
                    'C 63 S',
                    'E 63 S',
                    'GLE 350d',
                    'G 63',
                ],
                'Porsche' => [
                    '718 Cayman GT4',
                    '911 Carrera',
                    '991.2 GT3 RS',
                    '992 Turbo S',
                    'Cayenne Turbo',
                ],
                'Tesla' => [
                    'Model 3',
                    'Model S',
                    'Model X',
                    'Model Y',
                ],
            ],
            'specsByBrandModel' => $this->carModelSpecifications(),
        ];
    }

    private function carModelSpecifications(): array
    {
        return [
            'Audi' => [
                'A4 Avant' => [
                    'year' => 2024,
                    'segment' => 'Sportbreak',
                    'engine' => '2.0 TFSI',
                    'power' => 204,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Manual',
                    'transmissionOptions' => ['Manual', 'Automática'],
                    'doors' => 5,
                    'seats' => 5,
                ],
                'R8 V10' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '5.2 V10 FSI',
                    'power' => 620,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 2,
                    'seats' => 2,
                ],
                'RS6 Avant' => [
                    'year' => 2024,
                    'segment' => 'Sportbreak',
                    'engine' => '4.0 V8 Biturbo',
                    'power' => 630,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 5,
                    'seats' => 5,
                ],
                'RS7 Sportback' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '4.0 V8 Biturbo',
                    'power' => 630,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 5,
                    'seats' => 5,
                ],
                'RS Q8' => [
                    'year' => 2024,
                    'segment' => 'SUV',
                    'engine' => '4.0 V8 Biturbo',
                    'power' => 600,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 5,
                    'seats' => 5,
                ],
            ],
            'Aston Martin' => [
                'DB11' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '4.0 V8 Biturbo',
                    'power' => 528,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 2,
                    'seats' => 4,
                ],
                'DBS Superleggera' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '5.2 V12 Biturbo',
                    'power' => 725,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 2,
                    'seats' => 4,
                ],
                'Vantage' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '4.0 V8 Biturbo',
                    'power' => 665,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 2,
                    'seats' => 2,
                ],
            ],
            'Bentley' => [
                'Bentayga' => [
                    'year' => 2024,
                    'segment' => 'SUV',
                    'engine' => '4.0 V8 Twin Turbo',
                    'power' => 550,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 5,
                    'seats' => 5,
                ],
                'Continental GT' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '4.0 V8 Twin Turbo',
                    'power' => 550,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 2,
                    'seats' => 4,
                ],
                'Flying Spur' => [
                    'year' => 2024,
                    'segment' => 'Sedan',
                    'engine' => '4.0 V8 Twin Turbo',
                    'power' => 550,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 4,
                    'seats' => 5,
                ],
            ],
            'BMW' => [
                'F82 M4' => [
                    'year' => 2018,
                    'segment' => 'Coupé',
                    'engine' => '3.0 I6 Twin Turbo',
                    'power' => 431,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Manual',
                    'transmissionOptions' => ['Manual', 'Automática'],
                    'doors' => 2,
                    'seats' => 4,
                ],
                'G82 M4 CS' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '3.0 I6 Twin Turbo',
                    'power' => 551,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 2,
                    'seats' => 4,
                ],
                'M3 Competition' => [
                    'year' => 2024,
                    'segment' => 'Sedan',
                    'engine' => '3.0 I6 Twin Turbo',
                    'power' => 510,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 4,
                    'seats' => 5,
                ],
                'M5 Competition' => [
                    'year' => 2024,
                    'segment' => 'Sedan',
                    'engine' => '4.4 V8 Twin Turbo',
                    'power' => 625,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 4,
                    'seats' => 5,
                ],
                'X5 M' => [
                    'year' => 2024,
                    'segment' => 'SUV',
                    'engine' => '4.4 V8 Twin Turbo',
                    'power' => 625,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 5,
                    'seats' => 5,
                ],
            ],
            'Ferrari' => [
                '296 GTB' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '3.0 V6 Hybrid',
                    'power' => 830,
                    'fuel' => 'Híbrido',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 2,
                    'seats' => 2,
                ],
                'F8 Tributo' => [
                    'year' => 2023,
                    'segment' => 'Coupé',
                    'engine' => '3.9 V8 Twin Turbo',
                    'power' => 720,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 2,
                    'seats' => 2,
                ],
                'Roma' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '3.9 V8 Twin Turbo',
                    'power' => 620,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 2,
                    'seats' => 4,
                ],
            ],
            'Lamborghini' => [
                'Aventador' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '6.5 V12',
                    'power' => 740,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 2,
                    'seats' => 2,
                ],
                'Huracán' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '5.2 V10',
                    'power' => 610,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 2,
                    'seats' => 2,
                ],
                'Revuelto' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '6.5 V12 Hybrid',
                    'power' => 1015,
                    'fuel' => 'Híbrido',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 2,
                    'seats' => 2,
                ],
                'Urus' => [
                    'year' => 2024,
                    'segment' => 'SUV',
                    'engine' => '4.0 V8 Twin Turbo',
                    'power' => 650,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Automática'],
                    'doors' => 5,
                    'seats' => 5,
                ],
            ],
            'Land Rover' => [
                'Defender' => [
                    'year' => 2024,
                    'segment' => 'SUV',
                    'engine' => '3.0 I6 Diesel',
                    'power' => 300,
                    'fuel' => 'Diesel',
                    'transmission' => 'Automática',
                    'doors' => 5,
                    'seats' => 5,
                ],
                'Range Rover Sport' => [
                    'year' => 2024,
                    'segment' => 'SUV',
                    'engine' => '3.0 I6 Mild-Hybrid',
                    'power' => 400,
                    'fuel' => 'Híbrido',
                    'transmission' => 'Automática',
                    'doors' => 5,
                    'seats' => 5,
                ],
                'Range Rover Vogue' => [
                    'year' => 2024,
                    'segment' => 'SUV',
                    'engine' => '3.0 I6 Mild-Hybrid',
                    'power' => 400,
                    'fuel' => 'Híbrido',
                    'transmission' => 'Automática',
                    'doors' => 5,
                    'seats' => 5,
                ],
            ],
            'Maserati' => [
                'Ghibli' => [
                    'year' => 2024,
                    'segment' => 'Sedan',
                    'engine' => '3.0 V6 Twin Turbo',
                    'power' => 350,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'doors' => 4,
                    'seats' => 5,
                ],
                'GranTurismo' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '3.0 V6 Twin Turbo',
                    'power' => 490,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'doors' => 2,
                    'seats' => 4,
                ],
                'Levante' => [
                    'year' => 2024,
                    'segment' => 'SUV',
                    'engine' => '3.0 V6 Twin Turbo',
                    'power' => 350,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'doors' => 5,
                    'seats' => 5,
                ],
            ],
            'McLaren' => [
                'Artura' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '3.0 V6 Hybrid',
                    'power' => 680,
                    'fuel' => 'Híbrido',
                    'transmission' => 'Automática',
                    'doors' => 2,
                    'seats' => 2,
                ],
                '720S' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '4.0 V8 Twin Turbo',
                    'power' => 720,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'doors' => 2,
                    'seats' => 2,
                ],
                '765LT' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '4.0 V8 Twin Turbo',
                    'power' => 765,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'doors' => 2,
                    'seats' => 2,
                ],
            ],
            'Mercedes-Benz' => [
                'AMG GT' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '4.0 V8 Biturbo',
                    'power' => 585,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'doors' => 2,
                    'seats' => 2,
                ],
                'C 63 S' => [
                    'year' => 2024,
                    'segment' => 'Sedan',
                    'engine' => '4.0 V8 Biturbo',
                    'power' => 510,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'doors' => 4,
                    'seats' => 5,
                ],
                'E 63 S' => [
                    'year' => 2024,
                    'segment' => 'Sedan',
                    'engine' => '4.0 V8 Biturbo',
                    'power' => 612,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'doors' => 4,
                    'seats' => 5,
                ],
                'GLE 350d' => [
                    'year' => 2024,
                    'segment' => 'SUV',
                    'engine' => '3.0 V6 Turbo Diesel',
                    'power' => 272,
                    'fuel' => 'Diesel',
                    'transmission' => 'Automática',
                    'doors' => 5,
                    'seats' => 5,
                ],
                'G 63' => [
                    'year' => 2024,
                    'segment' => 'SUV',
                    'engine' => '4.0 V8 Biturbo',
                    'power' => 585,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'doors' => 5,
                    'seats' => 5,
                ],
            ],
            'Porsche' => [
                '718 Cayman GT4' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '4.0 Flat-6',
                    'power' => 420,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Manual',
                    'doors' => 2,
                    'seats' => 2,
                ],
                '911 Carrera' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '3.0 Flat-6 Twin Turbo',
                    'power' => 385,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'transmissionOptions' => ['Manual', 'Automática'],
                    'doors' => 2,
                    'seats' => 4,
                ],
                '991.2 GT3 RS' => [
                    'year' => 2019,
                    'segment' => 'Coupé',
                    'engine' => '4.0 Flat-6',
                    'power' => 520,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'doors' => 2,
                    'seats' => 2,
                ],
                '992 Turbo S' => [
                    'year' => 2024,
                    'segment' => 'Coupé',
                    'engine' => '3.8 Flat-6 Twin Turbo',
                    'power' => 650,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'doors' => 2,
                    'seats' => 4,
                ],
                'Cayenne Turbo' => [
                    'year' => 2024,
                    'segment' => 'SUV',
                    'engine' => '4.0 V8 Twin Turbo',
                    'power' => 550,
                    'fuel' => 'Gasolina',
                    'transmission' => 'Automática',
                    'doors' => 5,
                    'seats' => 5,
                ],
            ],
            'Tesla' => [
                'Model 3' => [
                    'year' => 2024,
                    'segment' => 'Sedan',
                    'engine' => 'Dual Motor Electric',
                    'power' => 513,
                    'fuel' => 'Elétrico',
                    'transmission' => 'Automática',
                    'doors' => 4,
                    'seats' => 5,
                ],
                'Model S' => [
                    'year' => 2024,
                    'segment' => 'Sedan',
                    'engine' => 'Dual Motor Electric',
                    'power' => 670,
                    'fuel' => 'Elétrico',
                    'transmission' => 'Automática',
                    'doors' => 4,
                    'seats' => 5,
                ],
                'Model X' => [
                    'year' => 2024,
                    'segment' => 'SUV',
                    'engine' => 'Dual Motor Electric',
                    'power' => 670,
                    'fuel' => 'Elétrico',
                    'transmission' => 'Automática',
                    'doors' => 5,
                    'seats' => 5,
                ],
                'Model Y' => [
                    'year' => 2024,
                    'segment' => 'SUV',
                    'engine' => 'Dual Motor Electric',
                    'power' => 533,
                    'fuel' => 'Elétrico',
                    'transmission' => 'Automática',
                    'doors' => 5,
                    'seats' => 5,
                ],
            ],
        ];
    }

    private function modelsForBrand(?string $brand): array
    {
        $catalogOptions = $this->carCatalogOptions();

        return $catalogOptions['modelsByBrand'][$brand] ?? [];
    }

    private function modelSpecifications(?string $brand, ?string $model): array
    {
        $catalogOptions = $this->carCatalogOptions();

        return $catalogOptions['specsByBrandModel'][$brand][$model] ?? [];
    }

    private function transmissionOptionsForModel(?string $brand, ?string $model): array
    {
        $specs = $this->modelSpecifications($brand, $model);
        $options = $specs['transmissionOptions'] ?? [];

        if (!is_array($options) || $options === []) {
            $fallback = $specs['transmission'] ?? null;
            return is_string($fallback) && $fallback !== '' ? [$fallback] : [];
        }

        return array_values(array_unique(array_filter($options, 'is_string')));
    }

    private function fuelOptions(): array
    {
        return [
            'Gasolina',
            'Diesel',
            'Híbrido',
            'Elétrico',
        ];
    }

    private function buildCarTitle(string $brand, string $model): string
    {
        return trim($brand.' '.$model);
    }

    private function notifyFavoriteUsersAboutPriceChange(Car $car, float $oldPrice, float $newPrice): void
    {
        $recipients = $car->favoritedByUsers()
            ->whereNotNull('users.email')
            ->get();

        foreach ($recipients as $index => $recipient) {
            try {
                $delaySeconds = $index * self::FAVORITES_MAIL_DELAY_SECONDS;

                $recipient->notify(
                    (new CarPriceChangedNotification($car, $oldPrice, $newPrice))
                        ->onQueue('mail')
                        ->delay(now()->addSeconds($delaySeconds))
                );
            } catch (\Throwable $exception) {
                Log::warning('Falha ao enviar notificacao de alteracao de preco.', [
                    'car_id' => $car->id,
                    'user_id' => $recipient->id,
                    'error' => $exception->getMessage(),
                ]);
            }
        }
    }

    private function notifyFavoriteUsersAboutSale(Car $car): void
    {
        $recipients = $car->favoritedByUsers()
            ->whereNotNull('users.email')
            ->get();

        foreach ($recipients as $index => $recipient) {
            try {
                $delaySeconds = $index * self::FAVORITES_MAIL_DELAY_SECONDS;

                $recipient->notify(
                    (new CarSoldNotification($car))
                        ->onQueue('mail')
                        ->delay(now()->addSeconds($delaySeconds))
                );
            } catch (\Throwable $exception) {
                Log::warning('Falha ao enviar notificacao de venda.', [
                    'car_id' => $car->id,
                    'user_id' => $recipient->id,
                    'error' => $exception->getMessage(),
                ]);
            }
        }
    }

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

        foreach ($order as $orderItem) {
            $orderItem = trim($orderItem);

            if (str_starts_with($orderItem, 'existing_')) {
                $index = (int) substr($orderItem, 9);

                if (isset($images[$index])) {
                    $reordered[] = $images[$index];
                }

                continue;
            }

            if (str_starts_with($orderItem, 'new_')) {
                continue;
            }
        }

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
            'year',
            'price_max',
            'mileage_max',
            'engine',
            'power_min',
            'fuel',
            'transmission',
            'doors',
            'seats',
            'sort_by',
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

        if ($request->filled('year')) {
            $query->where('year', (int) $request->input('year'));
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

        $sortBy = $request->input('sort_by', 'price_desc');

        switch ($sortBy) {
            case 'year_asc':
                $query->orderBy('year')->orderByDesc('id');
                break;
            case 'year_desc':
                $query->orderByDesc('year')->orderByDesc('id');
                break;
            case 'price_asc':
                $query->orderBy('price')->orderByDesc('id');
                break;
            case 'mileage_asc':
                $query->orderBy('mileage')->orderByDesc('id');
                break;
            case 'mileage_desc':
                $query->orderByDesc('mileage')->orderByDesc('id');
                break;
            case 'price_desc':
            default:
                $query->orderByDesc('price')->orderByDesc('id');
                break;
        }

        $cars = $query
            ->paginate(9)
            ->appends($filters);

        $modelOptions = collect();
        if ($request->filled('brand')) {
            $catalogOptions = $this->carCatalogOptions();
            $modelOptions = collect($catalogOptions['modelsByBrand'][$request->input('brand')] ?? []);
        }

        $options = [
            'segments' => collect($this->carCatalogOptions()['segments']),
            'brands' => collect($this->carCatalogOptions()['brands']),
            'models' => $modelOptions,
            'years' => Car::select('year')->whereNotNull('year')->distinct()->orderByDesc('year')->pluck('year'),
            'fuels' => collect($this->carCatalogOptions()['fuelOptions']),
            'transmissions' => collect(['Automática', 'Manual']),
            'prices' => collect([50000, 100000, 150000, 250000, 500000]),
            'mileages' => collect([0, 1000, 2500, 5000, 10000, 25000, 50000]),
        ];

        $modelsByBrand = collect($this->carCatalogOptions()['modelsByBrand']);

        $favoriteCarIds = [];
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            $favoriteCarIds = $user->favoriteCars()->pluck('cars.id')->all();
        }

        return view('cars', [
            'cars' => $cars,
            'filters' => $filters,
            'options' => $options,
            'modelsByBrand' => $modelsByBrand,
            'favoriteCarIds' => $favoriteCarIds,
        ]);
    }

    public function create()
    {
        $this->ensureAdmin();

        return view('back.cars.create', [
            'catalogOptions' => $this->carCatalogOptions(),
        ]);
    }

    public function highlights()
    {
        $this->ensureAdmin();

        $cars = Car::query()
            ->orderByDesc('is_featured')
            ->orderByRaw('featured_order IS NULL')
            ->orderBy('featured_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('back.cars.highlights', [
            'cars' => $cars,
            'featuredIds' => Car::query()
                ->where('is_featured', true)
                ->orderByRaw('featured_order IS NULL')
                ->orderBy('featured_order')
                ->orderByDesc('id')
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all(),
        ]);
    }

    public function updateHighlights(Request $request)
    {
        $this->ensureAdmin();

        $data = $request->validate([
            'featured_ids' => ['required', 'array', 'size:3'],
            'featured_ids.*' => ['required', 'integer', 'distinct', 'exists:cars,id'],
            'featured_order' => ['nullable', 'string'],
        ]);

        $featuredIds = collect($data['featured_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->take(3)
            ->values();

        $requestedOrder = collect(explode(',', (string) ($data['featured_order'] ?? '')))
            ->filter(fn ($id) => $id !== '')
            ->map(fn ($id) => (int) $id)
            ->unique();

        $orderedFeaturedIds = $requestedOrder
            ->filter(fn ($id) => $featuredIds->contains($id))
            ->concat($featuredIds->diff($requestedOrder))
            ->take(3)
            ->values();

        Car::query()->update([
            'is_featured' => false,
            'featured_order' => null,
        ]);

        if ($orderedFeaturedIds->isNotEmpty()) {
            foreach ($orderedFeaturedIds as $index => $carId) {
                Car::query()
                    ->where('id', $carId)
                    ->update([
                        'is_featured' => true,
                        'featured_order' => $index + 1,
                    ]);
            }
        }

        return redirect()
            ->route('back.cars.highlights')
            ->with('status', 'Destaques atualizados com sucesso.');
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $maxYear = (int) date('Y') + 1;
        $catalogOptions = $this->carCatalogOptions();
        $selectedBrand = $request->input('brand');
        $allowedModels = $this->modelsForBrand(is_string($selectedBrand) ? $selectedBrand : null);
        $selectedModel = $request->input('model');
        $allowedTransmissions = $this->transmissionOptionsForModel(
            is_string($selectedBrand) ? $selectedBrand : null,
            is_string($selectedModel) ? $selectedModel : null
        );

        $brandIsCustom = $request->input('brand') === '__add_brand';
        $modelIsCustom = $request->input('model') === '__add_model' || $request->filled('model_custom') || $brandIsCustom;

        $brandRule = $brandIsCustom ? ['required', 'string', 'max:255'] : ['required', Rule::in($catalogOptions['brands'])];
        $modelRule = $modelIsCustom ? ['required', 'string', 'max:255'] : ['required', Rule::in($allowedModels)];

        $fuelOptions = $this->fuelOptions();
        $transmissionRule = $modelIsCustom
            ? ['required', Rule::in(['Manual', 'Automática'])]
            : ['required', Rule::in($allowedTransmissions)];

        $request->validate([
            'segment' => ['required', Rule::in($catalogOptions['segments'])],
            'brand' => $brandRule,
            'brand_custom' => $brandIsCustom ? ['required', 'string', 'max:255'] : ['nullable'],
            'model' => $modelRule,
            'model_custom' => $modelIsCustom ? ['required', 'string', 'max:255'] : ['nullable'],
            'year' => ['required', 'integer', 'min:1900', 'max:'.$maxYear],
            'price' => ['required', 'numeric', 'min:0'],
            'mileage' => ['required', 'integer', 'min:0'],
            'engine' => ['required', 'string', 'max:255'],
            'power' => ['required', 'integer', 'min:0', 'max:65535'],
            'fuel' => ['required', Rule::in($fuelOptions)],
            'transmission' => $transmissionRule,
            'doors' => ['required', 'integer', 'min:1', 'max:255'],
            'seats' => ['required', 'integer', 'min:1', 'max:255'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'max:4096'],
            'image_order' => ['nullable', 'string'],
        ]);

        $mileage = (int) $request->input('mileage');
        $brand = $brandIsCustom ? (string) $request->input('brand_custom') : (string) $request->input('brand');
        $model = $modelIsCustom ? (string) $request->input('model_custom') : (string) $request->input('model');
        $specs = $this->modelSpecifications($brand, $model);

        $car = new Car();
        $car->title = $this->buildCarTitle($brand, $model);
        $car->is_new = $mileage === 0;
        $car->segment = $specs['segment'] ?? $request->input('segment');
        $car->brand = $brand;
        $car->model = $model;
        $car->year = $specs['year'] ?? $request->input('year');
        $car->price = $request->input('price');
        $car->mileage = $mileage;
        $car->engine = $specs['engine'] ?? $request->input('engine');
        $car->power = $specs['power'] ?? $request->input('power');
        $car->fuel = $specs['fuel'] ?? $request->input('fuel');
        $car->transmission = $specs['transmission'] ?? $request->input('transmission');
        $car->doors = $specs['doors'] ?? $request->input('doors');
        $car->seats = $specs['seats'] ?? $request->input('seats');
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

        $isFavorite = false;
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            $isFavorite = $user->favoriteCars()
                ->where('cars.id', $car->id)
                ->exists();
        }

        return view('cardetails', [
            'car' => $car,
            'isFavorite' => $isFavorite,
        ]);
    }

    public function edit(Car $car)
    {
        $this->ensureAdmin();

        return view('back.cars.edit', [
            'car' => $car,
            'catalogOptions' => $this->carCatalogOptions(),
        ]);
    }

    public function toggleAvailability(Car $car)
    {
        $this->ensureAdmin();

        $wasSold = (bool) $car->is_sold;
        $car->is_sold = !$wasSold;
        $car->save();

        if (!$wasSold && $car->is_sold) {
            $this->notifyFavoriteUsersAboutSale($car);
        }

        return redirect()
            ->route('back.cars.index')
            ->with('status', 'Disponibilidade do veículo atualizada com sucesso.');
    }

    public function update(Request $request, Car $car)
    {
        $this->ensureAdmin();

        $oldPrice = (float) $car->price;
        $wasSold = (bool) $car->is_sold;

        $maxYear = (int) date('Y') + 1;
        $catalogOptions = $this->carCatalogOptions();
        $selectedBrand = $request->input('brand');
        $allowedModels = $this->modelsForBrand(is_string($selectedBrand) ? $selectedBrand : null);
        $selectedModel = $request->input('model');
        $allowedTransmissions = $this->transmissionOptionsForModel(
            is_string($selectedBrand) ? $selectedBrand : null,
            is_string($selectedModel) ? $selectedModel : null
        );

        $existingImages = $this->normalizeImages($car);

        $brandIsCustom = $request->input('brand') === '__add_brand';
        $modelIsCustom = $request->input('model') === '__add_model' || $request->filled('model_custom') || $brandIsCustom;

        $brandRule = $brandIsCustom ? ['required', 'string', 'max:255'] : ['required', Rule::in($catalogOptions['brands'])];
        $modelRule = $modelIsCustom ? ['required', 'string', 'max:255'] : ['required', Rule::in($allowedModels)];
        $fuelOptions = $this->fuelOptions();
        $transmissionRule = $modelIsCustom
            ? ['required', Rule::in(['Manual', 'Automática'])]
            : ['required', Rule::in($allowedTransmissions)];

        $request->validate([
            'segment' => ['required', Rule::in($catalogOptions['segments'])],
            'brand' => $brandRule,
            'brand_custom' => $brandIsCustom ? ['required', 'string', 'max:255'] : ['nullable'],
            'model' => $modelRule,
            'model_custom' => $modelIsCustom ? ['required', 'string', 'max:255'] : ['nullable'],
            'year' => ['required', 'integer', 'min:1900', 'max:'.$maxYear],
            'price' => ['required', 'numeric', 'min:0'],
            'mileage' => ['required', 'integer', 'min:0'],
            'engine' => ['required', 'string', 'max:255'],
            'power' => ['required', 'integer', 'min:0', 'max:65535'],
            'fuel' => ['required', Rule::in($fuelOptions)],
            'transmission' => $transmissionRule,
            'doors' => ['required', 'integer', 'min:1', 'max:255'],
            'seats' => ['required', 'integer', 'min:1', 'max:255'],
            'is_sold' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'max:4096'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['string', 'distinct', Rule::in($existingImages)],
            'image_order' => ['nullable', 'string'],
        ]);

        $mileage = (int) $request->input('mileage');
        $brand = $brandIsCustom ? (string) $request->input('brand_custom') : (string) $request->input('brand');
        $model = $modelIsCustom ? (string) $request->input('model_custom') : (string) $request->input('model');
        $specs = $this->modelSpecifications($brand, $model);

        $car->title = $this->buildCarTitle($brand, $model);
        $car->is_new = $mileage === 0;
        $car->segment = $specs['segment'] ?? $request->input('segment');
        $car->brand = $brand;
        $car->model = $model;
        $car->year = $specs['year'] ?? $request->input('year');
        $car->price = $request->input('price');
        $car->mileage = $mileage;
        $car->engine = $specs['engine'] ?? $request->input('engine');
        $car->power = $specs['power'] ?? $request->input('power');
        $car->fuel = $specs['fuel'] ?? $request->input('fuel');
        $car->transmission = $specs['transmission'] ?? $request->input('transmission');
        $car->doors = $specs['doors'] ?? $request->input('doors');
        $car->seats = $specs['seats'] ?? $request->input('seats');
        $car->is_sold = (bool) $request->input('is_sold', 0);
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

        $newPrice = (float) $car->price;
        if (abs($newPrice - $oldPrice) > 0.00001) {
            $this->notifyFavoriteUsersAboutPriceChange($car, $oldPrice, $newPrice);
        }

        if (!$wasSold && $car->is_sold) {
            $this->notifyFavoriteUsersAboutSale($car);
        }

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
