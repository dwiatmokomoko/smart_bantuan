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
            'status_penempatan' => 'required|numeric'
        ]);

        $inputData = $request->only([
            'name',
            'nik',
            'jenis_kelamin',
            'penghasilan',
            'pekerjaan',
            'perkawinan',
            'calon_penghuni',
            'status_penempatan'
        ]);

        // Dapatkan semua sub-kriteria dari database
        $subCriteriaAll = $this->subCriteriaRepository->getAll();

        // Fungsi helper untuk mendapatkan nama berdasarkan weight dan criteria_id
        $getNameByWeightAndCriteria = function ($weight, $criteriaId) use ($subCriteriaAll) {
            $criteria = $subCriteriaAll->where('weight', $weight)
                ->where('criteria_id', $criteriaId)
                ->first();
            return $criteria ? $criteria->name : 'Data Tidak Ditemukan (Weight: ' . $weight . ')';
        };

        $inputLabel = [
            'penghasilan' => $getNameByWeightAndCriteria($inputData['penghasilan'], 1),
            'pekerjaan' => $getNameByWeightAndCriteria($inputData['pekerjaan'], 2),
            'perkawinan' => $getNameByWeightAndCriteria($inputData['perkawinan'], 3),
            'calon_penghuni' => $getNameByWeightAndCriteria($inputData['calon_penghuni'], 4),
            'status_penempatan' => $getNameByWeightAndCriteria($inputData['status_penempatan'], 5),
        ];

        $result = $this->service->train($inputData);

        return view('feature.fo.count.result', [
            'data_input' => $inputData,
            'input_label' => $inputLabel,
            'keputusan' => $result['keputusan']
        ]);
    }
}
