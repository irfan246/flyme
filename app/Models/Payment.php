<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['booking_id', 'method', 'amount', 'status', 'proof_path', 'paid_at', 'confirmed_by', 'confirmed_at'])]
class Payment extends Model
{
    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
