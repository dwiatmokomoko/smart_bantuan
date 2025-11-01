<?php
// app/Http/Controllers/feature/bo/user/FoUserController.php
namespace App\Http\Controllers\feature\bo\user;

use App\Http\Controllers\Controller;
use App\Repositories\FoUserRepository;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FoUserController extends Controller
{
    private string $menu = 'Data Pengguna (User)';
    private FoUserRepository $repo;

    public function __construct(FoUserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        $data['menu'] = $this->menu;
        return view('feature.bo.user.fo_index', compact('data'));
    }

    public function datas(Request $request)
    {
        abort_unless($request->ajax(), 404);

        try {
            $rows = $this->repo->getAll();
        } catch (Exception $e) {
            if (config('app.debug')) throw $e;
            return DataTables::of(collect())->make(true);
        }

        return DataTables::of($rows)
            ->addIndexColumn()
            ->editColumn('created_at', fn($r) => $r->created_at?->format('Y-m-d H:i:s'))
            ->addColumn('action', function ($row) {
                $idEnc = encrypt($row->id);
                return '<form class="d-flex justify-content-center" method="POST" action="'.route('fo-users.destroy',$idEnc).'">'
                    . method_field('DELETE') . csrf_field()
                    // Opsional tombol edit data user (jika ingin)
                    // . '<a href="'.route('admin.fo-users.edit',$idEnc).'" class="btn btn-outline-warning m-1"><i class="ti ti-edit"></i></a>'
                    . '<button type="button" id="deleteRow" data-message="'.e($row->name).'" class="btn btn-outline-danger m-1"><i class="ti ti-trash"></i></button>'
                    . '</form>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit(string $id)
    {
        // Opsional – kalau mau form edit user warga
        $rec = $this->repo->getById(decrypt($id));
        $data['menu'] = $this->menu;
        return view('feature.bo.user.fo_form', compact('data','rec'));
    }

    public function destroy(string $id)
    {
        $this->repo->destroy((int) decrypt($id));
        return redirect()->route('fo-users.index')->with('success','User berhasil dihapus.');
    }
}
