<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['registration_code', 'model', 'seat_rows', 'seat_columns', 'capacity', 'status'])]
class Aircraft extends Model
{
    protected $table = 'aircrafts';

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function flightSchedules(): HasMany
    {
        return $this->hasMany(FlightSchedule::class);
    }
}
