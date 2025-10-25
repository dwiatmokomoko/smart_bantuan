<?php

// app/Http/Controllers/feature/fo/berkas/BerkasController.php
namespace App\Http\Controllers\feature\fo\berkas;

use App\Http\Controllers\Controller;
use App\Models\UserBerkas;
use App\Models\Data_training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BerkasController extends Controller
{
    /**
     * Tampilkan form upload berkas.
     * Param "ticket" (optional) boleh dikirim via query string.
     */
    public function create(Request $request)
    {
        return view('feature.fo.berkas.create', [
            'ticket' => $request->query('ticket') ?? session('last_ticket'),
        ]);
    }

    /**
     * Simpan berkas user.
     * - Ticket diambil berurutan dari: form -> query -> session -> data_trainings terakhir user.
     * - Upsert berdasarkan (user_id, ticket) agar tidak dobel.
     * - Status wajib salah satu dari: pending|approved|rejected (diset ke 'pending').
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ktp'   => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'kk'    => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'surat_belum_memiliki_rumah' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'slip_gaji' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'skck'  => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'ticket'=> ['nullable', 'string', 'max:64'],
        ]);

        $userId = auth('web')->id();
        $dir    = 'berkas/' . $userId;

        // 1) Pastikan punya ticket supaya bisa di-join dengan data_trainings
        $ticket = $validated['ticket']
            ?? $request->query('ticket')
            ?? session('last_ticket');

        if (empty($ticket)) {
            // fallback: ambil ticket dari data_trainings terakhir milik user (created_by disimpan sebagai string id user)
            $latestDt = Data_training::where('created_by', (string) $userId)
                ->whereNotNull('ticket')
                ->orderByDesc('id')
                ->first();
            $ticket = $latestDt?->ticket;
        }

        return DB::transaction(function () use ($request, $userId, $dir, $ticket) {
            $paths = [];

            try {
                // 2) Upload semua file
                $paths = [
                    'ktp_path'  => $request->file('ktp')->store($dir, 'public'),
                    'kk_path'   => $request->file('kk')->store($dir, 'public'),
                    'surat_belum_memiliki_rumah_path' => $request->file('surat_belum_memiliki_rumah')->store($dir, 'public'),
                    'slip_gaji_path' => $request->file('slip_gaji')->store($dir, 'public'),
                    'skck_path' => $request->file('skck')->store($dir, 'public'),
                ];

                // 3) Upsert berdasarkan (user_id, ticket)
                $query = UserBerkas::where('user_id', $userId);
                if ($ticket) {
                    $query->where('ticket', $ticket);
                } else {
                    $query->whereNull('ticket');
                }

                $existing = $query->first();

                if ($existing) {
                    // hapus file lama agar storage rapi
                    $this->deleteIfExists($existing->ktp_path);
                    $this->deleteIfExists($existing->kk_path);
                    $this->deleteIfExists($existing->surat_belum_memiliki_rumah_path);
                    $this->deleteIfExists($existing->slip_gaji_path);
                    $this->deleteIfExists($existing->skck_path);

                    // update
                    $existing->update([
                        'ticket' => $ticket,              // bisa null, tapi lebih baik tidak
                        ...$paths,
                        'status' => 'pending',            // enum valid
                    ]);
                } else {
                    // create
                    UserBerkas::create([
                        'user_id' => $userId,
                        'ticket'  => $ticket,             // jika null, join ke data_trainings tidak akan terjadi
                        ...$paths,
                        'status'  => 'pending',
                    ]);
                }

                // 4) Ticket di session tidak perlu dipakai lagi
                if (session()->has('last_ticket')) {
                    session()->forget('last_ticket');
                }

                return redirect()
                    ->route('fo.pengajuan.history')
                    ->with('success', 'Berkas berhasil diunggah & dicatat. Menunggu verifikasi.');
            } catch (\Throwable $e) {
                // rollback file upload jika error
                foreach ($paths as $p) {
                    $this->deleteIfExists($p);
                }
                throw $e;
            }
        });
    }

    /**
     * Hapus file di disk public jika ada.
     */
    private function deleteIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
