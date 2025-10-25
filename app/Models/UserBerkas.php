<?php

// app/Models/UserBerkas.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBerkas extends Model
{
    protected $table = 'user_berkas';

    protected $fillable = [
        'user_id','ticket',
        'ktp_path','kk_path','surat_belum_memiliki_rumah_path',
        'slip_gaji_path','skck_path',
        'status','notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
