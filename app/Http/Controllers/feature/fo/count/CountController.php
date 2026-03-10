<?php

namespace App\Http\Controllers\feature\fo\count;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\SubCriteriaRepository;
use App\Services\SmartService;

class CountController extends Controller
{
    private $menu = "Counter";
    private SubCriteriaRepository $subCriteriaRepository;
    private SmartService $service;

    public function __construct(SubCriteriaRepository $subCriteriaRepository, SmartService $service)
    {
        // $this->middleware('auth:web'); // pastikan user login
        $this->subCriteriaRepository = $subCriteriaRepository;
        $this->service = $service;
    }

    public function filterInline(Request $request)
    {
        $request->validate([
            'is_warga_yogya'   => 'required|in:ya,tidak',
            'is_pns_tni_polri' => 'required|in:ya,tidak',
            'memiliki_rumah'   => 'required|in:ya,tidak',
            'is_menikah'       => 'required|in:ya,tidak',
        ]);

        if ($request->is_warga_yogya === 'tidak'
            && $request->is_pns_tni_polri === 'ya'
            && $request->memiliki_rumah === 'ya'
            && $request->is_menikah === 'tidak') {
            return back()->withInput()->with('error_filter',
                'Mohon maaf, Anda tidak termasuk dalam kriteria penerima program RUSUNAWA');
        }

        session(['lolos_filter' => true]);
        return redirect()->route('fo.count.index');
    }

    public function index()
    {
        $user = Auth::guard('web')->user();
        $subCriterias = $this->subCriteriaRepository->getAll();
        return view('feature.fo.count.index', compact('subCriterias','user'));
    }

    public function predict(Request $request)
    {
        // validasi opsi yang dipilih user
        $validated = $request->validate([
            'jenis_kelamin'                => 'required|string',
            'pekerjaan'                    => 'required|numeric',
            'status_hubungan_keluarga'     => 'required|numeric',
            'data_kependudukan_sinkron'    => 'required|numeric',
            'anggota_keluarga_bpjs'        => 'required|numeric',
            'anggota_keluarga_luar'        => 'required|numeric',
            'kependudukan_wilayah_pbi'     => 'required|numeric',
            // name & nik tidak divalidasi dari client
        ]);

        $user = Auth::guard('web')->user();

        // override identitas dari DB (anti spoof)
        $validated['name'] = $user->name;
        $validated['nik']  = $user->nik;

        // pastikan angka benar-benar float
        foreach (['pekerjaan','status_hubungan_keluarga','data_kependudukan_sinkron','anggota_keluarga_bpjs','anggota_keluarga_luar','kependudukan_wilayah_pbi'] as $k) {
            $validated[$k] = (float) $validated[$k];
        }

        $res = $this->service->train($validated); // menghasilkan ticket

        // Simpan hasil ke session untuk ditampilkan di result page
        session([
            'prediction_result' => $res,
            'data_input' => $validated,
        ]);

        return redirect()->route('fo.count.result', ['ticket' => $res['ticket']]);
    }

    public function result(Request $request)
    {
        $ticket = $request->query('ticket');
        $result = session('prediction_result');
        $data_input = session('data_input');

        if (!$result || !$data_input || $result['ticket'] !== $ticket) {
            return redirect()->route('fo.count.index')->with('error', 'Data tidak ditemukan');
        }

        $prob_layak = $result['prob_layak'];
        $input_label = $result['labels'];

        return view('feature.fo.count.result', compact('ticket', 'prob_layak', 'input_label', 'data_input'));
    }
}
