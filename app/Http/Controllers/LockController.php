<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LockController extends Controller
{
    public function show()
    {
        return view('auth.lock');
    }

    public function unlock(Request $request)
    {
        $request->validate(['password' => 'required']);
        if (Auth::attempt(['email' => Auth::user()->email, 'password' => $request->input('password')])) {
            $request->session()->forget('locked');
            return redirect()->intended('/');
        }
        return back()->withErrors(['password' => 'Salah']);
    }
}
