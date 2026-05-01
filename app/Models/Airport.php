<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['city_id', 'code', 'name', 'address'])]
class Airport extends Model
{
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function originRoutes(): HasMany
    {
        return $this->hasMany(FlightRoute::class, 'origin_airport_id');
    }

    public function destinationRoutes(): HasMany
    {
        return $this->hasMany(FlightRoute::class, 'destination_airport_id');
    }
}
