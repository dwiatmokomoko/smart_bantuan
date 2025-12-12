<?php

namespace App\Http\Controllers\feature\bo\criteria;

use App\Http\Controllers\Controller;
use App\Repositories\CriteriaRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Criteria;
use Yajra\DataTables\Facades\DataTables;

class CriteriaController extends Controller
{
    private $menu;
    private CriteriaRepository $repository;

    function __construct(CriteriaRepository $repository)
    {
        $this->menu = "Kriteria";
        $this->repository = $repository;
    }

    public function datas(Request $request)
    {
        abort_unless($request->ajax(), 404);

        // Builder, bukan collection
        $query = Criteria::query()->select(['id', 'name', 'weight']);

        return DataTables::eloquent($query)
            ->addIndexColumn()                       // DT_RowIndex (virtual)
            ->orderColumn('DT_RowIndex', 'id $1')    // kalau user klik kolom index, map ke id
            ->editColumn('weight', fn($r) => (int) $r->weight) // kirim angka murni
            ->addColumn('action', function ($r) {
                $idEnc = encrypt($r->id);
                $name = e($r->name);
                return '<form class="d-flex justify-content-center" method="POST" action="' . route('criteria.destroy', $idEnc) . '">'
                    . method_field('DELETE') . csrf_field()
                    . '<a href="' . route('criteria.edit', $idEnc) . '" class="btn btn-outline-warning m-1"><i class="ti ti-edit"></i></a>'
                    . '<button type="button" id="deleteRow" data-message="' . $name . '" class="btn btn-outline-danger m-1"><i class="ti ti-trash"></i></button>'
                    . '</form>';

            })
            ->rawColumns(['action'])
            ->toJson();
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data["menu"] = $this->menu;
        return view('feature.bo.criteria.index', compact("data"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data["menu"] = $this->menu;
        $data["form_status"] = "Tambah";
        return view('feature.bo.criteria.form', compact("data"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "id" => ['sometimes', 'nullable', 'string'],
            "name" => ['required', 'string', 'max:255'],
            "weight" => ['required', 'integer', 'between:0,100']
        ], [], [
            "name" => "Nama Kriteria",
            "weight" => "Bobot Kriteria"
        ]);

        isset($data["id"]) ? $data["updated_by"] = Auth::user()->id : $data["created_by"] = Auth::user()->id;
        $data["id"] = is_null($data["id"]) ? $data["id"] : decrypt($data['id']);
        try {
            $this->repository->store($data);
            return redirect()->route('criteria.index')->with('success', 'Berhasil menambah kriteria' . $data["name"]);
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat menyimpan kriteria")->withInput($request->input);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data["menu"] = $this->menu;
        $data["form_status"] = "Ubah";
        $data["record"] = $this->repository->getById(decrypt($id));
        // dd($data["record"]->toArray());
        return view('feature.bo.criteria.form', compact("data"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $data = $request->validate([
        //     "name" => ['required', 'string', 'max:255'],
        //     "weight" => ['required', 'integer', 'between:0,100']
        // ], [], [
        //     "name" => "Nama Kriteria",
        //     "weight" => "Bobot Kriteria"
        // ]);
        // $data["updated_by"] = Auth::user()->id;

        // // Simpan data menggunakan metode create() pada model Land
        // try {
        //     $this->repository->edit(decrypt($id), $data);
        //     return redirect()->route('criteria.index')->with('success', 'Berhasil mengubah kriteria' . $data["name"]);
        // } catch (Exception $e) {
        //     if (env('APP_DEBUG')) {
        //         return $e->getMessage();
        //     }
        //     return back()->with('error', "Oops..!! Terjadi keesalahan saat menyimpan kriteria")->withInput($request->input);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->repository->destroy(decrypt($id));
            return redirect()->route('criteria.index')->with('success', 'Kriteria berhasil dihapus');
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi keesalahan saat menghapus kriteria");
        }
    }
}
