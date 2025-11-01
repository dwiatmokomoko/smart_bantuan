<?php

namespace App\Http\Controllers\feature\fo\count;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\SubCriteriaRepository;
use App\Services\SmartRocService;

class CountController extends Controller
{
    private $menu = "Counter";
    private SubCriteriaRepository $subCriteriaRepository;
    private SmartRocService $service;

    public function __construct(SubCriteriaRepository $subCriteriaRepository, SmartRocService $service)
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
            'jenis_kelamin'     => 'required|string',
            'penghasilan'       => 'required|numeric',
            'pekerjaan'         => 'required|numeric',
            'perkawinan'        => 'required|numeric',
            'calon_penghuni'    => 'required|numeric',
            'status_penempatan' => 'required|numeric',
            // name & nik tidak divalidasi dari client
        ]);

        $user = Auth::guard('web')->user();

        // override identitas dari DB (anti spoof)
        $validated['name'] = $user->name;
        $validated['nik']  = $user->nik;

        // pastikan angka benar-benar float
        foreach (['penghasilan','pekerjaan','perkawinan','calon_penghuni','status_penempatan'] as $k) {
            $validated[$k] = (float) $validated[$k];
        }

        $res = $this->service->train($validated); // menghasilkan ticket

        session()->flash('success', 'Perhitungan selesai. Silakan unggah berkas.');
        return redirect()->route('fo.berkas.create', ['ticket' => $res['ticket']]);
    }
}
