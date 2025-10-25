<?php

// database/migrations/2025_10_25_000000_create_user_berkas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_berkas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()->cascadeOnDelete();

            // tiket proses (opsional, dari halaman hasil kelayakan)
            $table->string('ticket', 64)->nullable()->index();

            // path file di storage disk "public"
            $table->string('ktp_path');
            $table->string('kk_path');
            $table->string('surat_belum_memiliki_rumah_path');
            $table->string('slip_gaji_path');
            $table->string('skck_path');

            // status verifikasi berkas oleh admin
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_berkas');
    }
};
