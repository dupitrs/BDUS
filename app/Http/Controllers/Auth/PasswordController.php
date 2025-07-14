<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // ← Šis ir vajadzīgs!
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->parole)) {
            return back()->withErrors(['current_password' => 'Nepareiza parole!']);
        }

        $user->update([
            'parole' => Hash::make($request->password),
        ]);

        return back()->with('status', 'password-updated');
    }
}
