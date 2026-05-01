<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'province', 'country'])]
class City extends Model
{
    public function airports(): HasMany
    {
        return $this->hasMany(Airport::class);
    }
}
