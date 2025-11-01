<?php
// database/migrations/2025_11_01_000001_fix_user_berkas_status_check.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Pastikan semua status lama yang 'pending' dipetakan ke 'pengajuan'
        DB::statement("UPDATE user_berkas SET status='pengajuan' WHERE status='pending'");

        // 2) Hapus CHECK lama (nama constraint bisa beda, tapi kamu tadi log-nya 'user_berkas_status_check')
        DB::statement("ALTER TABLE user_berkas DROP CONSTRAINT IF EXISTS user_berkas_status_check");

        // 3) Buat CHECK baru dengan daftar lengkap
        DB::statement("
            ALTER TABLE user_berkas
            ADD CONSTRAINT user_berkas_status_check
            CHECK (status IN ('pengajuan','interview','approved','rejected'))
        ");
    }

    public function down(): void
    {
        // Kembali ke versi lama (opsional)
        DB::statement("ALTER TABLE user_berkas DROP CONSTRAINT IF EXISTS user_berkas_status_check");
        DB::statement("
            ALTER TABLE user_berkas
            ADD CONSTRAINT user_berkas_status_check
            CHECK (status IN ('pending','approved','rejected'))
        ");
        DB::statement("UPDATE user_berkas SET status='pending' WHERE status='pengajuan'");
    }
};
