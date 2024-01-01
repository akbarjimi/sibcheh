<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function arrivals(): HasMany
    {
        return $this->hasMany(Flight::class, 'to_location_id');
    }

    public function departures(): HasMany
    {
        return $this->hasMany(Flight::class, 'from_location_id');
    }
}
