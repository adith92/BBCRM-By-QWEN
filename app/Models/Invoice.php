<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['booking_id', 'total_amount', 'remaining_balance', 'status'])]
class Invoice extends Model
{
    use HasFactory;

    /**
     * Get the booking associated with the invoice.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the payments for the invoice.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
