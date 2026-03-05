<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarsSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [
            [
                'title' => 'BMW G82 M4 CS',
                'is_new' => false,
                'segment' => 'Coupé',
                'brand' => 'BMW',
                'model' => 'G82 M4 CS',
                'price' => 207499,
                'mileage' => 4350,
                'engine' => '3.0L I6 TwinTurbo',
                'power' => 550,
                'fuel' => 'Gasolina',
                'transmission' => 'Automática',
                'doors' => 2,
                'seats' => 4,
                'image_path' => 'resources/images/m4cs.jpg',
            ],
            [
                'title' => 'Porsche 992 Turbo S Cabrio',
                'is_new' => false,
                'segment' => 'Cabrio',
                'brand' => 'Porsche',
                'model' => '992 Turbo S',
                'price' => 299599,
                'mileage' => 2730,
                'engine' => '3.8L Boxer',
                'power' => 650,
                'fuel' => 'Gasolina',
                'transmission' => 'PDK Automática',
                'doors' => 2,
                'seats' => 4,
                'image_path' => 'resources/images/911turbos.jpg',
            ],
            [
                'title' => 'Porsche 991.2 GT3 RS',
                'is_new' => false,
                'segment' => 'Coupé',
                'brand' => 'Porsche',
                'model' => '991.2 GT3 RS',
                'price' => 224779,
                'mileage' => 7995,
                'engine' => '4.0L Boxer',
                'power' => 520,
                'fuel' => 'Gasolina',
                'transmission' => 'PDK Automática',
                'doors' => 2,
                'seats' => 2,
                'image_path' => 'resources/images/foto-Album-de-PORSCHE-911-A12817-6650dab8909b2.jpg',
            ],
            [
                'title' => 'Audi RS6 Avant Performance',
                'is_new' => true,
                'segment' => 'Sportbreak',
                'brand' => 'Audi',
                'model' => 'RS6 Avant',
                'price' => 189999,
                'mileage' => 0,
                'engine' => '4.0L V8 Biturbo',
                'power' => 630,
                'fuel' => 'Gasolina',
                'transmission' => 'Automática',
                'doors' => 5,
                'seats' => 5,
                'image_path' => 'resources/images/RS6AP.jpg',
            ],
            [
                'title' => 'Mercedes-Benz GLE 350d',
                'is_new' => false,
                'segment' => 'SUV',
                'brand' => 'Mercedes-Benz',
                'model' => 'GLE 350d',
                'price' => 89999,
                'mileage' => 12200,
                'engine' => '3.0L V6',
                'power' => 272,
                'fuel' => 'Diesel',
                'transmission' => 'Automática',
                'doors' => 5,
                'seats' => 5,
                'image_path' => 'resources/images/gle350d.jpg',
            ],
            [
                'title' => 'BMW F82 M4',
                'is_new' => false,
                'segment' => 'Coupé',
                'brand' => 'BMW',
                'model' => 'F82 M4',
                'price' => 75900,
                'mileage' => 4350,
                'engine' => '3.0L I6 TwinTurbo',
                'power' => 444,
                'fuel' => 'Gasolina',
                'transmission' => 'Manual',
                'doors' => 2,
                'seats' => 5,
                'image_path' => 'resources/images/m4f82.jpg',
            ],
        ];

        foreach ($cars as $car) {
            Car::updateOrCreate(
                ['title' => $car['title']],
                $car
            );
        }
    }
}