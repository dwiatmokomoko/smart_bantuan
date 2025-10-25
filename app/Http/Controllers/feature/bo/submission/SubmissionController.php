<?php

namespace App\Http\Controllers\feature\bo\submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SubmissionController extends Controller
{
    private $menu;

    public function __construct()
    {
        $this->menu = "Pengajuan";
    }

    public function index()
    {
        $data["menu"] = $this->menu;
        return view('feature.bo.submissions.index', compact("data"));
    }

    // app/Http/Controllers/feature/bo/submission/SubmissionController.php

    public function data(Request $request)
    {
        abort_unless($request->ajax(), 404);

        $qb = DB::table('user_berkas as ub')
            ->join('users as u', 'u.id', '=', 'ub.user_id')
            ->leftJoin('data_trainings as dt', 'dt.ticket', '=', 'ub.ticket')
            ->select([
                'ub.id as ub_id',
                'u.name as user_name',
                'u.email',
                'u.nik',
                'u.no_hp',
                'ub.ticket',
                DB::raw("COALESCE(dt.kelayakan, NULL) as kelayakan"),
                DB::raw("COALESCE(dt.prob_layak, NULL) as prob_layak"),
                'ub.status as berkas_status',
                'ub.created_at',
            ])
            ->orderByDesc('ub.created_at');

        return DataTables::of($qb)
            ->addIndexColumn()
            ->editColumn('kelayakan', function ($row) {
                if (is_null($row->kelayakan))
                    return '-';
                return ((int) $row->kelayakan === 1 ? 'LAYAK' : 'TIDAK LAYAK');
            })
            ->editColumn('prob_layak', fn($row) => is_null($row->prob_layak) ? '-' : number_format((float) $row->prob_layak, 6, '.', ''))
            ->editColumn('berkas_status', function ($row) {
                $map = [
                    'pending' => '<span class="badge bg-warning">Menunggu Verifikasi</span>',
                    'approved' => '<span class="badge bg-success">Disetujui</span>',
                    'rejected' => '<span class="badge bg-danger">Ditolak</span>',
                ];
                return $map[$row->berkas_status] ?? e($row->berkas_status);
            })
            ->editColumn('created_at', fn($r) => $r->created_at ?: '-')
            ->addColumn('action', function ($row) {
                $detailUrl = route('admin.submissions.show', $row->ub_id);

                // format nomor HP -> 62
                $hp = preg_replace('/\D+/', '', (string) ($row->no_hp ?? ''));
                if ($hp !== '') {
                    if (str_starts_with($hp, '0'))
                        $hp = '62' . substr($hp, 1);
                    $hp = ltrim($hp, '+');
                }

                // template pesan WA berdasarkan status
                $ticket = $row->ticket ?? '-';
                $pesanPending = "Halo {$row->user_name}, terima kasih telah mengajukan permohonan RUSUNAWA dengan tiket {$ticket}. "
                    . "Berkas Anda sudah kami terima dan saat ini sedang dalam proses verifikasi. "
                    . "Kami akan menghubungi Anda kembali setelah proses selesai. Terima kasih 🙏";

                $pesanApproved = "Halo {$row->user_name}, selamat! 🎉 Permohonan RUSUNAWA Anda (tiket {$ticket}) telah DISETUJUI. "
                    . "Silakan menunggu informasi lanjutan terkait tahapan berikutnya. "
                    . "Terima kasih atas kepercayaannya.";

                $pesanRejected = "Halo {$row->user_name}, terima kasih atas pengajuan RUSUNAWA (tiket {$ticket}). "
                    . "Kami mohon maaf, saat ini pengajuan Anda BELUM DAPAT DISETUJUI. "
                    . "Anda dapat memperbarui data/berkas dan mengajukan kembali di lain kesempatan. "
                    . "Terima kasih atas pengertiannya.";

                $pesan = $pesanPending;
                if ($row->berkas_status === 'approved')
                    $pesan = $pesanApproved;
                if ($row->berkas_status === 'rejected')
                    $pesan = $pesanRejected;

                $waBtn = '';
                if ($hp !== '') {
                    $waUrl = 'https://wa.me/' . $hp . '?text=' . urlencode($pesan);
                    $waBtn = '<a href="' . $waUrl . '" target="_blank" class="btn btn-sm btn-outline-success me-1">'
                        . '<i class="fa fa-whatsapp"></i> WA</a>';
                }

                // form POST: approved & rejected
                $approveForm = '<form method="POST" action="' . route('admin.submissions.status', $row->ub_id) . '" class="d-inline">'
                    . csrf_field()
                    . '<input type="hidden" name="status" value="approved">'
                    . '<button type="submit" class="btn btn-sm btn-success me-1"><i class="ti ti-check"></i></button>'
                    . '</form>';

                $rejectForm = '<form method="POST" action="' . route('admin.submissions.status', $row->ub_id) . '" class="d-inline">'
                    . csrf_field()
                    . '<input type="hidden" name="status" value="rejected">'
                    . '<button type="submit" class="btn btn-sm btn-danger me-1"><i class="ti ti-x"></i></button>'
                    . '</form>';

                $detailBtn = '<a href="' . $detailUrl . '" class="btn btn-sm btn-outline-primary">'
                    . '<i class="ti ti-eye"></i></a>';

                return $approveForm . $rejectForm . $waBtn . $detailBtn;
            })
            ->rawColumns(['berkas_status', 'action'])
            ->toJson();
    }

    // app/Http/Controllers/feature/bo/submission/SubmissionController.php

    public function show($id)
    {
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
            ])
            ->where('ub.id', $id)
            ->first();

        abort_unless($rec, 404);

        // ✅ perbaiki label
        $statusBadge = match ($rec->berkas_status) {
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            default => '<span class="badge bg-warning">Menunggu Verifikasi</span>',
        };

        $files = [
            ['label' => 'KTP', 'path' => $rec->ktp_path],
            ['label' => 'KK', 'path' => $rec->kk_path],
            ['label' => 'Surat Belum Memiliki Rumah', 'path' => $rec->surat_belum_memiliki_rumah_path],
            ['label' => 'Slip Gaji / Surat Penghasilan', 'path' => $rec->slip_gaji_path],
            ['label' => 'SKCK', 'path' => $rec->skck_path],
        ];

        $data["menu"] = $this->menu;

        return view('feature.bo.submissions.show', [
            'data' => $data,
            'rec' => $rec,
            'statusBadge' => $statusBadge,
            'files' => $files,
        ]);
    }


    // public function updateStatus(Request $request, $id)
    // {
    //     $request->validate([
    //         'status' => 'required|in:pending,approved,rejected',
    //     ]);

    //     $updated = DB::table('user_berkas')
    //         ->where('id', $id)
    //         ->update([
    //             'status' => $request->status,
    //             'updated_at' => now(),
    //         ]);

    //     abort_unless($updated, 404, 'Pengajuan tidak ditemukan.');

    //     return response()->json(['message' => 'Status berkas diperbarui ke: ' . $request->status]);
    // }

    // app/Http/Controllers/feature/bo/submission/SubmissionController.php

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        // update status user_berkas
        DB::table('user_berkas')
            ->where('id', $id)
            ->update([
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
                'updated_at' => now(),
            ]);

        // kalau dipanggil dari DataTables, kita bisa balas JSON
        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'Status berkas diperbarui.');
    }


}
