<?php

namespace App\Http\Controllers\feature\bo\submission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

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
                'ub.notes', // <-- keterangan
                'ub.created_at',
            ]);

        // Filter by tanggal pengajuan (optional)
        $start = $request->get('start_date');
        $end   = $request->get('end_date');
        if ($start || $end) {
            // aman-kan format dan tutup rentang ke akhir hari
            $startDate = $start ? Carbon::parse($start)->startOfDay() : Carbon::minValue();
            $endDate   = $end   ? Carbon::parse($end)->endOfDay()   : Carbon::maxValue();
            $qb->whereBetween('ub.created_at', [$startDate, $endDate]);
        }

        $qb->orderByDesc('ub.created_at');

        return DataTables::of($qb)
            ->addIndexColumn()
            ->editColumn('prob_layak', fn($r) => is_null($r->prob_layak) ? '-' : number_format((float)$r->prob_layak, 6, '.', ''))
            ->editColumn('berkas_status', function ($row) {
                $map = [
                    'pending'  => '<span class="badge bg-warning">Menunggu Verifikasi</span>',
                    'approved' => '<span class="badge bg-success">Disetujui</span>',
                    'rejected' => '<span class="badge bg-danger">Ditolak</span>',
                ];
                return $map[$row->berkas_status] ?? e($row->berkas_status);
            })
            ->editColumn('notes', fn($r) => e($r->notes ?? '-'))
            ->editColumn('created_at', fn($r) => $r->created_at ?: '-')
            ->addColumn('action', function ($row) {
                $detailUrl = route('admin.submissions.show', $row->ub_id, false);
                $statusUrl = route('admin.submissions.status', $row->ub_id, false);

                // normalisasi nomor HP -> 62
                $hp = preg_replace('/\D+/', '', (string)($row->no_hp ?? ''));
                if ($hp !== '') {
                    if (str_starts_with($hp, '0')) $hp = '62' . substr($hp, 1);
                    $hp = ltrim($hp, '+');
                }

                // template WA
                $ticket = $row->ticket ?? '-';
                $nama   = $row->user_name ?? 'Bapak/Ibu';
                $pesanApproved = "Halo, Bapak/Ibu {$nama} 👋%0AKami informasikan bahwa status pengajuan Anda telah disetujui untuk melanjutkan ke tahap wawancara calon penghuni Rusunawa.%0A%0ASaat proses wawancara, silahkan membawa berkas-berkas berikut ini:%0A- Fotokopi KTP%0A- Fotokopi KK%0A- Fotokopi Surat Nikah/Akta cerai hiduop/ Akta kematian%0A- Surat Pernyataan Belum Memiliki Rumah%0A- Surat Pernyataan Penghasilan / Slip Gaji%0A- SKCK yang masih berlaku%0A- Pas Foto ukuran 4 x 6 masing-masing anggota keluarga%0A%0AUntuk jadwal wawancara, akan kami informasikan lebih lanjut melalui pesan WhatsApp ini.%0ATerima kasih atas perhatian dan kerjasamanya 🙏%0A%0ASalam,%0ATim Pengelola Rusunawa Kota Yogyakarta";
                // rejected: alasan diambil dari notes
                $alasan = trim((string)($row->notes ?? ''));
                if ($alasan === '') $alasan = 'alasan penolakan akan kami sampaikan kembali.';
                $pesanRejected = "Halo, Bapak/Ibu {$nama} 🙏%0AKami informasikan bahwa status pengajuan Anda belum dapat disetujui.%0A%0ABerdasarkan hasil verifikasi berkas, pengajuan belum memenuhi kriteria karena {$alasan}.%0A%0ATerima kasih atas pengertian dan perhatiannya 🙏%0A%0ASalam,%0ATim Pengelola Rusunawa Kota Yogyakarta";
                $pesanPending  = "Halo {$nama}, terima kasih telah mengajukan permohonan RUSUNAWA dengan tiket {$ticket}. Berkas Anda sudah kami terima dan saat ini sedang dalam proses verifikasi. Kami akan menghubungi Anda kembali setelah proses selesai. Terima kasih 🙏";

                $waBtn = '';
                if ($hp !== '') {
                    $msg = $pesanPending;
                    if ($row->berkas_status === 'approved') $msg = $pesanApproved;
                    if ($row->berkas_status === 'rejected') $msg = $pesanRejected;
                    $waUrl = "https://wa.me/{$hp}?text={$msg}";
                    $waBtn = '<a href="'.$waUrl.'" target="_blank" class="btn btn-sm btn-outline-success me-1">'
                           . '<i class="fa-brands fa-whatsapp"></i></a>';
                }

                // tombol ubah status (approve cepat) -> POST
                $statusUrl = e($statusUrl);
                $approveBtn = '<form method="POST" action="'.$statusUrl.'" class="d-inline">'
                            . csrf_field()
                            . '<input type="hidden" name="status" value="approved">'
                            . '<button type="submit" class="btn btn-sm btn-success me-1" title="Setujui">'
                            . '<i class="ti ti-check"></i></button></form>';

                // tombol tolak -> buka modal keterangan
                $rejectBtn = '<button type="button" class="btn btn-sm btn-danger me-1 btn-reject"'
                           . ' data-id="'.$row->ub_id.'" data-url="'.$statusUrl.'" data-nama="'.e($row->user_name).'"'
                           . ' title="Tolak dengan keterangan"><i class="ti ti-x"></i></button>';

                $detailBtn = '<a href="'.$detailUrl.'" class="btn btn-sm btn-outline-primary" title="Detail">'
                           . '<i class="ti ti-eye"></i></a>';

                return $approveBtn.$rejectBtn.$waBtn.$detailBtn;
            })
            ->rawColumns(['berkas_status','action'])
            ->toJson();
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
