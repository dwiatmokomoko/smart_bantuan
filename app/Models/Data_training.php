<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Data_training.php
class Data_training extends Model
{
    protected $fillable = [
        'name',
        'ticket',
        'penghasilan',
        'pekerjaan',
        'perkawinan',
        'calon_penghuni',
        'status_penempatan',
        'kelayakan',
        'status',
        'created_by',
        'updated_by',
        'prob_layak',
        'prob_tidak_layak',
    ];

    protected $casts = [
        'prob_layak' => 'float',
        'prob_tidak_layak' => 'float',
    ];
}

