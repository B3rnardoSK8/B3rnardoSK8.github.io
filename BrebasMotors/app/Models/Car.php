<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'is_new',
        'segment',
        'brand',
        'model',
        'price',
        'mileage',
        'engine',
        'power',
        'fuel',
        'transmission',
        'doors',
        'seats',
        'image_path',
        'description',
    ];

    protected $casts = [
        'is_new' => 'boolean',
        'price' => 'decimal:2',
        'mileage' => 'integer',
        'power' => 'integer',
        'doors' => 'integer',
        'seats' => 'integer',
    ];
}
