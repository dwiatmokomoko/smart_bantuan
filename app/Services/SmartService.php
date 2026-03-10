<?php

namespace App\Services;

use App\Models\Data_training;
use App\Models\Criteria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SmartService
{
    private Data_training $model;

    public function __construct(Data_training $model)
    {
        $this->model = $model;
    }

    public function train(array $data_uji): array
    {
        // --- 1) Ambil semua kriteria dan hitung total bobot ---
        $criterias = Criteria::all();
        $total_bobot = $criterias->sum('weight');
        
        if ($total_bobot == 0) {
            throw new RuntimeException("Total bobot kriteria tidak boleh 0");
        }

        // --- 2) Siapkan keys dan raw values dari input user ---
        $keys = [];
        $raw = [];
        $criteria_map = []; // mapping field_name => criteria_id
        
        foreach ($criterias as $criteria) {
            $field_name = $this->getCriteriaFieldName($criteria->id);
            $keys[] = $field_name;
            $raw[$field_name] = (float) ($data_uji[$field_name] ?? 0);
            $criteria_map[$field_name] = $criteria->id;
        }

        // --- 3) Hitung normalisasi bobot untuk setiap kriteria ---
        $normalisasi_bobot = [];
        foreach ($criterias as $criteria) {
            $field_name = $this->getCriteriaFieldName($criteria->id);
            $normalisasi_bobot[$field_name] = $criteria->weight / $total_bobot;
        }

        // --- 4) Hitung utility value menggunakan rumus: ui(ai) = (Cout - Cmin) / (Cmax - Cmin) ---
        $utility_value = [];
        foreach ($criterias as $criteria) {
            $field_name = $this->getCriteriaFieldName($criteria->id);
            $cout = $raw[$field_name];
            
            // Ambil Cmin dan Cmax dari sub_criterias
            $sub_criterias = DB::table('sub_criterias')
                ->where('criteria_id', $criteria->id)
                ->get(['weight']);
            
            if ($sub_criterias->isEmpty()) {
                $utility_value[$field_name] = 0.0;
                continue;
            }
            
            $cmin = $sub_criterias->min('weight');
            $cmax = $sub_criterias->max('weight');
            
            // Hitung utility value
            if ($cmax == $cmin) {
                // Jika semua nilai sama, utility = 1
                $utility_value[$field_name] = 1.0;
            } else {
                $utility_value[$field_name] = ($cout - $cmin) / ($cmax - $cmin);
            }
        }

        // --- 5) Hitung nilai akhir = normalisasi_bobot * utility_value ---
        $nilai_akhir = [];
        foreach ($keys as $field_name) {
            $nilai_akhir[$field_name] = $this->round10(
                $normalisasi_bobot[$field_name] * $utility_value[$field_name]
            );
        }
        $prob_layak = array_sum($nilai_akhir);

        // --- 6) Tentukan klasifikasi berdasarkan nilai akhir ---
        $klasifikasi = $this->getKlasifikasi($prob_layak);

        // --- 7) Ambil LABEL dari sub_criterias ---
        $label = [];
        foreach ($criterias as $criteria) {
            $field_name = $this->getCriteriaFieldName($criteria->id);
            $raw_value = $raw[$field_name];
            
            $label[$field_name] = DB::table('sub_criterias')
                ->where(['criteria_id' => $criteria->id, 'weight' => $raw_value])
                ->value('name') ?? (string) $raw_value;
        }

        $ticket = $this->generateUniqueTicket();

        // --- 8) Simpan ke data_trainings ---
        $payload = [
            'name' => $data_uji['name'],
            'nik' => $data_uji['nik'],
            'ticket' => $ticket,
            'kelayakan' => $this->getKelayakan($prob_layak), // 1 = layak, 0 = tidak layak
            'prob_layak' => $this->round10($prob_layak),
            'status' => 1, // data testing
            'created_by' => (string) (Auth::id() ?? 'guest'),
            'input_label' => json_encode($label, JSON_UNESCAPED_UNICODE),
        ];

        // Simpan nilai akhir ke kolom existing (mapping dinamis)
        $column_map = [
            1 => 'pekerjaan',
            2 => 'status_hubungan_keluarga',
            3 => 'data_kependudukan_sinkron',
            4 => 'anggota_keluarga_bpjs',
            5 => 'anggota_keluarga_luar',
            6 => 'kependudukan_wilayah_pbi',
        ];

        foreach ($criterias as $criteria) {
            $field_name = $this->getCriteriaFieldName($criteria->id);
            $column = $column_map[$criteria->id] ?? null;
            
            if ($column) {
                $payload[$column] = $this->round10($nilai_akhir[$field_name]);
                $payload[$column . '_raw'] = $raw[$field_name];
            }
        }

        logger()->info('payload-training', $payload + [
            '_normalisasi_bobot' => $normalisasi_bobot,
            '_utility_value' => $utility_value,
            '_nilai_akhir' => $nilai_akhir
        ]);

        $this->model->create($payload);

        return [
            'ticket' => $ticket,
            'prob_layak' => $this->round10($prob_layak),
            'klasifikasi' => $klasifikasi,
            'keputusan' => $this->getKelayakan($prob_layak) == 1 ? 'Layak' : 'Tidak Layak',
            'labels' => $label,
            'nilai_akhir' => $nilai_akhir,
            'normalisasi_bobot' => $normalisasi_bobot,
            'utility_value' => $utility_value,
        ];
    }

    /**
     * Mapping criteria_id ke field name yang digunakan di form
     */
    private function getCriteriaFieldName(int $criteria_id): string
    {
        $map = [
            1 => 'pekerjaan',
            2 => 'status_hubungan_keluarga',
            3 => 'data_kependudukan_sinkron',
            4 => 'anggota_keluarga_bpjs',
            5 => 'anggota_keluarga_luar',
            6 => 'kependudukan_wilayah_pbi',
        ];
        
        return $map[$criteria_id] ?? 'criteria_' . $criteria_id;
    }

    /**
     * Tentukan klasifikasi berdasarkan nilai akhir (prob_layak)
     * Rumus: prob_layak = Σ (normalisasi_bobot * utility_value)
     * Klasifikasi:
     * < 0.50 = Tidak berhak menerima PBI BPJS
     * 0.50 - 0.75 = Bisa diupayakan menerima PBI BPJS dengan penyesuaian persyaratan
     * > 0.75 = Berhak menerima PBI BPJS
     */
    private function getKlasifikasi(float $nilai): string
    {
        if ($nilai < 0.50) {
            return 'Tidak Berhak Menerima PBI BPJS';
        } elseif ($nilai >= 0.50 && $nilai <= 0.75) {
            return 'Bisa Diupayakan Menerima PBI BPJS dengan Penyesuaian Persyaratan';
        } else {
            return 'Berhak Menerima PBI BPJS';
        }
    }

    /**
     * Tentukan kelayakan (1 = layak, 0 = tidak layak)
     * Layak jika nilai >= 0.50
     */
    private function getKelayakan(float $nilai): int
    {
        return $nilai >= 0.50 ? 1 : 0;
    }

    private function round10(float $v): float
    {
        return (float) number_format($v, 10, '.', '');
    }

    private function generateUniqueTicket(): string
    {
        do {
            $ticket = substr(bin2hex(random_bytes(8)), 0, 12);
        } while ($this->model->where('ticket', $ticket)->exists());
        return $ticket;
    }
}
