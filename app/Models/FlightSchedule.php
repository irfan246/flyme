<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['aircraft_id', 'route_id', 'flight_number', 'departure_time', 'arrival_time', 'status'])]
class FlightSchedule extends Model
{
    protected function casts(): array
    {
        return [
            'departure_time' => 'datetime',
            'arrival_time' => 'datetime',
        ];
    }

    public function aircraft(): BelongsTo
    {
        return $this->belongsTo(Aircraft::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(FlightRoute::class, 'route_id');
    }

    public function ticketPrices(): HasMany
    {
        return $this->hasMany(TicketPrice::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
