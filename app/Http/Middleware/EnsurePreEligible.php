<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePreEligible
{
    public function handle(Request $request, Closure $next)
    {
        if (!(bool) $request->session()->get('pre_eligible', false)) {
            return redirect()->route('pre-eligibility.form')
                ->with('error', 'Silakan isi dan lolos pra-kelayakan terlebih dahulu.');
        }

        return $next($request);
    }
}
