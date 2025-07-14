<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FiltriController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $user_type = session('user_type');

        // Filtri no pieprasījuma
        $meklet_lietotaju = $request->input('meklet_lietotaju');
        $meklet_sludinajumu = $request->input('meklet_sludinajumu');
        $no = $request->input('no');
        $lidz = $request->input('lidz');
        $statuss = $request->input('statuss');

        $sort = $request->input('sort') ?? 'sludinajums.norises_datums';
        $direction = $request->input('direction') === 'desc' ? 'desc' : 'asc';

        // Pamatvaicājums
        $query = DB::table('parskats')
            ->join('sludinajums', 'parskats.ID', '=', 'sludinajums.ID');

        if ($user_type === 'admin') {
            $query->join('lietotajs', 'parskats.lietotaja_epasts', '=', 'lietotajs.lietotaja_epasts')
                ->select(
                    'parskats.*',
                    'sludinajums.nosaukums',
                    'sludinajums.norises_datums',
                    'lietotajs.vards',
                    'lietotajs.uzvards'
                );
        } else {
            $query->where('parskats.lietotaja_epasts', $user->lietotaja_epasts)
                ->join('lietotajs', 'parskats.lietotaja_epasts', '=', 'lietotajs.lietotaja_epasts')
                ->select(
                    'parskats.*',
                    'sludinajums.nosaukums',
                    'sludinajums.norises_datums',
                    'lietotajs.vards',
                    'lietotajs.uzvards'
                );
        }

        // Lietotāja filtrs (admin)
        if ($user_type === 'admin' && $meklet_lietotaju) {
            $query->where(function ($q) use ($meklet_lietotaju) {
                $q->where('lietotajs.vards', 'like', "%{$meklet_lietotaju}%")
                  ->orWhere('lietotajs.uzvards', 'like', "%{$meklet_lietotaju}%")
                  ->orWhere('lietotajs.lietotaja_epasts', 'like', "%{$meklet_lietotaju}%");
            });
        }

        // Sludinājuma filtrs
        if ($meklet_sludinajumu) {
            $query->where('sludinajums.nosaukums', 'like', "%{$meklet_sludinajumu}%");
        }

        // Statusa filtrs
        if ($user_type === 'admin' && $statuss) {
            $query->where('parskats.statuss', $statuss);
        }

        // Datuma filtrs
        if ($no && $lidz) {
            $query->whereBetween('sludinajums.norises_datums', [$no, $lidz]);
        }

        // Kārtošana (tikai pēc datuma)
        $allowedSorts = ['sludinajums.norises_datums'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        }

        // Filtrētie rezultāti
        $ieraksti = $query->get();

        // Kopējās stundas no filtrētajiem datiem (tikai apliecinātās)
        $kopa_stundas = null;
        if ($user_type !== 'admin') {
            $kopa_stundas = $ieraksti
                ->where('statuss', 'Apliecināts')
                ->sum('stundas');
        }

        // Pieejamie sludinājumi
        $sludinajumi = DB::table('sludinajums')
            ->where('is_visible', 1)
            ->select('ID', 'nosaukums')
            ->get();

        return view('parskats', compact(
            'ieraksti',
            'sludinajumi',
            'user_type',
            'meklet_lietotaju',
            'meklet_sludinajumu',
            'no',
            'lidz',
            'statuss',
            'sort',
            'direction',
            'kopa_stundas'
        ));
    }
}
