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
        'nik',
        'ticket',

        // nilai skor berbobot (hasil normalisasi * bobot) - 6 kriteria baru
        'pekerjaan',
        'status_hubungan_keluarga',
        'data_kependudukan_sinkron',
        'anggota_keluarga_bpjs',
        'anggota_keluarga_luar',
        'kependudukan_wilayah_pbi',

        // klasifikasi
        'kelayakan',
        'prob_layak',
        'status',

        // audit
        'created_by',
        'updated_by',

        // ======== FIELD BARU (RAW & LABEL) ========
        'pekerjaan_raw',
        'status_hubungan_keluarga_raw',
        'data_kependudukan_sinkron_raw',
        'anggota_keluarga_bpjs_raw',
        'anggota_keluarga_luar_raw',
        'kependudukan_wilayah_pbi_raw',
        'input_label',
    ];

    protected $casts = [
        // skor berbobot 10 desimal (sesuai SmartService)
        'pekerjaan'                    => 'decimal:10',
        'status_hubungan_keluarga'     => 'decimal:10',
        'data_kependudukan_sinkron'    => 'decimal:10',
        'anggota_keluarga_bpjs'        => 'decimal:10',
        'anggota_keluarga_luar'        => 'decimal:10',
        'kependudukan_wilayah_pbi'     => 'decimal:10',
        'prob_layak'                   => 'decimal:10',

        // flag numerik
        'kelayakan'          => 'integer',
        'status'             => 'integer',

        // ======== CAST BARU (RAW & LABEL) ========
        // RAW 2 desimal (ikuti migrasi)
        'pekerjaan_raw'                    => 'decimal:2',
        'status_hubungan_keluarga_raw'     => 'decimal:2',
        'data_kependudukan_sinkron_raw'    => 'decimal:2',
        'anggota_keluarga_bpjs_raw'        => 'decimal:2',
        'anggota_keluarga_luar_raw'        => 'decimal:2',
        'kependudukan_wilayah_pbi_raw'     => 'decimal:2',

        // otomatis decode JSON -> array saat diakses
        'input_label'        => 'array',
    ];
}
