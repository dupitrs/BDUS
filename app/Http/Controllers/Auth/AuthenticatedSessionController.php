<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
    
        $request->session()->regenerate();
    
        logger('Lietotājs pieslēdzās!');
        return redirect('/dashboard'); // <- vienmēr uz dashboard
    }
    
    

    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else {
            Auth::logout(); // parastais lietotājs
        }
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/');
    }
    
    
}
