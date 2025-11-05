<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('data_trainings', function (Blueprint $t) {
            // Nilai RAW yang dipilih user (sebelum normalisasi & bobot)
            $t->decimal('penghasilan_raw', 8, 2)->nullable();
            $t->decimal('pekerjaan_raw', 8, 2)->nullable();
            $t->decimal('perkawinan_raw', 8, 2)->nullable();
            $t->decimal('calon_penghuni_raw', 8, 2)->nullable();
            $t->decimal('status_penempatan_raw', 8, 2)->nullable();

            // Label yang dipilih (JSON: {penghasilan:"...", pekerjaan:"...", ...})
            $t->json('input_label')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('data_trainings', function (Blueprint $t) {
            $t->dropColumn([
                'penghasilan_raw','pekerjaan_raw','perkawinan_raw',
                'calon_penghuni_raw','status_penempatan_raw','input_label'
            ]);
        });
    }
};
