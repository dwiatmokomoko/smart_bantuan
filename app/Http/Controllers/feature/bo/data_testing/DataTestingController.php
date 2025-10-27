<?php

namespace App\Http\Controllers\feature\bo\data_testing;

use App\Http\Controllers\Controller;
use App\Repositories\CriteriaRepository;
use App\Repositories\DataTestingRepository;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DataTestingController extends Controller
{
    private string $menu;
    private DataTestingRepository $repository;
    private CriteriaRepository $criteriaRepository;

    public function __construct(DataTestingRepository $repository, CriteriaRepository $criteriaRepository)
    {
        $this->menu = "Data Uji";
        $this->repository = $repository;
        $this->criteriaRepository = $criteriaRepository;
    }

    public function datas(Request $request)
    {
        abort_unless($request->ajax(), 404);

        try {
            // hanya status = 1
            $data = $this->repository->getDataTesting();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            // Tampilkan prob_layak sebagai float (6 desimal), tanpa kolom kelayakan
            ->editColumn('prob_layak', fn($row) =>
                is_null($row->prob_layak) ? null : (float) $row->prob_layak
            )
            ->make(true);
    }

    public function index()
    {
        $data["criteria"] = $this->criteriaRepository->getAll()->toArray();
        $data["menu"] = $this->menu;
        return view('feature.bo.data_testing.index', compact("data"));
    }
}
