<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function index () {        
        
        return view('auth.login');
    }

    public function login (Request $request) {

        $validated = $request->validate([
            'email'     => ['required', 'max:190'],
            'password'  => ['required', 'max:190'],
        ]);
        
        $remember = $request->boolean('remember');
        
        // Login
        if (Auth::attempt($validated, $remember)):
            $user = Auth::user();
            $request->session()->regenerate();

            return redirect()->route('dashboard.index');
        endif;
    
        return redirect()
            ->route('auth.login')
            ->withInput()
            ->with('error', ['Email ou senha inválidos.']);
    }

    public function logout (Request $request) {
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
