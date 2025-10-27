<?php

namespace App\Http\Controllers\feature\bo\data_training;

use App\Http\Controllers\Controller;
use App\Repositories\CriteriaRepository;
use App\Repositories\DataTrainingRepository;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DataTrainingController extends Controller
{
    private string $menu;
    private DataTrainingRepository $repository;
    private CriteriaRepository $criteriaRepository;

    public function __construct(DataTrainingRepository $repository, CriteriaRepository $criteriaRepository)
    {
        $this->menu = "Data Latih";
        $this->repository = $repository;
        $this->criteriaRepository = $criteriaRepository;
    }

    public function datas(Request $request)
    {
        abort_unless($request->ajax(), 404);

        try {
            // hanya status = 0 (sudah di repository)
            $data = $this->repository->getDataTraining();
        } catch (Exception $e) {
            // biar error kebaca oleh DataTables devtools
            return response()->json(['error' => $e->getMessage()]);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            // tidak perlu kolom 'kelayakan' lagi
            ->editColumn('prob_layak', fn($row) =>
                is_null($row->prob_layak) ? null : (float) $row->prob_layak
            )
            ->make(true);
    }

    public function index()
    {
        $data["criteria"] = $this->criteriaRepository->getAll()->toArray();
        $data["menu"] = $this->menu;
        return view('feature.bo.data_training.index', compact("data"));
    }
}
