<?php

namespace App\Services;

use App\Models\Data_training;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SmartRocService
{
    private Data_training $model;

    // Bobot final per kriteria (sesuai permintaan)
    private const W = [
        'penghasilan'       => 0.4566,
        'pekerjaan'         => 0.2567,
        'status_penempatan' => 0.1567,
        'calon_penghuni'    => 0.09,
        'perkawinan'        => 0.04,
    ];

    public function __construct(Data_training $model)
    {
        $this->model = $model;
    }

    /**
     * $dataUji: [
     *   name, nik, jenis_kelamin,
     *   penghasilan, pekerjaan, perkawinan, calon_penghuni, status_penempatan  (semua angka)
     * ]
     */
    public function train(array $dataUji): array
    {
        // Ambil baris MIN dan PEMBAGI dari data_trainings
        // (pastikan 2 baris ini memang ada di DB).
        $rowMin     = (array) DB::table('data_trainings')->where('name', 'MIN')->first() ?: [];
        $rowPembagi = (array) DB::table('data_trainings')->where('name', 'PEMBAGI')->first() ?: [];

        // Helper untuk hitung (x - MIN) / PEMBAGI dengan aman
        $norm = function (string $field, float $x) use ($rowMin, $rowPembagi): float {
            $min = isset($rowMin[$field]) ? (float) $rowMin[$field] : 0.0;
            $div = isset($rowPembagi[$field]) ? (float) $rowPembagi[$field] : 0.0;
            if ($div == 0.0) {
                // bila pembagi 0, anggap 0 agar tidak division by zero
                return 0.0;
            }
            return ($x - $min) / $div;
        };

        // 1) Normalisasi → Xi
        $x1 = $norm('penghasilan',       (float) $dataUji['penghasilan']);
        $x2 = $norm('pekerjaan',         (float) $dataUji['pekerjaan']);
        $x3 = $norm('status_penempatan', (float) $dataUji['status_penempatan']);
        $x4 = $norm('calon_penghuni',    (float) $dataUji['calon_penghuni']);
        $x5 = $norm('perkawinan',        (float) $dataUji['perkawinan']);

        // 2) Bobotkan → nilai yang DISIMPAN
        $vPenghasilan       = $x1 * self::W['penghasilan'];
        $vPekerjaan         = $x2 * self::W['pekerjaan'];
        $vStatusPenempatan  = $x3 * self::W['status_penempatan'];
        $vCalonPenghuni     = $x4 * self::W['calon_penghuni'];
        $vPerkawinan        = $x5 * self::W['perkawinan'];

        // 3) Probabilitas layak = jumlah bobot
        $probLayak = $vPenghasilan + $vPekerjaan + $vStatusPenempatan + $vCalonPenghuni + $vPerkawinan;

        // 4) Simpan ke DB (kelayakan selalu 1)
        $ticket = $this->generateUniqueTicket();

        $payload = [
            'name'               => $dataUji['name'],
            'ticket'             => $ticket,

            // simpan nilai HASIL (Xi × bobot) sesuai instruksi
            'penghasilan'        => $vPenghasilan,
            'pekerjaan'          => $vPekerjaan,
            'perkawinan'         => $vPerkawinan,
            'calon_penghuni'     => $vCalonPenghuni,
            'status_penempatan'  => $vStatusPenempatan,

            'kelayakan'          => 1,               // semua dinyatakan layak
            'prob_layak'         => $probLayak,
            'status'             => 1,               // data testing
            'created_by'         => (string) (Auth::id() ?? 'guest'),
        ];

        $this->model->create($payload);

        return [
            'ticket'     => $ticket,
            'prob_layak' => $probLayak,
            'saved'      => $payload,
        ];
    }

    private function generateUniqueTicket(): string
    {
        do {
            $ticket = substr(bin2hex(random_bytes(8)), 0, 12);
        } while ($this->model->where('ticket', $ticket)->exists());

        return $ticket;
    }
}
