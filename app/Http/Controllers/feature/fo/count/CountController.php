<?php

namespace App\Http\Controllers\feature\fo\count;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CriteriaRepository;
use App\Repositories\SubCriteriaRepository;
use App\Services\SmartRocService;
use Illuminate\Support\Facades\Log;

class CountController extends Controller
{
    private $menu;
    private SubCriteriaRepository $subCriteriaRepository;
    private SmartRocService $service;

    public function __construct(SubCriteriaRepository $subCriteriaRepository, SmartRocService $service)
    {
        $this->menu = "Counter";
        $this->subCriteriaRepository = $subCriteriaRepository;
        $this->service = $service;
    }
    public function filterInline(Request $request)
    {
        $request->validate([
            'is_warga_yogya' => 'required|in:ya,tidak',
            'is_pns_tni_polri' => 'required|in:ya,tidak',
            'memiliki_rumah' => 'required|in:ya,tidak',
            'is_menikah' => 'required|in:ya,tidak',
        ]);

        if (
            $request->is_warga_yogya === 'tidak' &&
            $request->is_pns_tni_polri === 'ya' &&
            $request->memiliki_rumah === 'ya' &&
            $request->is_menikah === 'tidak'
        ) {
            return redirect()->back()->withInput()->with('error_filter', 'Mohon maaf, Anda tidak termasuk dalam kriteria penerima program RUSUNAWA');
        }

        session(['lolos_filter' => true]);
        return redirect()->route('fo.count.index');
    }

    public function index()
    {
        $subCriterias = $this->subCriteriaRepository->getAll();
        return view('feature.fo.count.index', compact('subCriterias'));
    }


    public function predict(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'nik' => 'required|numeric',
            'jenis_kelamin' => 'required|string',
            'penghasilan' => 'required|numeric',
            'pekerjaan' => 'required|numeric',
            'perkawinan' => 'required|numeric',
            'calon_penghuni' => 'required|numeric',
            'status_penempatan' => 'required|numeric',
        ]);

        // jalankan perhitungan & penyimpanan (service yg sudah kamu update)
        $res = $this->service->train($validated);   // $res['ticket'] tersedia

        // agar ada notifikasi di halaman upload
        session()->flash('success', 'Perhitungan selesai. Silakan unggah berkas.');

        // ⬅️ langsung ke halaman Upload Berkas sambil membawa ticket
        return redirect()->route('fo.berkas.create', ['ticket' => $res['ticket']]);
    }


}
