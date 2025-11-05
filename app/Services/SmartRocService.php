<?php

namespace App\Services;

use App\Models\Data_training;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use RuntimeException;

class SmartRocService
{
    private Data_training $model;

    public function __construct(Data_training $model)
    {
        $this->model = $model;
    }

    /**
     * $data_uji:
     *  - name, nik, jenis_kelamin
     *  - penghasilan, pekerjaan, perkawinan, calon_penghuni, status_penempatan  (ANGKA)
     */
    public function train(array $data_uji): array
    {
        // 1) Ambil input mentah & pastikan float
        $keys = ['penghasilan','pekerjaan','perkawinan','calon_penghuni','status_penempatan'];
        $raw  = [];
        foreach ($keys as $k) {
            $raw[$k] = (float) ($data_uji[$k] ?? 0);
        }

        // 2) Ambil baris MIN & PEMBAGI dari data_trainings
        $min  = $this->model->where('name', 'MIN')->first();
        $div  = $this->model->where('name', 'PEMBAGI')->first();

        if (!$min || !$div) {
            throw new RuntimeException("Row 'MIN' atau 'PEMBAGI' tidak ditemukan di data_trainings.");
        }

        // ambil nilai min & pembagi per kolom
        $MIN = [
            'penghasilan'       => (float) $min->penghasilan,
            'pekerjaan'         => (float) $min->pekerjaan,
            'perkawinan'        => (float) $min->perkawinan,
            'calon_penghuni'    => (float) $min->calon_penghuni,
            'status_penempatan' => (float) $min->status_penempatan,
        ];
        $DIV = [
            'penghasilan'       => (float) $div->penghasilan,
            'pekerjaan'         => (float) $div->pekerjaan,
            'perkawinan'        => (float) $div->perkawinan,
            'calon_penghuni'    => (float) $div->calon_penghuni,
            'status_penempatan' => (float) $div->status_penempatan,
        ];

        // 3) Normalisasi Xi = (val - MIN)/DIV (aman dari pembagi 0 & minimum 0)
        $norm = [];
        foreach ($keys as $k) {
            $d = $DIV[$k];
            $norm[$k] = $d == 0.0 ? 0.0 : max(0.0, ($raw[$k] - $MIN[$k]) / $d);
        }

        // 4) Bobot kriteria (sesuai permintaan)
        // C1 Penghasilan: 0.04, C2 Pekerjaan: 0.10, C3 Status Penempatan: 0.15,
        // C4 Calon Penghuni: 0.26, C5 Perkawinan: 0.45
        $w = [
            'penghasilan'       => 0.4567,
            'pekerjaan'         => 0.2567,
            'status_penempatan' => 0.1567,
            'calon_penghuni'    => 0.09,
            'perkawinan'        => 0.04,
        ];

        // 5) Hitung nilai ter-bobot untuk masing-masing kolom
        $scored = [
            'penghasilan'       => $norm['penghasilan']       * $w['penghasilan'],
            'pekerjaan'         => $norm['pekerjaan']         * $w['pekerjaan'],
            'status_penempatan' => $norm['status_penempatan'] * $w['status_penempatan'],
            'calon_penghuni'    => $norm['calon_penghuni']    * $w['calon_penghuni'],
            'perkawinan'        => $norm['perkawinan']        * $w['perkawinan'],
        ];

        $prob_layak = array_sum($scored);

        // 6) Semua hasil dinyatakan layak (sesuai instruksi)
        $keputusan = 1;

        // 7) Siapkan payload simpan
        $payload = [
            'name'               => $data_uji['name'],
            'nik'                => $data_uji['nik'],
            'ticket'             => $this->generateUniqueTicket(),
            // simpan NILAI TERBOBOT ke kolom-kolom K1..K5
            'penghasilan'        => $this->round4($scored['penghasilan']),
            'pekerjaan'          => $this->round4($scored['pekerjaan']),
            'perkawinan'         => $this->round4($scored['perkawinan']),
            'calon_penghuni'     => $this->round4($scored['calon_penghuni']),
            'status_penempatan'  => $this->round4($scored['status_penempatan']),
            // flag & prob
            'kelayakan'          => $keputusan,
            'prob_layak'         => $this->round4($prob_layak),
            'status'             => 1, // data testing
            'created_by'         => (string) (Auth::id() ?? 'guest'),
        ];

        // Logging untuk debug
        logger()->info('payload-training', $payload + [
            '_raw'  => $raw,
            '_min'  => $MIN,
            '_div'  => $DIV,
            '_norm' => $norm,
        ]);

        // 8) Simpan
        $this->model->create($payload);

        // 9) Return ringkas
        return [
            'ticket'     => $payload['ticket'],
            'prob_layak' => $payload['prob_layak'],
            'keputusan'  => 'layak',
            'scored'     => $scored,
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
