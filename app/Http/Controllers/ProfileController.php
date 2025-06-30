<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'vards' => ['required', 'string', 'max:50'],
            'uzvards' => ['required', 'string', 'max:50'],
            'personas_kods' => ['required', 'string', 'max:50'],
            'adrese' => ['required', 'string', 'max:50'],
            'lietotaja_epasts' => ['required', 'email', 'max:50', 'unique:lietotajs,lietotaja_epasts,'.Auth::user()->lietotaja_epasts.',lietotaja_epasts'],
        ]);

        $user = Auth::user();

        $user->vards = $request->vards;
        $user->uzvards = $request->uzvards;
        $user->personas_kods = $request->personas_kods;
        $user->adrese = $request->adrese;

        if ($user->lietotaja_epasts !== $request->lietotaja_epasts) {
            $user->lietotaja_epasts = $request->lietotaja_epasts;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
public function destroy(Request $request): RedirectResponse
{
    $request->validate([
        'password' => ['required'],
    ]);

    $user = Auth::user();

    if (! Hash::check($request->password, $user->parole)) {
        return Redirect::route('profile.edit')->withErrors(['password' => 'Nepareiza parole!']);
    }

    // 🔥 Izdzēš visus saistītos ierakstus
    \DB::table('pieteikums')->where('lietotaja_epasts', $user->lietotaja_epasts)->delete();
    \DB::table('parskats')->where('lietotaja_epasts', $user->lietotaja_epasts)->delete(); // ← pievienots

    // Atslēdz lietotāju
    Auth::logout();

    // Izdzēš kontu
    $user->delete();

    // Beidz sesiju
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return Redirect::to('/');
}

    
}
