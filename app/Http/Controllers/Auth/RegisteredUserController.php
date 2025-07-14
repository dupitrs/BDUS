<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'vards' => ['required', 'string', 'max:50'],
            'uzvards' => ['required', 'string', 'max:50'],
            'personas_kods' => ['required', 'string', 'max:50'],
            'adrese' => ['required', 'string', 'max:50'],
            'lietotaja_epasts' => ['required', 'string', 'email', 'max:50', 'unique:lietotajs,lietotaja_epasts'],
            'parole' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'vards' => $request->vards,
            'uzvards' => $request->uzvards,
            'personas_kods' => $request->personas_kods,
            'adrese' => $request->adrese,
            'lietotaja_epasts' => $request->lietotaja_epasts,
            'parole' => Hash::make($request->parole),
        ]);

       

        return redirect()->route('login')->with('status', 'registration-successful');
    }
}
