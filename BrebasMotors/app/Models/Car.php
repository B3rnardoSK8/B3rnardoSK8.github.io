<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
        'is_sold',
        'is_reserved',
        'reserved_until',
        'reserved_email',
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
        'is_sold' => 'boolean',
        'is_reserved' => 'boolean',
        'reserved_until' => 'datetime',
        'reserved_email' => 'string',
        'featured_order' => 'integer',
        'year' => 'integer',
        'price' => 'decimal:2',
        'mileage' => 'integer',
        'power' => 'integer',
        'doors' => 'integer',
        'seats' => 'integer',
        'images' => 'array',
    ];

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_cars')
            ->withTimestamps();
    }

    public function isCurrentlyReserved(): bool
    {
        return $this->is_reserved && 
               $this->reserved_until && 
               $this->reserved_until->isFuture();
    }

    public function cancelReservation(): void
    {
        $this->is_reserved = false;
        $this->reserved_until = null;
        $this->reserved_email = null;
        $this->save();
    }
}
