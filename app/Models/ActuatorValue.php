<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActuatorValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'actuator_id',
        'value'
    ];

    // Relasi dengan Actuator
    public function actuator()
    {
        return $this->belongsTo(Actuator::class, 'actuator_id');
    }
}
