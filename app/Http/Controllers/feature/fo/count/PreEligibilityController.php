<?php

namespace App\Http\Controllers\feature\fo\count;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreEligibilityController extends Controller
{
    public function check(Request $request)
    {
        $data = $request->validate([
            'is_warga_kota' => ['required', 'in:ya,tidak'],
            'is_aparat' => ['required', 'in:ya,tidak'],
            'is_punya_rumah' => ['required', 'in:ya,tidak'],
            'is_menikah' => ['required', 'in:ya,tidak'],
        ]);

        $lolos = $data['is_warga_kota'] === 'ya'
            && $data['is_aparat'] === 'tidak'
            && $data['is_punya_rumah'] === 'tidak'
            && $data['is_menikah'] === 'ya';

        if (!$lolos) {
            return redirect()
                ->route('pre-eligibility.form')
                ->withInput()
                ->with('error', 'Mohon maaf, Anda tidak termasuk dalam kriteria penerima program RUSUNAWA');
        }

        // Set flag pra-kelayakan & simpan jawaban (opsional)
        $request->session()->put('pre_eligible', true);
        $request->session()->put('pre_eligibility_payload', $data);

        // Arahkan ke halaman register (baru bisa diakses jika pre_eligible = true)
        return redirect()->route('user.register');
    }

}
