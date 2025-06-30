<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile', [
            'user' => Auth::guard('admin')->user(),
        ]);
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'vards' => ['required', 'string', 'max:50'],
            'uzvards' => ['required', 'string', 'max:50'],
            'administratora_epasts' => ['required', 'email', 'max:50', 'unique:administrators,administratora_epasts,' . $admin->administratora_epasts . ',administratora_epasts'],
        ]);

        $admin->vards = $request->vards;
        $admin->uzvards = $request->uzvards;

        if ($admin->administratora_epasts !== $request->administratora_epasts) {
            $admin->administratora_epasts = $request->administratora_epasts;
        }

        $admin->save();

        return Redirect::route('admin.profile')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();
    
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    
        if (!Hash::check($request->current_password, $admin->parole)) {
            return back()->withErrors([
                'current_password' => 'Nepareiza parole.',
            ]);
        }
    
        $admin->parole = Hash::make($request->password);
        $admin->save();
    
        // Šeit Laravelam tiek pateikts, lai adminu "notur" iekšā pēc paroles maiņas
        Auth::guard('admin')->login($admin);
    
        return Redirect::route('admin.profile')->with('status', 'password-updated');
    }
    

    public function destroy(Request $request)
    {
        $admin = Auth::guard('admin')->user();
    
        $request->validate([
            'password' => ['required'],
        ]);
    
        if (!Hash::check($request->password, $admin->parole)) {
            return back()->withErrors([
                'password' => 'Nepareiza parole.',
            ]);
        }
    
        // Izrakstās pirms dzēšanas!
        Auth::guard('admin')->logout();
    
        // Dzēš lietotāju
        $admin->delete();
    
        // Sesijas tīrīšana
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/')->with('status', 'profile-deleted');
    }
    
}

