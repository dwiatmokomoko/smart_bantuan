<?php

namespace App\Http\Controllers\feature\fo\pengajuan;

use App\Http\Controllers\Controller;
use App\Models\Data_training;
use App\Models\UserBerkas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengajuanDetailController extends Controller
{
    // Halaman detail kriteria & sub-kriteria yg dipilih (berdasar ticket)
    public function kriteria(string $ticket)
    {
        $userId = Auth::id();

        // Ambil satu record data_training milik user (berdasar ticket)
        $dt = Data_training::query()
            ->where('ticket', $ticket)
            ->where('created_by', (string)$userId)   // kamu simpan created_by sebagai string "2"
            ->first();

        abort_unless($dt, 404, 'Data kriteria tidak ditemukan.');

        // input_label disimpan JSON string
        $labels = [];
        if (!empty($dt->input_label)) {
            $decoded = json_decode($dt->input_label, true);
            if (is_array($decoded)) {
                $labels = $decoded;
            }
        }

        // Siapkan meta ringkas (opsional)
        $meta = [
            'name'   => $dt->name ?? '-',
            'nik'    => $dt->nik ?? '-',
            'ticket' => $dt->ticket ?? '-',
            'prob'   => $dt->prob_layak ?? null,
            'kelas'  => $dt->kelayakan == 1 ? 'LAYAK' : 'TIDAK LAYAK',
        ];

        return view('feature.fo.pengajuan.kriteria', compact('labels', 'meta'));
    }

    // Halaman detail berkas
    public function berkas(string $ticket)
    {
        $userId = Auth::id();

        $ub = UserBerkas::query()
            ->where('ticket', $ticket)
            ->where('user_id', $userId)
            ->first();

        abort_unless($ub, 404, 'Berkas tidak ditemukan.');

        // Buat daftar file yg tersedia
        $files = [];
        if ($ub->ktp_path)  $files[] = ['label' => 'KTP', 'url' => Storage::url($ub->ktp_path), 'cls' => 'pill-ktp'];
        if ($ub->kk_path)   $files[] = ['label' => 'KK', 'url' => Storage::url($ub->kk_path), 'cls' => 'pill-kk'];
        if ($ub->surat_belum_memiliki_rumah_path)
            $files[] = ['label' => 'Surat Perny. Belum Memiliki Rumah', 'url' => Storage::url($ub->surat_belum_memiliki_rumah_path), 'cls' => 'pill-sp'];
        if ($ub->slip_gaji_path)
            $files[] = ['label' => 'Slip Gaji / Surat Penghasilan', 'url' => Storage::url($ub->slip_gaji_path), 'cls' => 'pill-sg'];
        if ($ub->skck_path) $files[] = ['label' => 'SKCK', 'url' => Storage::url($ub->skck_path), 'cls' => 'pill-sk'];

        $meta = [
            'ticket' => $ub->ticket,
            'status' => $ub->status,
            'notes'  => $ub->notes,
            'created_at' => $ub->created_at,
        ];

        return view('feature.fo.pengajuan.berkas', compact('files', 'meta'));
    }
}
