<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'phone', 'company', 'address'])]
class Client extends Model
{
    use HasFactory;

    /**
     * Get the bookings for the client.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
