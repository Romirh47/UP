<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sensor extends Model
{
    protected $fillable = ['name', 'type', 'description'];

    /**
     * Relasi dengan model SensorData
     */
    public function sensorData(): HasMany
    {
        return $this->hasMany(SensorData::class);
    }
}
