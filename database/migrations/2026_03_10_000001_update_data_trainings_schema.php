<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('data_trainings', function (Blueprint $table) {
            // Buat kolom lama nullable
            if (Schema::hasColumn('data_trainings', 'penghasilan')) {
                $table->integer('penghasilan')->nullable()->change();
            }
            if (Schema::hasColumn('data_trainings', 'pekerjaan')) {
                $table->decimal('pekerjaan', 10, 10)->nullable()->change();
            }
            if (Schema::hasColumn('data_trainings', 'perkawinan')) {
                $table->integer('perkawinan')->nullable()->change();
            }
            if (Schema::hasColumn('data_trainings', 'calon_penghuni')) {
                $table->integer('calon_penghuni')->nullable()->change();
            }
            if (Schema::hasColumn('data_trainings', 'status_penempatan')) {
                $table->integer('status_penempatan')->nullable()->change();
            }
            if (Schema::hasColumn('data_trainings', 'status_kependudukan')) {
                $table->integer('status_kependudukan')->nullable()->change();
            }
            if (Schema::hasColumn('data_trainings', 'status_kepemilikan_rumah')) {
                $table->integer('status_kepemilikan_rumah')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_trainings', function (Blueprint $table) {
            if (Schema::hasColumn('data_trainings', 'penghasilan')) {
                $table->integer('penghasilan')->nullable(false)->change();
            }
            if (Schema::hasColumn('data_trainings', 'pekerjaan')) {
                $table->integer('pekerjaan')->nullable(false)->change();
            }
            if (Schema::hasColumn('data_trainings', 'perkawinan')) {
                $table->integer('perkawinan')->nullable(false)->change();
            }
            if (Schema::hasColumn('data_trainings', 'calon_penghuni')) {
                $table->integer('calon_penghuni')->nullable(false)->change();
            }
            if (Schema::hasColumn('data_trainings', 'status_penempatan')) {
                $table->integer('status_penempatan')->nullable(false)->change();
            }
            if (Schema::hasColumn('data_trainings', 'status_kependudukan')) {
                $table->integer('status_kependudukan')->nullable(false)->change();
            }
            if (Schema::hasColumn('data_trainings', 'status_kepemilikan_rumah')) {
                $table->integer('status_kepemilikan_rumah')->nullable(false)->change();
            }
        });
    }
};
