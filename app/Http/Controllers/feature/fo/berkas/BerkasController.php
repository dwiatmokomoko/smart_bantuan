<?php

// app/Http/Controllers/feature/fo/berkas/BerkasController.php
namespace App\Http\Controllers\feature\fo\berkas;

use App\Http\Controllers\Controller;
use App\Models\UserBerkas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BerkasController extends Controller
{
    public function create(Request $request)
    {
        // kirim ticket jika ada di query string
        return view('feature.fo.berkas.create', [
            'ticket' => $request->query('ticket'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ktp' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'kk' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'surat_belum_memiliki_rumah' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'slip_gaji' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'skck' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'ticket' => ['nullable', 'string', 'max:64'],
        ]);

        $userId = auth('web')->id();
        $dir = 'berkas/' . $userId;

        return DB::transaction(function () use ($request, $validated, $userId, $dir) {
            $paths = [
                'ktp_path' => $request->file('ktp')->store($dir, 'public'),
                'kk_path' => $request->file('kk')->store($dir, 'public'),
                'surat_belum_memiliki_rumah_path' => $request->file('surat_belum_memiliki_rumah')->store($dir, 'public'),
                'slip_gaji_path' => $request->file('slip_gaji')->store($dir, 'public'),
                'skck_path' => $request->file('skck')->store($dir, 'public'),
            ];

            UserBerkas::create([
                'user_id' => $userId,
                'ticket' => $validated['ticket'] ?? null,
                ...$paths,
                'status' => 'Menunggu verifikasi',
            ]);

            return redirect()
                ->route('fo.pengajuan.history')
                ->with('success', 'Berkas berhasil diunggah & dicatat. Menunggu verifikasi.');
        });
    }
}


