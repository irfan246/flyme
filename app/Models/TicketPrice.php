<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['flight_schedule_id', 'ticket_class_id', 'price', 'quota'])]
class TicketPrice extends Model
{
    public function flightSchedule(): BelongsTo
    {
        return $this->belongsTo(FlightSchedule::class);
    }

    public function ticketClass(): BelongsTo
    {
        return $this->belongsTo(TicketClass::class);
    }
}
