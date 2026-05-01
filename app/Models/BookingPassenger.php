<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['booking_id', 'name', 'identity_number', 'gender', 'birth_date'])]
class BookingPassenger extends Model
{
    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
