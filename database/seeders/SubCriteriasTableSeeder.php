<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SubCriteriasTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $rows = [
            // criteria_id = 1 (Pekerjaan)
            ['id'=>1, 'criteria_id'=>1, 'name'=>'Tetap',        'weight'=>40,  'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],
            ['id'=>2, 'criteria_id'=>1, 'name'=>'Tidak Tetap',  'weight'=>80,  'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],
            ['id'=>3, 'criteria_id'=>1, 'name'=>'Tidak Bekerja','weight'=>90,  'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],

            // criteria_id = 2 (Status Hubungan Dalam Keluarga)
            ['id'=>4, 'criteria_id'=>2, 'name'=>'Kepala Keluarga',  'weight'=>80, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],
            ['id'=>5, 'criteria_id'=>2, 'name'=>'Suami/Istri',      'weight'=>75, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],
            ['id'=>6, 'criteria_id'=>2, 'name'=>'Anak',             'weight'=>70, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],
            ['id'=>7, 'criteria_id'=>2, 'name'=>'Menantu',          'weight'=>50, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],
            ['id'=>8, 'criteria_id'=>2, 'name'=>'Cucu',             'weight'=>30, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],
            ['id'=>9, 'criteria_id'=>2, 'name'=>'Mertua',           'weight'=>40, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],
            ['id'=>10, 'criteria_id'=>2, 'name'=>'Famili Lain',     'weight'=>20, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],
            ['id'=>11, 'criteria_id'=>2, 'name'=>'Lainnya',         'weight'=>10, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],

            // criteria_id = 3 (Data Kependudukan Sinkron)
            ['id'=>12, 'criteria_id'=>3, 'name'=>'Ya',   'weight'=>80, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],
            ['id'=>13, 'criteria_id'=>3, 'name'=>'Tidak','weight'=>50, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],

            // criteria_id = 4 (Adanya Anggota Keluarga Sudah Ditanggung Iuran BPJS)
            ['id'=>14, 'criteria_id'=>4, 'name'=>'Ada',   'weight'=>40, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],
            ['id'=>15, 'criteria_id'=>4, 'name'=>'Tidak', 'weight'=>70, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],

            // criteria_id = 5 (Adanya Anggota Keluarga di luar keluarga inti)
            ['id'=>16, 'criteria_id'=>5, 'name'=>'Ada',   'weight'=>50, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],
            ['id'=>17, 'criteria_id'=>5, 'name'=>'Tidak', 'weight'=>80, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],

            // criteria_id = 6 (Kependudukan Sesuai Wilayah PBI BPJS)
            ['id'=>18, 'criteria_id'=>6, 'name'=>'Ya',    'weight'=>80, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],
            ['id'=>19, 'criteria_id'=>6, 'name'=>'Tidak', 'weight'=>30, 'created_by'=>1, 'updated_by'=>1, 'created_at'=>$now, 'updated_at'=>$now],
        ];

        // Idempotent: jika id sudah ada, update kolom berikut
        DB::table('sub_criterias')->upsert(
            $rows,
            ['id'], // conflict target (PRIMARY KEY)
            ['criteria_id','name','weight','updated_by','updated_at']
        );
    }
}
