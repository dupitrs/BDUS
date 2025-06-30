<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LietotajiController extends Controller
{
    public function index(Request $request)
    {
        $no = $request->input('no');
        $lidz = $request->input('lidz');
        $meklet_lietotaju = $request->input('meklet_lietotaju');

        // Pamatvaicājums: visi lietotāji
        $subquery = DB::table('parskats')
            ->join('sludinajums', 'parskats.ID', '=', 'sludinajums.ID')
            ->select('parskats.lietotaja_epasts', DB::raw('SUM(parskats.stundas) as kopa_stundas'))
            ->where('parskats.statuss', '=', 'Apliecināts');

        if ($no && $lidz) {
            $subquery->whereBetween('sludinajums.norises_datums', [$no, $lidz]);
        }

        $subquery->groupBy('parskats.lietotaja_epasts');

        $lietotaji = DB::table('lietotajs')
            ->leftJoinSub($subquery, 'kopsavilkums', function ($join) {
                $join->on('lietotajs.lietotaja_epasts', '=', 'kopsavilkums.lietotaja_epasts');
            })
            ->select(
                'lietotajs.lietotaja_epasts',
                'lietotajs.vards',
                'lietotajs.uzvards',
                'lietotajs.personas_kods',
                'lietotajs.adrese',
                DB::raw('COALESCE(kopsavilkums.kopa_stundas, 0) as kopa_stundas')
            );

        // Filtrs pēc lietotāja
        if ($meklet_lietotaju) {
            $lietotaji->where(function ($q) use ($meklet_lietotaju) {
                $q->where('lietotajs.vards', 'like', "%{$meklet_lietotaju}%")
                  ->orWhere('lietotajs.uzvards', 'like', "%{$meklet_lietotaju}%")
                  ->orWhere('lietotajs.lietotaja_epasts', 'like', "%{$meklet_lietotaju}%");
            });
        }

        $lietotaji = $lietotaji->get();

        return view('admin.lietotaji', compact('lietotaji', 'no', 'lidz', 'meklet_lietotaju'));
    }

    public function destroy($epasts)
    {
        DB::table('apliecinajums')->where('lietotaja_epasts', $epasts)->delete();
        DB::table('pieteikums')->where('lietotaja_epasts', $epasts)->delete();
        DB::table('parskats')->where('lietotaja_epasts', $epasts)->delete();
        DB::table('lietotajs')->where('lietotaja_epasts', $epasts)->delete();

        return redirect()->route('lietotaji.index')->with('success', 'Lietotājs izdzēsts!');
    }
}
