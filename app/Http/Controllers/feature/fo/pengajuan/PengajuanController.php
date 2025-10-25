<?php


// app/Http/Controllers/feature/fo/pengajuan/PengajuanController.php
namespace App\Http\Controllers\feature\fo\pengajuan;

use App\Http\Controllers\Controller;
use App\Models\UserBerkas;

class PengajuanController extends Controller
{
    public function history()
    {
        $berkas = UserBerkas::where('user_id', auth('web')->id())
                    ->latest()->get();

        return view('feature.fo.pengajuan.history', compact('berkas'));
    }
}
