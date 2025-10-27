<?php
// database/migrations/2025_10_26_000001_alter_data_trainings_to_decimal.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('data_trainings', function (Blueprint $table) {
            // ubah kolom fitur jadi decimal(10,4)
            $table->decimal('penghasilan',       10, 4)->change();
            $table->decimal('pekerjaan',         10, 4)->change();
            $table->decimal('perkawinan',        10, 4)->change();
            $table->decimal('calon_penghuni',    10, 4)->change();
            $table->decimal('status_penempatan', 10, 4)->change();

            // pastikan prob_layak ada dan bertipe desimal
            if (!Schema::hasColumn('data_trainings', 'prob_layak')) {
                $table->decimal('prob_layak', 12, 6)->nullable();
            } else {
                $table->decimal('prob_layak', 12, 6)->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('data_trainings', function (Blueprint $table) {
            // kembalikan ke integer kalau perlu
            $table->integer('penghasilan')->change();
            $table->integer('pekerjaan')->change();
            $table->integer('perkawinan')->change();
            $table->integer('calon_penghuni')->change();
            $table->integer('status_penempatan')->change();
            $table->dropColumn('prob_layak');
        });
    }
};
