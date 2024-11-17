<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // Menyesuaikan kolom pada tabel 'reports'
    protected $fillable = ['jenis_kejadian', 'foto_kejadian'];
}
