<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('data_trainings', function (Blueprint $table) {
            // Hapus kolom yang tidak dipakai lagi
            if (Schema::hasColumn('data_trainings', 'status_kependudukan')) {
                $table->dropColumn('status_kependudukan');
            }
            if (Schema::hasColumn('data_trainings', 'status_kepemilikan_rumah')) {
                $table->dropColumn('status_kepemilikan_rumah');
            }
        });
    }

    public function down(): void
    {
        Schema::table('data_trainings', function (Blueprint $table) {
            // Kembalikan jika di-rollback (sesuaikan tipe data aslinya)
            $table->unsignedInteger('status_kependudukan')->nullable(false)->default(0);
            $table->unsignedInteger('status_kepemilikan_rumah')->nullable(false)->default(0);
        });
    }
};
