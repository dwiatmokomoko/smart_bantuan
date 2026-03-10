<?php

namespace App\Http\Controllers\feature\bo\submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SubmissionController extends Controller
{
    private $menu = 'Pengajuan';

    public function index()
    {
        $data['menu'] = $this->menu;
        return view('feature.bo.submissions.index', compact('data'));
    }

    public function data(Request $request)
    {
        abort_unless($request->ajax(), 404);

        $qb = DB::table('user_berkas as ub')
            ->join('users as u', 'u.id', '=', 'ub.user_id')
            ->leftJoin('data_trainings as dt', 'dt.ticket', '=', 'ub.ticket')
            ->selectRaw("
                ub.id as ub_id,
                u.name as user_name,
                u.email,
                u.nik,
                u.no_hp,
                ub.ticket,
                ub.status as berkas_status,
                ub.notes,
                ub.created_at,
                dt.kelayakan,
                dt.prob_layak
            ");

        // filter tanggal (opsional) dari request
        if ($request->filled('date_from')) {
            $qb->whereDate('ub.created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $qb->whereDate('ub.created_at', '<=', $request->date_to);
        }

        // order custom: jika minta sort by prob_layak
        if ($request->filled('order_prob') && $request->order_prob === 'desc') {
            $qb->orderByDesc('dt.prob_layak');
        } elseif ($request->filled('order_prob') && $request->order_prob === 'asc') {
            $qb->orderBy('dt.prob_layak');
        } else {
            $qb->orderByDesc('ub.created_at');
        }

        return DataTables::of($qb)
            ->addIndexColumn()
            ->editColumn('prob_layak', function ($row) {
                return is_null($row->prob_layak) ? '-' : number_format((float) $row->prob_layak, 4, '.', '');
            })
            ->addColumn('kelas', function ($row) {
                if (is_null($row->prob_layak))
                    return '-';
                $p = (float) $row->prob_layak;
                if ($p <= 0.4500)
                    return '<span class="badge bg-secondary">Kurang direkomendasikan</span>';
                if ($p <= 0.7000)
                    return '<span class="badge bg-info">Cukup direkomendasikan</span>';
                return '<span class="badge bg-success">Direkomendasikan</span>';
            })
            ->editColumn('berkas_status', function ($row) {
                return match ($row->berkas_status) {
                    'approved' => '<span class="badge bg-success">Disetujui</span>',
                    'rejected' => '<span class="badge bg-danger">Ditolak</span>',
                    'interview' => '<span class="badge bg-primary">Wawancara</span>',
                    default => '<span class="badge bg-warning text-dark">Pengajuan</span>',
                };
            })
            ->editColumn('created_at', fn($r) => $r->created_at ? $r->created_at : '-')
            ->addColumn('action', function ($row) {
                $detailUrl = route('admin.submissions.show', $row->ub_id, false);
                $statusUrl = route('admin.submissions.status', $row->ub_id, false);

                // Normalisasi HP -> 62xxxxxxxxxx
                $hp = preg_replace('/\D+/', '', (string) ($row->no_hp ?? ''));
                if ($hp !== '') {
                    if (str_starts_with($hp, '0'))
                        $hp = '62' . substr($hp, 1);
                    $hp = ltrim($hp, '+');
                }

                $ticket = $row->ticket ?? '-';
                $nama = $row->user_name ?? '-';
                $alasan = trim((string) ($row->notes ?? ''));

                // Pesan WA:
                $waMsgRejected = "Halo, Bapak/Ibu {$nama} 🙏%0AKami informasikan bahwa status pengajuan Anda belum dapat disetujui.%0A%0ABerdasarkan hasil verifikasi berkas, pengajuan belum memenuhi kriteria karena {$alasan}.%0A%0ATerima kasih atas pengertian dan perhatiannya 🙏%0A%0ASalam,%0ATim Pengelola Rusunawa Kota Yogyakarta";
                $waMsgInterview = "Halo, Bapak/Ibu {$nama} 👋%0AKami informasikan bahwa status pengajuan Anda telah disetujui untuk melanjutkan ke tahap wawancara calon penghuni Rusunawa (Tiket {$ticket}).%0A%0ASaat proses wawancara, silahkan membawa berkas-berkas berikut ini:%0A- Fotokopi KTP%0A- Fotokopi KK%0A- Fotokopi Surat Nikah/Akta cerai hidup/Akta kematian%0A- Surat Pernyataan Belum Memiliki Rumah%0A- Surat Pernyataan Penghasilan / Slip Gaji%0A- SKCK yang masih berlaku%0A- Pas Foto ukuran 4 x 6 masing-masing anggota keluarga%0A%0AUntuk jadwal wawancara, akan kami informasikan lebih lanjut melalui pesan WhatsApp ini.%0ATerima kasih atas perhatian dan kerjasamanya 🙏%0A%0ASalam,%0ATim Pengelola Rusunawa Kota Yogyakarta";
                $waMsgPending = "Halo {$nama}, terima kasih telah mengajukan permohonan RUSUNAWA (Tiket {$ticket}). Berkas Anda kami terima dan sedang proses verifikasi. 🙏";
                $waMsgApproved = "Halo {$nama}, pengajuan RUSUNAWA (Tiket {$ticket}) telah DISETUJUI. Terima kasih. 🙏";

                $waText = $waMsgPending;
                if ($row->berkas_status === 'interview')
                    $waText = $waMsgInterview;
                elseif ($row->berkas_status === 'rejected')
                    $waText = $waMsgRejected;
                elseif ($row->berkas_status === 'approved')
                    $waText = $waMsgApproved;

                $waBtn = ($hp !== '')
                    ? '<a href="https://wa.me/' . $hp . '?text=' . $waText . '" target="_blank" class="btn btn-sm btn-outline-success me-1"><i class="fa-brands fa-whatsapp"></i></a>'
                    : '';

                // Tombol perubahan status:
                // pengajuan -> wawancara
                $interviewForm = '<form method="POST" action="' . $statusUrl . '" class="d-inline">'
                    . csrf_field()
                    . '<input type="hidden" name="status" value="interview">'
                    . '<button type="submit" class="btn btn-sm btn-primary me-1" title="Ubah ke Wawancara"><i class="ti ti-users"></i></button>'
                    . '</form>';

                // wawancara -> approved
                $approveForm = '<form method="POST" action="' . $statusUrl . '" class="d-inline">'
                    . csrf_field()
                    . '<input type="hidden" name="status" value="approved">'
                    . '<button type="submit" class="btn btn-sm btn-success me-1" title="Setujui"><i class="ti ti-check"></i></button>'
                    . '</form>';

                // tolak (pakai notes)
                $rejectForm = '<form method="POST" action="' . $statusUrl . '" class="d-inline frm-reject" data-id="' . $row->ub_id . '">'
                    . csrf_field()
                    . '<input type="hidden" name="status" value="rejected">'
                    . '<input type="hidden" name="notes" value="">'
                    . '<button type="button" class="btn btn-sm btn-danger btn-reject" title="Tolak"><i class="ti ti-x"></i></button>'
                    . '</form>';

                $detailBtn = '<a href="' . $detailUrl . '" class="btn btn-sm btn-outline-primary" title="Detail"><i class="ti ti-eye"></i></a>';

                return $interviewForm . $approveForm . $rejectForm . $waBtn . $detailBtn;
            })
            ->addColumn('notes_show', fn($row) => e($row->notes ?? '-'))
            ->rawColumns(['berkas_status', 'kelas', 'action'])
            ->toJson();
    }

    // app/Http/Controllers/feature/bo/submission/SubmissionController.php

    public function show($id)
    {
        // Ambil record utama + hasil hitung + RAW ( *_raw ) + input_label
        $rec = DB::table('user_berkas as ub')
            ->join('users as u', 'u.id', '=', 'ub.user_id')
            ->leftJoin('data_trainings as dt', 'dt.ticket', '=', 'ub.ticket')
            ->select([
                'ub.id as ub_id',
                'ub.user_id',
                'ub.ticket',
                'ub.ktp_path',
                'ub.kk_path',
                'ub.surat_belum_memiliki_rumah_path',
                'ub.slip_gaji_path',
                'ub.skck_path',
                'ub.status as berkas_status',
                'ub.notes',
                'ub.created_at as ub_created_at',

                'u.name as user_name',
                'u.email',
                'u.nik',
                'u.no_hp',

                'dt.id as dt_id',
                'dt.kelayakan',
                'dt.prob_layak',
                'dt.created_at as dt_created_at',

                // RAW & label json (6 kriteria baru)
                'dt.pekerjaan_raw',
                'dt.status_hubungan_keluarga_raw',
                'dt.data_kependudukan_sinkron_raw',
                'dt.anggota_keluarga_bpjs_raw',
                'dt.anggota_keluarga_luar_raw',
                'dt.kependudukan_wilayah_pbi_raw',
                'dt.input_label',
            ])
            ->where('ub.id', $id)
            ->first();

        abort_unless($rec, 404);

        // Badge status berkas (tanpa perubahan)
        $statusBadge = match ($rec->berkas_status) {
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            'interview' => '<span class="badge bg-primary">Wawancara</span>',
            default => '<span class="badge bg-warning text-dark">Pengajuan</span>',
        };

        // ===== Klasifikasi dari prob_layak =====
        $klasifikasi = null;
        if (!is_null($rec->prob_layak)) {
            $p = (float) $rec->prob_layak;
            if ($p <= 0.4500) {
                $klasifikasi = ['Kurang direkomendasikan', 'secondary'];
            } elseif ($p <= 0.7000) {
                $klasifikasi = ['Cukup direkomendasikan', 'info'];
            } else {
                $klasifikasi = ['Direkomendasikan', 'success'];
            }
        }
        $klasifikasiBadge = $klasifikasi
            ? '<span class="badge bg-' . $klasifikasi[1] . '">' . $klasifikasi[0] . '</span>'
            : '-';

        // ===== Siapkan label sub-kriteria =====
        // 1) Jika kolom JSON input_label terisi, pakai itu dulu
        $labels = null;
        if (!empty($rec->input_label)) {
            $parsed = json_decode($rec->input_label, true);
            if (is_array($parsed)) {
                // Normalisasi key agar konsisten dgn view (6 kriteria baru)
                $labels = [
                    'pekerjaan' => $parsed['pekerjaan'] ?? null,
                    'status_hubungan_keluarga' => $parsed['status_hubungan_keluarga'] ?? null,
                    'data_kependudukan_sinkron' => $parsed['data_kependudukan_sinkron'] ?? null,
                    'anggota_keluarga_bpjs' => $parsed['anggota_keluarga_bpjs'] ?? null,
                    'anggota_keluarga_luar' => $parsed['anggota_keluarga_luar'] ?? null,
                    'kependudukan_wilayah_pbi' => $parsed['kependudukan_wilayah_pbi'] ?? null,
                ];
            }
        }

        // 2) Jika JSON tidak ada/kurang lengkap, fallback berdasarkan RAW weight
        $fetchLabel = function (int $criteriaId, $weight) {
            if ($weight === null) {
                return null;
            }

            // RAW di data_trainings disimpan decimal(8,2) → cast ke integer
            // contoh "50.00" → 50 agar cocok dengan sub_criterias.weight (INT)
            $w = is_numeric($weight) ? (int) round((float) $weight) : $weight;

            return DB::table('sub_criterias')
                ->where('criteria_id', $criteriaId)
                ->where('weight', $w)
                ->value('name');
        };

        // jika labels masih null atau ada yang kosong, lengkapi dari RAW
        $labels = $labels ?? [];
        $labels['pekerjaan'] = $labels['pekerjaan'] ?? $fetchLabel(1, $rec->pekerjaan_raw);
        $labels['status_hubungan_keluarga'] = $labels['status_hubungan_keluarga'] ?? $fetchLabel(2, $rec->status_hubungan_keluarga_raw);
        $labels['data_kependudukan_sinkron'] = $labels['data_kependudukan_sinkron'] ?? $fetchLabel(3, $rec->data_kependudukan_sinkron_raw);
        $labels['anggota_keluarga_bpjs'] = $labels['anggota_keluarga_bpjs'] ?? $fetchLabel(4, $rec->anggota_keluarga_bpjs_raw);
        $labels['anggota_keluarga_luar'] = $labels['anggota_keluarga_luar'] ?? $fetchLabel(5, $rec->anggota_keluarga_luar_raw);
        $labels['kependudukan_wilayah_pbi'] = $labels['kependudukan_wilayah_pbi'] ?? $fetchLabel(6, $rec->kependudukan_wilayah_pbi_raw);

        // Apakah ada salah satu RAW yang terisi (untuk menampilkan baris "Raw: xx" di view)
        $hasAnyRaw = (
            !is_null($rec->pekerjaan_raw) ||
            !is_null($rec->status_hubungan_keluarga_raw) ||
            !is_null($rec->data_kependudukan_sinkron_raw) ||
            !is_null($rec->anggota_keluarga_bpjs_raw) ||
            !is_null($rec->anggota_keluarga_luar_raw) ||
            !is_null($rec->kependudukan_wilayah_pbi_raw)
        );

        // Berkas untuk panel kanan (tanpa perubahan)
        $files = [
            ['label' => 'KTP', 'path' => $rec->ktp_path],
            ['label' => 'KK', 'path' => $rec->kk_path],
            ['label' => 'Surat Belum Memiliki Rumah', 'path' => $rec->surat_belum_memiliki_rumah_path],
            ['label' => 'Slip Gaji / Surat Penghasilan', 'path' => $rec->slip_gaji_path],
            ['label' => 'SKCK', 'path' => $rec->skck_path],
        ];

        $data['menu'] = $this->menu;

        return view(
            'feature.bo.submissions.show',
            compact(
                'data',
                'rec',
                'statusBadge',
                'files',
                'labels',
                'hasAnyRaw',
                'klasifikasiBadge'
            )
        );
    }



    // app/Http/Controllers/feature/bo/submission/SubmissionController.php

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pengajuan,interview,approved,rejected'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::table('user_berkas')->where('id', $id)->update([
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
            'updated_at' => now(),
        ]);

        return $request->wantsJson()
            ? response()->json(['ok' => true])
            : back()->with('success', 'Status berkas diperbarui.');
    }

}
