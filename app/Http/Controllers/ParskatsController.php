<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Parskats;
use App\Models\Sludinajums;
use Illuminate\Validation\Rule;

class ParskatsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user_type = session('user_type');
    
        if ($user_type === 'admin') {
            $ieraksti = DB::table('parskats')
                ->join('lietotajs', 'parskats.lietotaja_epasts', '=', 'lietotajs.lietotaja_epasts')
                ->join('sludinajums', 'parskats.ID', '=', 'sludinajums.ID')
                ->select('parskats.*', 'lietotajs.vards', 'lietotajs.uzvards', 'sludinajums.nosaukums', 'sludinajums.norises_datums')
                ->get();
        } else {
            $ieraksti = DB::table('parskats')
                ->join('lietotajs', 'parskats.lietotaja_epasts', '=', 'lietotajs.lietotaja_epasts') // ← pievienojam!
                ->join('sludinajums', 'parskats.ID', '=', 'sludinajums.ID')
                ->where('parskats.lietotaja_epasts', $user->lietotaja_epasts)
                ->select('parskats.*', 'lietotajs.vards', 'lietotajs.uzvards', 'sludinajums.nosaukums', 'sludinajums.norises_datums')
                ->get();
        }
    
        $sludinajumi = DB::table('sludinajums')
            ->where('is_visible', 1)
            ->select('ID', 'nosaukums')
            ->get();
    
        return view('parskats', compact('ieraksti', 'user_type', 'sludinajumi'));
    }
    
    

    public function store(Request $request)
    {
        $request->validate([
            'ID' => 'required|integer',
            'stundas' => 'required|integer',
            'padaritais_darbs' => 'required|string|max:255',
        ]);
    
        $userEmail = Auth::user()->lietotaja_epasts;
    
        $exists = DB::table('parskats')
            ->where('lietotaja_epasts', $userEmail)
            ->where('ID', $request->ID)
            ->exists();
    
        if ($exists) {
            return redirect()->route('parskats.index')->with('success', 'Jūs jau esat iesniedzis ierakstu šim pasākumam.');
        }
    
        DB::table('parskats')->insert([
            'lietotaja_epasts' => $userEmail,
            'ID' => $request->ID,
            'stundas' => $request->stundas,
            'padaritais_darbs' => $request->padaritais_darbs,
            'statuss' => 'gaida apstiprinājumu',
        ]);
    
        return redirect()->route('parskats.index')->with('success', 'Ieraksts pievienots!');
    }
    


    public function updateStatus(Request $request, $epasts, $id)
    {
        $request->validate([
            'statuss' => ['required', Rule::in(['Apliecināts', 'Noraidīts', 'gaida apstiprinājumu'])],
        ]);
    
        DB::table('parskats')
            ->where('lietotaja_epasts', $epasts)
            ->where('ID', $id)
            ->update(['statuss' => $request->statuss]);
    
        return redirect()->route('parskats.index')->with('success', 'Statuss atjaunināts!');
    }

    public function destroy($epasts, $id)
    {
        DB::table('parskats')->where('lietotaja_epasts', $epasts)->where('ID', $id)->delete();
        return redirect()->route('parskats.index')->with('success', 'Ieraksts dzēsts!');
    }

    
    
}

