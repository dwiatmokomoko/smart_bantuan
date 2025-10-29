<?php

namespace App\Http\Controllers\feature\bo\submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SubmissionController extends Controller
{
    private string $menu;

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

        $dateFrom = $request->input('date_from'); // format YYYY-MM-DD
        $dateTo   = $request->input('date_to');

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
                'ub.notes',
                'ub.created_at',
            ]);

        if ($dateFrom && $dateTo) {
            $qb->whereBetween(DB::raw('DATE(ub.created_at)'), [$dateFrom, $dateTo]);
        } elseif ($dateFrom) {
            $qb->whereDate('ub.created_at', '>=', $dateFrom);
        } elseif ($dateTo) {
            $qb->whereDate('ub.created_at', '<=', $dateTo);
        }

        $qb->orderByDesc('ub.created_at');

        return DataTables::of($qb)
            ->addIndexColumn()
            ->editColumn('prob_layak', function ($row) {
                if (is_null($row->prob_layak)) return '-';
                // kirim numeric untuk sorting, tampil 6 desimal saat display
                $val = (float)$row->prob_layak;
                return number_format($val, 6, '.', '');
            })
            ->editColumn('berkas_status', function ($row) {
                $map = [
                    'pending'  => '<span class="badge bg-warning">Menunggu Verifikasi</span>',
                    'approved' => '<span class="badge bg-success">Disetujui</span>',
                    'rejected' => '<span class="badge bg-danger">Ditolak</span>',
                ];
                return $map[$row->berkas_status] ?? e($row->berkas_status);
            })
            ->editColumn('created_at', fn($r) => $r->created_at ?: '-')
            ->addColumn('action', function ($row) {
                $detailUrl = route('admin.submissions.show', $row->ub_id, false);
                $statusUrl = route('admin.submissions.status', $row->ub_id, false);

                // format nomor HP -> 62
                $hp = preg_replace('/\D+/', '', (string) ($row->no_hp ?? ''));
                if ($hp !== '') {
                    if (str_starts_with($hp, '0')) $hp = '62' . substr($hp, 1);
                    $hp = ltrim($hp, '+');
                }

                $ticket = $row->ticket ?? '-';

                // TEMPLATE WA
                $pesanApproved =
"Hallo, Bapak/Ibu {$row->user_name} 👋
Kami informasikan bahwa status pengajuan Anda telah disetujui untuk melanjutkan ke tahap wawancara calon penghuni Rusunawa (Tiket {$ticket}).

Saat proses wawancara, silakan membawa berkas-berkas berikut:
- Fotokopi KTP
- Fotokopi KK
- Fotokopi Surat Nikah/Akta cerai hidup/Akta kematian
- Surat Pernyataan Belum Memiliki Rumah
- Surat Pernyataan Penghasilan / Slip Gaji
- SKCK yang masih berlaku
- Pas Foto ukuran 4 x 6 masing-masing anggota keluarga

Jadwal wawancara akan kami informasikan lebih lanjut melalui pesan WhatsApp ini.
Terima kasih atas perhatian dan kerja samanya 🙏

Salam,
Tim Pengelola Rusunawa Kota Yogyakarta";

                // notes akan disisipkan di front-end saat status=rejected (jika sudah ada)
                $alasan = trim((string)($row->notes ?? ''));
                $alasanTxt = $alasan !== '' ? $alasan : '[alasan penolakan]';
                $pesanRejected =
"Hallo, Bapak/Ibu {$row->user_name} 🙏
Kami informasikan bahwa status pengajuan Anda belum dapat disetujui.

Berdasarkan hasil verifikasi berkas, pengajuan belum memenuhi kriteria karena {$alasanTxt}.

Terima kasih atas pengertian dan perhatiannya 🙏

Salam,
Tim Pengelola Rusunawa Kota Yogyakarta";

                $pesan = match ($row->berkas_status) {
                    'approved' => $pesanApproved,
                    'rejected' => $pesanRejected,
                    default    => "Hallo, Bapak/Ibu {$row->user_name} 🙏
Terima kasih telah mengajukan permohonan Rusunawa (Tiket {$ticket}). Berkas Anda sudah kami terima dan sedang diverifikasi. Kami akan menghubungi Anda kembali setelah proses selesai. Terima kasih 🙏",
                };

                $waBtn = '';
                if ($hp !== '') {
                    $waUrl = 'https://wa.me/' . $hp . '?text=' . urlencode($pesan);
                    $waBtn = '<a href="' . $waUrl . '" target="_blank" class="btn btn-sm btn-outline-success me-1">
                                <i class="fa-brands fa-whatsapp"></i>
                              </a>';
                }

                // tombol approve (langsung submit)
                $approveForm = '<form method="POST" action="' . $statusUrl . '" class="d-inline">'
                    . csrf_field()
                    . '<input type="hidden" name="status" value="approved">'
                    . '<button type="submit" class="btn btn-sm btn-success me-1" title="Setujui"><i class="ti ti-check"></i></button>'
                    . '</form>';

                // tombol reject -> buka modal agar isi keterangan
                $rejectBtn = '<button type="button" class="btn btn-sm btn-danger me-1 btn-reject"
                                   data-id="'.$row->ub_id.'"
                                   data-name="'.e($row->user_name).'"
                                   data-hp="'.$hp.'"
                                   data-ticket="'.e($ticket).'"
                                   data-notes="'.e($alasan).'"
                                   title="Tolak">
                                   <i class="ti ti-x"></i>
                              </button>';

                $detailBtn = '<a href="' . $detailUrl . '" class="btn btn-sm btn-outline-primary" title="Detail"><i class="ti ti-eye"></i></a>';

                return $approveForm . $rejectBtn . $waBtn . $detailBtn;
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
            'approved' => '<span class="badge bg-success">Disetujui</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            default    => '<span class="badge bg-warning">Menunggu Verifikasi</span>',
        };

        $files = [
            ['label' => 'KTP', 'path' => $rec->ktp_path],
            ['label' => 'KK', 'path' => $rec->kk_path],
            ['label' => 'Surat Belum Memiliki Rumah', 'path' => $rec->surat_belum_memiliki_rumah_path],
            ['label' => 'Slip Gaji / Surat Penghasilan', 'path' => $rec->slip_gaji_path],
            ['label' => 'SKCK', 'path' => $rec->skck_path],
        ];

        $data["menu"] = $this->menu;

        return view('feature.bo.submissions.show', compact('data','rec','statusBadge','files'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'notes'  => ['nullable', 'string', 'max:2000'],
        ]);

        DB::table('user_berkas')
            ->where('id', $id)
            ->update([
                'status'     => $data['status'],
                'notes'      => $data['notes'] ?? null,
                'updated_at' => now(),
            ]);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }
        return back()->with('success', 'Status berkas diperbarui.');
    }
}
