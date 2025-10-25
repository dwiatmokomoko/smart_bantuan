<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data_training extends Model
{
    use HasFactory;
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
        'updated_by'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->ticket = uniqid();
        });
    }
}
