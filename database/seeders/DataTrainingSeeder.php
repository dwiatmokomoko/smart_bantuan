<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataTrainingSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk tabel data_trainings.
     */
    public function run(): void
    {
        $dataTraining = [
            // Data 1 - Layak
            [
                'name' => 'DT001',
                'nik' => '1234567890123456',
                'ticket' => strtoupper(Str::random(10)),
                'penghasilan' => 0,
                'pekerjaan' => 0.1714285714,
                'perkawinan' => 0,
                'calon_penghuni' => 0,
                'status_penempatan' => 0,
                'status_hubungan_keluarga' => 0.0204081633,
                'data_kependudukan_sinkron' => 0.1666666667,
                'anggota_keluarga_bpjs' => 0,
                'anggota_keluarga_luar' => 0,
                'kependudukan_wilayah_pbi' => 0.1904761905,
                'pekerjaan_raw' => 40,
                'status_hubungan_keluarga_raw' => 80,
                'data_kependudukan_sinkron_raw' => 80,
                'anggota_keluarga_bpjs_raw' => 40,
                'anggota_keluarga_luar_raw' => 50,
                'kependudukan_wilayah_pbi_raw' => 80,
                'prob_layak' => 0.5489795918,
                'input_label' => json_encode(['pekerjaan' => 'Tetap', 'status_hubungan_keluarga' => 'Kepala Keluarga', 'data_kependudukan_sinkron' => 'Ya', 'anggota_keluarga_bpjs' => 'Ada', 'anggota_keluarga_luar' => 'Ada', 'kependudukan_wilayah_pbi' => 'Ya']),
                'kelayakan' => 1,
                'status' => 0,
                'created_by' => 'admin',
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Data 2 - Tidak Layak
            [
                'name' => 'DT002',
                'nik' => '1234567890123457',
                'ticket' => strtoupper(Str::random(10)),
                'penghasilan' => 0,
                'pekerjaan' => 0.1142857143,
                'perkawinan' => 0,
                'calon_penghuni' => 0,
                'status_penempatan' => 0,
                'status_hubungan_keluarga' => 0.0107142857,
                'data_kependudukan_sinkron' => 0.0833333333,
                'anggota_keluarga_bpjs' => 0,
                'anggota_keluarga_luar' => 0,
                'kependudukan_wilayah_pbi' => 0.0071428571,
                'pekerjaan_raw' => 80,
                'status_hubungan_keluarga_raw' => 75,
                'data_kependudukan_sinkron_raw' => 50,
                'anggota_keluarga_bpjs_raw' => 40,
                'anggota_keluarga_luar_raw' => 80,
                'kependudukan_wilayah_pbi_raw' => 30,
                'prob_layak' => 0.2154761905,
                'input_label' => json_encode(['pekerjaan' => 'Tidak Tetap', 'status_hubungan_keluarga' => 'Suami/Istri', 'data_kependudukan_sinkron' => 'Tidak', 'anggota_keluarga_bpjs' => 'Ada', 'anggota_keluarga_luar' => 'Tidak', 'kependudukan_wilayah_pbi' => 'Tidak']),
                'kelayakan' => 0,
                'status' => 0,
                'created_by' => 'admin',
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Data 3 - Layak
            [
                'name' => 'DT003',
                'nik' => '1234567890123458',
                'ticket' => strtoupper(Str::random(10)),
                'penghasilan' => 0,
                'pekerjaan' => 0.1928571429,
                'perkawinan' => 0,
                'calon_penghuni' => 0,
                'status_penempatan' => 0,
                'status_hubungan_keluarga' => 0.0100000000,
                'data_kependudukan_sinkron' => 0.1333333333,
                'anggota_keluarga_bpjs' => 0,
                'anggota_keluarga_luar' => 0,
                'kependudukan_wilayah_pbi' => 0.1904761905,
                'pekerjaan_raw' => 90,
                'status_hubungan_keluarga_raw' => 70,
                'data_kependudukan_sinkron_raw' => 80,
                'anggota_keluarga_bpjs_raw' => 70,
                'anggota_keluarga_luar_raw' => 50,
                'kependudukan_wilayah_pbi_raw' => 80,
                'prob_layak' => 0.5266666667,
                'input_label' => json_encode(['pekerjaan' => 'Tidak Bekerja', 'status_hubungan_keluarga' => 'Anak', 'data_kependudukan_sinkron' => 'Ya', 'anggota_keluarga_bpjs' => 'Tidak', 'anggota_keluarga_luar' => 'Ada', 'kependudukan_wilayah_pbi' => 'Ya']),
                'kelayakan' => 1,
                'status' => 0,
                'created_by' => 'admin',
                'updated_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('data_trainings')->insert($dataTraining);
    }
}
