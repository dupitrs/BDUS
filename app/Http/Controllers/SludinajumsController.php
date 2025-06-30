<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Sludinajums;
use App\Models\Pieteikums;
use App\Models\User;



class SludinajumsController extends Controller
{
    public function create()
    {
        // Tikai adminam
        if (!Auth::guard('admin')->check()) {
            abort(403);
        }

        return view('admin.sludinajumi.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nosaukums' => 'required|string|max:255',
            'apraksts' => 'required|string',
            'norises_datums' => 'required|date',
            'bilde' => 'nullable|image|max:2048',
        ]);
    
        $sludinajums = new Sludinajums($validated);
        $sludinajums->is_visible = true;
    
        if ($request->hasFile('bilde')) {
            $sludinajums->bilde = $request->file('bilde')->store('sludinajumi', 'public');
        }
    
        $sludinajums->save();
    
        return redirect()->route('admin.sludinajumi.create')->with('success', 'Sludinājums pievienots!');
    }
    

    public function edit($id)
    {
        $sludinajums = DB::table('sludinajums')->where('id', $id)->first();

        if (!$sludinajums) {
            abort(404);
        }

        return view('admin.sludinajumi.edit', compact('sludinajums'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nosaukums' => 'required|string|max:255',
            'apraksts' => 'required|string',
            'norises_datums' => 'required|date',
            'bilde' => 'nullable|image|max:2048',
        ]);

        $sludinajums = DB::table('sludinajums')->where('id', $id)->first();

        if (!$sludinajums) {
            abort(404);
        }

        if ($request->hasFile('bilde')) {
            $validated['bilde'] = $request->file('bilde')->store('sludinajumi', 'public');
        }

        DB::table('sludinajums')->where('id', $id)->update($validated);

        return redirect()->route('dashboard')->with('success', 'Sludinājums atjaunināts!');
    }

    public function destroy($id)
    {
        DB::table('parskats')->where('ID', $id)->delete();

        DB::table('pieteikums')->where('ID', $id)->delete();
        
        $sludinajums = DB::table('sludinajums')->where('id', $id)->first();

        if ($sludinajums->bilde) {
            Storage::disk('public')->delete($sludinajums->bilde);
        }

        DB::table('sludinajums')->where('id', $id)->delete();

        return redirect()->route('dashboard')->with('success', 'Sludinājums izdzēsts!');
    }

    public function hide($id)
    {
        $sludinajums = Sludinajums::findOrFail($id);
        $sludinajums->visible = false;
        $sludinajums->save();

        return redirect()->route('dashboard')->with('success', 'Sludinājums paslēpts.');
    }

    // Parāda visus slēptos
    public function hidden()
    {
        $sludinajumi = Sludinajums::where('is_visible', false)->latest()->get();
        return view('admin.sludinajumi.hidden', compact('sludinajumi'));
    }

    // Atkārtoti parāda sludinājumu
    public function unhide($id)
    {
        $sludinajums = Sludinajums::findOrFail($id);
        $sludinajums->is_visible = true;
        $sludinajums->save();

        return redirect()->route('admin.sludinajumi.hidden')->with('success', 'Sludinājums ir atkārtoti parādīts!');
    }

    public function dashboard()
    {
        $sludinajumi = Sludinajums::where('is_visible', true)
            ->orderBy('norises_datums', 'desc')
            ->get();

        $pieteikumi = DB::table('pieteikums')->get();

        foreach ($sludinajumi as $sludinajums) {
            // Iegūst lietotājus, kas pieteikušies
            $sludinajums->pieteikusies = DB::table('pieteikums')
                ->join('lietotajs', 'pieteikums.lietotaja_epasts', '=', 'lietotajs.lietotaja_epasts')
                ->where('pieteikums.ID', $sludinajums->ID)
                ->where('pieteikums.statuss', 1)
                ->select('lietotajs.vards', 'lietotajs.uzvards', 'lietotajs.lietotaja_epasts')
                ->get();

            // Vai lietotājs jau pieteicies
            $sludinajums->user_pieteicies = $pieteikumi->contains(function ($p) use ($sludinajums) {
                return $p->ID == $sludinajums->ID
                    && $p->lietotaja_epasts === optional(auth()->user())->lietotaja_epasts
                    && $p->statuss == 1;
            });

            // Pieteikšanās iespējama līdz 17:00 dienu pirms norises datuma
            $deadline = \Carbon\Carbon::parse($sludinajums->norises_datums)->subDay()->setTime(17, 0, 0);
            $sludinajums->can_apply = now()->lessThanOrEqualTo($deadline);
        }

        return view('dashboard', compact('sludinajumi', 'pieteikumi'));
    }

    
    
    
    
    
    
    public function pieteikties($id)
    {
        if (!Auth::check()) {
            return redirect()->route('dashboard')->with('error', 'Jābūt pieslēgtam, lai pieteiktos.');
        }
    
        $lietotajs = Auth::user();
        $epasts = $lietotajs->lietotaja_epasts;
    
        $pieteikums = DB::table('pieteikums')
            ->where('ID', $id)
            ->where('lietotaja_epasts', $epasts)
            ->first();
    
        if ($pieteikums) {
            // Ja jau eksistē, pārslēdz statusu
            DB::table('pieteikums')
                ->where('ID', $id)
                ->where('lietotaja_epasts', $epasts)
                ->update(['statuss' => $pieteikums->statuss == 1 ? 0 : 1]);
        } else {
            // Ja vēl nav, izveido jaunu ierakstu
            DB::table('pieteikums')->insert([
                'ID' => $id,
                'lietotaja_epasts' => $epasts,
                'statuss' => 1,
            ]);
        }
    
        return redirect()->route('dashboard')->with('success', '');
    }
    



    
}
