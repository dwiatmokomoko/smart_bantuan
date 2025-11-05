<?php
// app/Models/Data_training.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Data_training extends Model
{
    protected $table = 'data_trainings';

    // Pastikan timestamps aktif agar created_at/updated_at terisi otomatis
    public $timestamps = true;

    protected $fillable = [
        // identitas
        'name',
        
        'ticket',

        // nilai skor berbobot (hasil normalisasi * bobot)
        'penghasilan',
        'pekerjaan',
        'perkawinan',
        'calon_penghuni',
        'status_penempatan',

        // klasifikasi
        'kelayakan',
        'prob_layak',
        'status',

        // audit
        'created_by',
        'updated_by',

        // ======== FIELD BARU (RAW & LABEL) ========
        'penghasilan_raw',
        'pekerjaan_raw',
        'perkawinan_raw',
        'calon_penghuni_raw',
        'status_penempatan_raw',
        'input_label',
    ];

    protected $casts = [
        // skor berbobot empat desimal
        'penghasilan'        => 'decimal:4',
        'pekerjaan'          => 'decimal:4',
        'status_penempatan'  => 'decimal:4',
        'calon_penghuni'     => 'decimal:4',
        'perkawinan'         => 'decimal:4',
        'prob_layak'         => 'decimal:4',

        // flag numerik
        'kelayakan'          => 'integer',
        'status'             => 'integer',

        // ======== CAST BARU (RAW & LABEL) ========
        // RAW boleh 2 desimal (ikuti migrasi)
        'penghasilan_raw'        => 'decimal:2',
        'pekerjaan_raw'          => 'decimal:2',
        'perkawinan_raw'         => 'decimal:2',
        'calon_penghuni_raw'     => 'decimal:2',
        'status_penempatan_raw'  => 'decimal:2',

        // otomatis decode JSON -> array saat diakses
        'input_label'        => 'array',
    ];
}
