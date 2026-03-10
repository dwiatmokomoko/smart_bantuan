<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CriteriasTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $rows = [
            ['id'=>1,'name'=>'Pekerjaan','weight'=>90,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'name'=>'Status Hubungan Dalam Keluarga','weight'=>60,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'name'=>'Data Kependudukan Sinkron','weight'=>70,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4,'name'=>'Adanya Anggota Keluarga Sudah Ditanggung Iuran BPJS','weight'=>60,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>5,'name'=>'Adanya Anggota Keluarga di luar keluarga inti','weight'=>60,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>6,'name'=>'Kependudukan Sesuai Wilayah PBI BPJS','weight'=>80,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];

        // Kunci unik berdasarkan PRIMARY KEY 'id'
        DB::table('criterias')->upsert(
            $rows,
            ['id'], // conflict target
            ['name','weight','updated_by','updated_at'] // kolom yang di-update jika sudah ada
        );
    }
}
