<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['client_id', 'vehicle_id', 'start_datetime', 'end_datetime', 'status'])]
class Booking extends Model
{
    use HasFactory;

    /**
     * Get the client that owns the booking.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the vehicle that is booked.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the invoice associated with the booking.
     */
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }
}
