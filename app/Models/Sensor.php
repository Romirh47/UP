<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sensor extends Model
{
    protected $fillable = ['name', 'type', 'description'];


    public function sensorData(): HasMany
    {
        return $this->hasMany(SensorData::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function latestValue()
    {
        // Ambil nilai sensor terbaru berdasarkan waktu 'created_at'
        return $this->sensorData()->orderBy('created_at', 'desc')->first()->value ?? null;
    }
}
