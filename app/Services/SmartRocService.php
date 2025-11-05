<?php

namespace App\Services;

use App\Models\Data_training;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SmartRocService
{
    private Data_training $model;

    public function __construct(Data_training $model)
    {
        $this->model = $model;
    }

    public function train(array $data_uji): array
    {
        // --- 1) RAW dari form (float) ---
        $keys = ['penghasilan', 'pekerjaan', 'perkawinan', 'calon_penghuni', 'status_penempatan'];
        $raw = [];
        foreach ($keys as $k) {
            $raw[$k] = (float) ($data_uji[$k] ?? 0);
        }

        // --- 2) Ambil baris MIN & PEMBAGI ---
        $min = $this->model->where('name', 'MIN')->first();
        $div = $this->model->where('name', 'PEMBAGI')->first();
        if (!$min || !$div) {
            throw new RuntimeException("Row 'MIN' atau 'PEMBAGI' tidak ditemukan di data_trainings.");
        }
        $MIN = [
            'penghasilan' => (float) $min->penghasilan,
            'pekerjaan' => (float) $min->pekerjaan,
            'perkawinan' => (float) $min->perkawinan,
            'calon_penghuni' => (float) $min->calon_penghuni,
            'status_penempatan' => (float) $min->status_penempatan,
        ];
        $DIV = [
            'penghasilan' => (float) $div->penghasilan,
            'pekerjaan' => (float) $div->pekerjaan,
            'perkawinan' => (float) $div->perkawinan,
            'calon_penghuni' => (float) $div->calon_penghuni,
            'status_penempatan' => (float) $div->status_penempatan,
        ];

        // --- 3) Normalisasi Xi = (val - MIN)/DIV ---
        $norm = [];
        foreach ($keys as $k) {
            $d = $DIV[$k];
            $norm[$k] = $d == 0.0 ? 0.0 : max(0.0, ($raw[$k] - $MIN[$k]) / $d);
        }

        // --- 4) Bobot ---
        $w = [
            'penghasilan' => 0.4567,
            'pekerjaan' => 0.2567,
            'status_penempatan' => 0.1567,
            'calon_penghuni' => 0.09,
            'perkawinan' => 0.04,
        ];

        // --- 5) Skor berbobot ---
        $scored = [
            'penghasilan' => $norm['penghasilan'] * $w['penghasilan'],
            'pekerjaan' => $norm['pekerjaan'] * $w['pekerjaan'],
            'status_penempatan' => $norm['status_penempatan'] * $w['status_penempatan'],
            'calon_penghuni' => $norm['calon_penghuni'] * $w['calon_penghuni'],
            'perkawinan' => $norm['perkawinan'] * $w['perkawinan'],
        ];
        $prob_layak = array_sum($scored);

        // --- 6) Keputusan (sesuai requirement Anda: semua layak) ---
        $keputusan = 1;

        // --- 7) Ambil LABEL pilihan dari tabel sub_criterias (berdasarkan criteria_id & weight RAW) ---
        // Mapping criteria_id: 1=Penghasilan, 2=Pekerjaan, 3=Perkawinan, 4=Calon Penghuni, 5=Status Penempatan
        $label = [
            'penghasilan' => DB::table('sub_criterias')->where(['criteria_id' => 1, 'weight' => $raw['penghasilan']])->value('name') ?? (string) $raw['penghasilan'],
            'pekerjaan' => DB::table('sub_criterias')->where(['criteria_id' => 2, 'weight' => $raw['pekerjaan']])->value('name') ?? (string) $raw['pekerjaan'],
            'perkawinan' => DB::table('sub_criterias')->where(['criteria_id' => 3, 'weight' => $raw['perkawinan']])->value('name') ?? (string) $raw['perkawinan'],
            'calon_penghuni' => DB::table('sub_criterias')->where(['criteria_id' => 4, 'weight' => $raw['calon_penghuni']])->value('name') ?? (string) $raw['calon_penghuni'],
            'status_penempatan' => DB::table('sub_criterias')->where(['criteria_id' => 5, 'weight' => $raw['status_penempatan']])->value('name') ?? (string) $raw['status_penempatan'],
        ];

        $ticket = $this->generateUniqueTicket();

        // --- 8) Simpan ke data_trainings ---
        $payload = [
            'name' => $data_uji['name'],
            'nik' => $data_uji['nik'],
            'ticket' => $ticket,

            // simpan nilai skor berbobot (kolom existing)
            'penghasilan' => $this->round4($scored['penghasilan']),
            'pekerjaan' => $this->round4($scored['pekerjaan']),
            'perkawinan' => $this->round4($scored['perkawinan']),
            'calon_penghuni' => $this->round4($scored['calon_penghuni']),
            'status_penempatan' => $this->round4($scored['status_penempatan']),

            // simpan RAW & LABEL untuk ditampilkan di riwayat
            'penghasilan_raw' => $raw['penghasilan'],
            'pekerjaan_raw' => $raw['pekerjaan'],
            'perkawinan_raw' => $raw['perkawinan'],
            'calon_penghuni_raw' => $raw['calon_penghuni'],
            'status_penempatan_raw' => $raw['status_penempatan'],
            'input_label' => json_encode($label, JSON_UNESCAPED_UNICODE),

            'kelayakan' => $keputusan,
            'prob_layak' => $this->round4($prob_layak),
            'status' => 1, // data testing
            'created_by' => (string) (Auth::id() ?? 'guest'),
        ];

        logger()->info('payload-training', $payload + [
            '_min' => $MIN,
            '_div' => $DIV,
            '_norm' => $norm
        ]);

        $this->model->create($payload);

        return [
            'ticket' => $ticket,
            'prob_layak' => $this->round4($prob_layak),
            'keputusan' => 'layak',
            'labels' => $label,
        ];
    }

    private function round4(float $v): float
    {
        return (float) number_format($v, 4, '.', '');
    }

    private function generateUniqueTicket(): string
    {
        do {
            $ticket = substr(bin2hex(random_bytes(8)), 0, 12);
        } while ($this->model->where('ticket', $ticket)->exists());
        return $ticket;
    }
}
