<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['user_id', 'flight_schedule_id', 'return_flight_schedule_id', 'ticket_class_id', 'created_by', 'booking_code', 'customer_name', 'customer_email', 'customer_phone', 'passenger_count', 'subtotal', 'discount', 'total_amount', 'status', 'source', 'trip_type', 'expires_at', 'confirmed_at'])]
class Booking extends Model
{
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'confirmed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function flightSchedule(): BelongsTo
    {
        return $this->belongsTo(FlightSchedule::class);
    }

    public function returnFlightSchedule(): BelongsTo
    {
        return $this->belongsTo(FlightSchedule::class, 'return_flight_schedule_id');
    }

    public function ticketClass(): BelongsTo
    {
        return $this->belongsTo(TicketClass::class);
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(BookingPassenger::class);
    }

    public function bookingSeats(): HasMany
    {
        return $this->hasMany(BookingSeat::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function isExpired(): bool
    {
        return $this->status === 'pending' && $this->expires_at !== null && $this->expires_at->isPast();
    }
}
