<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'is_new',
        'is_featured',
        'featured_order',
        'segment',
        'brand',
        'model',
        'year',
        'price',
        'mileage',
        'engine',
        'power',
        'fuel',
        'transmission',
        'doors',
        'seats',
        'image_path',
        'images',
        'description',
    ];

    protected $casts = [
        'is_new' => 'boolean',
        'is_featured' => 'boolean',
        'featured_order' => 'integer',
        'year' => 'integer',
        'price' => 'decimal:2',
        'mileage' => 'integer',
        'power' => 'integer',
        'doors' => 'integer',
        'seats' => 'integer',
        'images' => 'array',
    ];
}
