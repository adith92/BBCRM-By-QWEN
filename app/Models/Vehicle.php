<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['plate', 'brand', 'model', 'status'])]
class Vehicle extends Model
{
    use HasFactory;

    /**
     * Get the bookings for the vehicle.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
