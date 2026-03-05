<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
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

    public function show(Car $car)
    {
        return view('cardetails', [
            'car' => $car,
        ]);
    }
}
