<?php
// database/migrations/2025_10_25_160000_update_user_berkas_status_add_interview.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Hapus CHECK lama lalu buat ulang dengan daftar status baru
        DB::statement("ALTER TABLE user_berkas DROP CONSTRAINT IF EXISTS user_berkas_status_check");
        // daftar nilai yang diizinkan (pakai lowercase)
        DB::statement("
          ALTER TABLE user_berkas
          ADD CONSTRAINT user_berkas_status_check
          CHECK (status IN ('pengajuan','interview','approved','rejected'))
        ");
        // Untuk record lama yang masih 'pending', kita migrasikan ke 'pengajuan'
        DB::statement("UPDATE user_berkas SET status='pengajuan' WHERE status='pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE user_berkas DROP CONSTRAINT IF EXISTS user_berkas_status_check");
        DB::statement("
          ALTER TABLE user_berkas
          ADD CONSTRAINT user_berkas_status_check
          CHECK (status IN ('pending','approved','rejected'))
        ");
        DB::statement("UPDATE user_berkas SET status='pending' WHERE status='pengajuan'");
    }
};
