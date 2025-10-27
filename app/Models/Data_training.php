<?php
// app/Models/Data_training.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Data_training extends Model
{
    protected $table = 'data_trainings';

    protected $fillable = [
        'name','ticket',
        'penghasilan','pekerjaan','perkawinan','calon_penghuni','status_penempatan',
        'kelayakan','prob_layak','status','created_by','updated_by'
    ];

    protected $casts = [
        'penghasilan'       => 'decimal:4',
        'pekerjaan'         => 'decimal:4',
        'perkawinan'        => 'decimal:4',
        'calon_penghuni'    => 'decimal:4',
        'status_penempatan' => 'decimal:4',
        'prob_layak'        => 'decimal:6',
        'kelayakan'         => 'integer',
        'status'            => 'integer',
    ];
}
