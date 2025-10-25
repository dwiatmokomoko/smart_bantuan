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
            ->editColumn('prob_layak', function ($row) {
                return is_null($row->prob_layak) ? '-' : number_format((float)$row->prob_layak, 6, '.', '');
            })
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
                $url = route('admin.submissions.show', $row->ub_id);
                return '<a href="' . $url . '" class="btn btn-sm btn-outline-primary"><i class="ti ti-eye"></i> Detail</a>';
            })
            ->rawColumns(['berkas_status', 'action'])
            ->toJson();
    }

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

        $statusBadge = match ($rec->berkas_status) {
            'approved' => '<span class="badge bg-success">Menunggu Verifikasi</span>',
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

        // 🔧 penting: topbar butuh $data['menu']
        $data["menu"] = $this->menu;

        return view('feature.bo.submissions.show', [
            'data' => $data,
            'rec' => $rec,
            'statusBadge' => $statusBadge,
            'files' => $files,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $updated = DB::table('user_berkas')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now(),
            ]);

        abort_unless($updated, 404, 'Pengajuan tidak ditemukan.');

        return response()->json(['message' => 'Status berkas diperbarui ke: ' . $request->status]);
    }

}
