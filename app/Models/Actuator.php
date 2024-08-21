<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actuator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    // Relasi jika diperlukan
    public function actuatorValues()
    {
        return $this->hasMany(ActuatorValue::class);
    }
}
