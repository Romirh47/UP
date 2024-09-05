<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['sensor_id', 'actuator_id', 'min_value', 'max_value', 'actuator_action'];

    // Relasi dengan Sensor
    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }

    // Relasi dengan Actuator
    public function actuator()
    {
        return $this->belongsTo(Actuator::class);
    }
}
