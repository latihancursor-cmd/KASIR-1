<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KunciSesi
{
    public function handle(Request $request, Closure $next)
    {
        $idle = (int) config('sinopat.kunci_idle_menit', 60) * 60;
        $last = $request->session()->get('last_activity', time());
        if (time() - $last > $idle) {
            $request->session()->put('locked', true);
            return redirect()->route('lock.screen');
        }
        $request->session()->put('last_activity', time());
        return $next($request);
    }
}
