<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'code', 'description'])]
class TicketClass extends Model
{
    public function prices(): HasMany
    {
        return $this->hasMany(TicketPrice::class);
    }
}
