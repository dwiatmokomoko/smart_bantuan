<?php


// app/Http/Controllers/feature/fo/pengajuan/PengajuanController.php
namespace App\Http\Controllers\feature\fo\pengajuan;

use App\Http\Controllers\Controller;
use App\Models\UserBerkas;

class PengajuanController extends Controller
{
    // Contoh method history() di FO
    public function history()
    {
        $user = auth('web')->user();

        $berkas = \App\Models\UserBerkas::where('user_id', $user->id)
            ->latest()->get();

        $labelsByTicket = [];
        if ($berkas->isNotEmpty()) {
            $tickets = $berkas->pluck('ticket')->filter()->unique()->values();
            $trainRows = \App\Models\Data_training::whereIn('ticket', $tickets)
                ->get(['ticket', 'input_label']);
            foreach ($trainRows as $tr) {
                $labelsByTicket[$tr->ticket] = $tr->input_label
                    ? json_decode($tr->input_label, true) : null;
            }
        }

        return view('feature.fo.pengajuan.history', compact('berkas', 'labelsByTicket'));
    }


}
