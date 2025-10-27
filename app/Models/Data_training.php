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
        'penghasilan'        => 'float',
        'pekerjaan'          => 'float',
        'status_penempatan'  => 'float',
        'calon_penghuni'     => 'float',
        'perkawinan'         => 'float',
        'prob_layak'         => 'float',
        'kelayakan'         => 'integer',
        'status'            => 'integer',
    ];
}
