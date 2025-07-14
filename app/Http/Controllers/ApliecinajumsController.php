<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use Carbon\Carbon;

class ApliecinajumsController extends Controller
{
    // Rāda apliecinājuma izveides formu
    public function index()
    {
        $lietotaji = DB::table('lietotajs')->get();
        return view('admin.apliecinajums', compact('lietotaji'));
    }

    // Ģenerē PDF un saglabā to
    public function generate(Request $request)
    {
        $request->validate([
            'lietotaja_epasts' => 'required|email',
            'no' => 'required|date',
            'lidz' => 'required|date|after_or_equal:no',
        ]);

        $lietotajs = DB::table('lietotajs')->where('lietotaja_epasts', $request->lietotaja_epasts)->first();
        $admin = Auth::guard('admin')->user();

        if (!$lietotajs || !$admin) {
            return back()->withErrors(['fail' => 'Lietotājs vai administrators nav atrasts.']);
        }

        $kopa_stundas = DB::table('parskats')
            ->join('sludinajums', 'parskats.ID', '=', 'sludinajums.ID')
            ->where('parskats.lietotaja_epasts', $request->lietotaja_epasts)
            ->where('parskats.statuss', 'Apliecināts')
            ->whereBetween('sludinajums.norises_datums', [$request->no, $request->lidz])
            ->sum('parskats.stundas');

        $dati = [
            'vards' => $lietotajs->vards,
            'uzvards' => $lietotajs->uzvards,
            'personas_kods' => $lietotajs->personas_kods,
            'no' => $request->no,
            'lidz' => $request->lidz,
            'stundas' => $kopa_stundas,
        ];

        $pdf = PDF::loadView('admin.apliecinajums-pdf', $dati);

        $faila_nosaukums = 'apliecinajums_' . str_replace(' ', '_', $lietotajs->vards . '_' . $lietotajs->uzvards) . '_' . now()->format('Ymd_His') . '.pdf';
        $pdf_path = 'apliecinajumi/' . $faila_nosaukums;

        Storage::put('public/' . $pdf_path, $pdf->output());

        DB::table('apliecinajums')->insert([
            'izveides_datums' => now()->toDateString(),
            'administratora_epasts' => $admin->administratora_epasts,
            'lietotaja_epasts' => $lietotajs->lietotaja_epasts,
            'pdf_path' => $pdf_path,
        ]);

        return redirect()->route('apliecinajums.vesture')->with('success', 'Apliecinājums veiksmīgi izveidots un saglabāts!');
    }

    // Rāda visus apliecinājumus
    public function vesture(Request $request)
    {
        $query = DB::table('apliecinajums')
            ->join('lietotajs', 'apliecinajums.lietotaja_epasts', '=', 'lietotajs.lietotaja_epasts')
            ->join('administrators', 'apliecinajums.administratora_epasts', '=', 'administrators.administratora_epasts')
            ->select(
                'apliecinajums.*',
                'lietotajs.vards as lietotaja_vards',
                'lietotajs.uzvards as lietotaja_uzvards',
                'lietotajs.lietotaja_epasts',
                'administrators.vards as admin_vards',
                'administrators.uzvards as admin_uzvards',
                'administrators.administratora_epasts'
            );
    
        if ($request->filled('meklet')) {
            $meklet = $request->input('meklet');
            $query->where(function ($q) use ($meklet) {
                $q->where('lietotajs.vards', 'like', "%$meklet%")
                  ->orWhere('lietotajs.uzvards', 'like', "%$meklet%")
                  ->orWhere('lietotajs.lietotaja_epasts', 'like', "%$meklet%");
            });
        }
    
        $apliecinajumi = $query->orderByDesc('apliecinajums.izveides_datums')->get();
    
        return view('admin.apliecinajums.vesture', compact('apliecinajumi'));
    }
    

    // Lejupielādē PDF
    public function download($id)
    {
        $record = DB::table('apliecinajums')->where('ID', $id)->first();

        if (!$record || !Storage::exists('public/' . $record->pdf_path)) {
            return back()->withErrors(['fail' => 'Fails nav atrasts.']);
        }

        return Storage::download('public/' . $record->pdf_path);
    }

    // Dzēš ierakstu un PDF
    public function delete($id)
    {
        $record = DB::table('apliecinajums')->where('ID', $id)->first();

        if ($record && Storage::exists('public/' . $record->pdf_path)) {
            Storage::delete('public/' . $record->pdf_path);
        }

        DB::table('apliecinajums')->where('ID', $id)->delete();

        return back()->with('success', 'Apliecinājums veiksmīgi dzēsts!');
    }
}
