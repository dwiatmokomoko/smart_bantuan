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
            // Tambahkan kolom baru untuk 6 kriteria jika belum ada
            if (!Schema::hasColumn('data_trainings', 'status_hubungan_keluarga')) {
                $table->decimal('status_hubungan_keluarga', 10, 10)->nullable()->after('pekerjaan');
            }
            if (!Schema::hasColumn('data_trainings', 'data_kependudukan_sinkron')) {
                $table->decimal('data_kependudukan_sinkron', 10, 10)->nullable()->after('status_hubungan_keluarga');
            }
            if (!Schema::hasColumn('data_trainings', 'anggota_keluarga_bpjs')) {
                $table->decimal('anggota_keluarga_bpjs', 10, 10)->nullable()->after('data_kependudukan_sinkron');
            }
            if (!Schema::hasColumn('data_trainings', 'anggota_keluarga_luar')) {
                $table->decimal('anggota_keluarga_luar', 10, 10)->nullable()->after('anggota_keluarga_bpjs');
            }
            if (!Schema::hasColumn('data_trainings', 'kependudukan_wilayah_pbi')) {
                $table->decimal('kependudukan_wilayah_pbi', 10, 10)->nullable()->after('anggota_keluarga_luar');
            }
            
            // Tambahkan kolom raw untuk setiap kriteria jika belum ada
            if (!Schema::hasColumn('data_trainings', 'status_hubungan_keluarga_raw')) {
                $table->decimal('status_hubungan_keluarga_raw', 10, 2)->nullable()->after('pekerjaan_raw');
            }
            if (!Schema::hasColumn('data_trainings', 'data_kependudukan_sinkron_raw')) {
                $table->decimal('data_kependudukan_sinkron_raw', 10, 2)->nullable()->after('status_hubungan_keluarga_raw');
            }
            if (!Schema::hasColumn('data_trainings', 'anggota_keluarga_bpjs_raw')) {
                $table->decimal('anggota_keluarga_bpjs_raw', 10, 2)->nullable()->after('data_kependudukan_sinkron_raw');
            }
            if (!Schema::hasColumn('data_trainings', 'anggota_keluarga_luar_raw')) {
                $table->decimal('anggota_keluarga_luar_raw', 10, 2)->nullable()->after('anggota_keluarga_bpjs_raw');
            }
            if (!Schema::hasColumn('data_trainings', 'kependudukan_wilayah_pbi_raw')) {
                $table->decimal('kependudukan_wilayah_pbi_raw', 10, 2)->nullable()->after('anggota_keluarga_luar_raw');
            }
            
            // Tambahkan kolom untuk probabilitas dan label jika belum ada
            if (!Schema::hasColumn('data_trainings', 'prob_layak')) {
                $table->decimal('prob_layak', 10, 10)->nullable()->after('kependudukan_wilayah_pbi_raw');
            }
            if (!Schema::hasColumn('data_trainings', 'input_label')) {
                $table->text('input_label')->nullable()->after('prob_layak');
            }
            
            // Tambahkan kolom NIK jika belum ada
            if (!Schema::hasColumn('data_trainings', 'nik')) {
                $table->string('nik')->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_trainings', function (Blueprint $table) {
            $table->dropColumn([
                'status_hubungan_keluarga',
                'data_kependudukan_sinkron',
                'anggota_keluarga_bpjs',
                'anggota_keluarga_luar',
                'kependudukan_wilayah_pbi',
                'status_hubungan_keluarga_raw',
                'data_kependudukan_sinkron_raw',
                'anggota_keluarga_bpjs_raw',
                'anggota_keluarga_luar_raw',
                'kependudukan_wilayah_pbi_raw',
                'prob_layak',
                'input_label',
                'nik',
            ]);
        });
    }
};
