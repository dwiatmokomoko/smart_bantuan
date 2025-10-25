<?php

namespace App\Http\Controllers\feature\bo\sub_criteria;

use App\Http\Controllers\Controller;
use App\Repositories\CriteriaRepository;
use App\Repositories\SubCriteriaRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SubCriteriaController extends Controller
{
    private $menu;
    private SubCriteriaRepository $repository;
    private CriteriaRepository $criteria_repository;

    function __construct(SubCriteriaRepository $repository, CriteriaRepository $criteria_repository)
    {
        $this->menu = "Sub Kriteria";
        $this->repository = $repository;
        $this->criteria_repository = $criteria_repository;
    }

    public function datas(Request $request)
    {
        abort_unless($request->ajax(), 404);

        // JOIN supaya bisa sort/search di server untuk nama kriteria
        $qb = DB::table('sub_criterias')
            ->leftJoin('criterias', 'criterias.id', '=', 'sub_criterias.criteria_id')
            ->select([
                'sub_criterias.id',
                'sub_criterias.criteria_id',
                'sub_criterias.name',
                'sub_criterias.weight',            // sudah persentase asli dari DB
                'criterias.name as criteria_name',
            ]);

        return DataTables::of($qb)
            ->addIndexColumn()
            ->orderColumn('DT_RowIndex', 'sub_criterias.id $1')
            ->editColumn('weight', fn($row) => (int) $row->weight) // tetap angka murni; % ditambahkan di Blade
            ->addColumn('action', function ($row) {
                $idEnc = encrypt($row->id);
                $name = e($row->name);
                return '<form class="d-flex justify-content-center" method="POST" action="' . route('sub-criteria.destroy', $idEnc) . '">'
                    . method_field('DELETE') . csrf_field()
                    . '<a href="' . route('sub-criteria.edit', $idEnc) . '" class="btn btn-outline-warning m-1"><i class="ti ti-edit"></i></a>'
                    . '<button type="button" id="deleteRow" data-message="' . $name . '" class="btn btn-outline-danger m-1"><i class="ti ti-trash"></i></button>'
                    . '</form>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function index()
    {
        $data["menu"] = $this->menu;
        return view('feature.bo.sub_criteria.index', compact("data"));
    }

    public function create()
    {
        $data["menu"] = $this->menu;
        $data["form_status"] = "Tambah ";
        $data["criteria"] = $this->criteria_repository->getAll();
        return view('feature.bo.sub_criteria.form', compact("data"));
    }

    /**
     * Store & Update dalam satu endpoint (repository->store biasanya sudah handle create/update by id).
     * DI SINI: kita pakai *persentase asli* sebagai weight (mis. 25, 33, 50, 67, 75, 100).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "id"          => ['sometimes', 'nullable', 'string'],
            "criteria_id" => ['required', 'string'],          // terenkripsi
            "name"        => ['required', 'string', 'max:255'],
            "weight"      => ['required', 'integer', 'min:0', 'max:100'], // PERSENTASE ASLI
        ], [], [
            "criteria_id" => "Kriteria",
            "name"        => "Nama sub kriteria",
            "weight"      => "Bobot (persen)",
        ]);

        // set created_by / updated_by
        if (!empty($data['id'])) {
            $data["updated_by"] = Auth::user()->id;
        } else {
            $data["created_by"] = Auth::user()->id;
        }

        try {
            // decrypt id & criteria_id
            $data["id"]          = is_null($data["id"]) ? $data["id"] : decrypt($data['id']);
            $data["criteria_id"] = decrypt($data['criteria_id']);

            // Validasi tambahan: batasi nilai bobot yang diperbolehkan per kriteria (opsional tapi disarankan)
            $cid = (string) $data["criteria_id"];
            $allowed = match ($cid) {
                '1', '3' => [10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100],               // contoh: Penghasilan & Perkawinan
                '2', '4' => [10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100],       // contoh: Pekerjaan & Calon Penghuni
                '5'      => [10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100],           // contoh: Status Penempatan
                default  => range(0, 100),           // fallback bebas 0..100
            };

            if (!in_array((int)$data['weight'], $allowed, true)) {
                return back()
                    ->withErrors(['weight' => 'Nilai bobot tidak sesuai dengan ketentuan kriteria terpilih.'])
                    ->withInput();
            }

            // Simpan langsung nilai weight (persentase) apa adanya
            $this->repository->store($data);

            $msg = (!empty($data['id']) ? 'Berhasil mengubah' : 'Berhasil menambah') . ' sub kriteria ' . $data["name"];
            return redirect()->route('sub-criteria.index')->with('success', $msg);

        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi kesalahan saat menyimpan sub kriteria")->withInput($request->input());
        }
    }

    public function edit(string $id)
    {
        $data["menu"]        = $this->menu;
        $data["form_status"] = "Ubah ";
        $data["criteria"]    = $this->criteria_repository->getAll();
        $data["record"]      = $this->repository->getById(decrypt($id));
        return view('feature.bo.sub_criteria.form', compact("data"));
    }

    public function update(Request $request, string $id)
    {
        // Tidak digunakan karena store() sudah handle create/update via repository->store
        abort(404);
    }

    public function destroy(string $id)
    {
        try {
            $this->repository->destroy(decrypt($id));
            return redirect()->route('sub-criteria.index')->with('success', 'Sub kriteria berhasil dihapus');
        } catch (Exception $e) {
            if (env('APP_DEBUG')) {
                return $e->getMessage();
            }
            return back()->with('error', "Oops..!! Terjadi kesalahan saat menghapus sub kriteria");
        }
    }
}
