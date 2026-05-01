<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['origin_airport_id', 'destination_airport_id', 'code', 'distance_km', 'duration_minutes', 'is_active'])]
class FlightRoute extends Model
{
    protected $table = 'routes';

    public function originAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'origin_airport_id');
    }

    public function destinationAirport(): BelongsTo
    {
        return $this->belongsTo(Airport::class, 'destination_airport_id');
    }

    public function flightSchedules(): HasMany
    {
        return $this->hasMany(FlightSchedule::class, 'route_id');
    }
}
