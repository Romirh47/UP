<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorData extends Model
{
    protected $fillable = ['sensor_id', 'value'];

    /**
     * Relasi dengan model Sensor
     */
    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class);
    }
}
