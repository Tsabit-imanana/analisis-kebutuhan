<?php

namespace App\Http\Controllers;

use App\Models\periodeLaporan;
use App\Models\budget;
use App\Models\detailLaporan;
use App\Models\divisi;
use App\Models\tahun_periode;
use App\Models\bulan_periode;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Display a listing of the finance periods.
     */
    public function index()
    {
        $periodeLaporans = periodeLaporan::with(['bulan', 'tahun', 'divisi'])->get();
        $divisi = divisi::all();
        $tahun = tahun_periode::all();
        $bulan = bulan_periode::all();

        // Group budgets and details by periode
        $finansialData = [];
        foreach ($periodeLaporans as $periode) {
            $budgets = budget::where('periode_laporan_id', $periode->id)->get();
            $details = detailLaporan::where('periode_laporan_id', $periode->id)->get();
            
            $totalBudget = $budgets->sum('jumlah_budget');
            $totalRealized = $details->sum('jumlah_anggaran');
            
            $finansialData[$periode->id] = [
                'periode' => $periode,
                'budgets' => $budgets,
                'details' => $details,
                'totalBudget' => $totalBudget,
                'totalRealized' => $totalRealized,
                'remaining' => $totalBudget - $totalRealized,
                'percentage' => $totalBudget > 0 ? round(($totalRealized / $totalBudget) * 100, 2) : 0
            ];
        }

        return view('admin.finance.index', [
            'finansialData' => collect($finansialData),
            'divisi' => $divisi,
            'tahun' => $tahun,
            'bulan' => $bulan,
        ]);
    }

    /**
     * Show details of a specific reporting period.
     */
    public function show($id)
    {
        $periode = periodeLaporan::with(['bulan', 'tahun', 'divisi'])->findOrFail($id);
        $budgets = budget::where('periode_laporan_id', $id)->get();
        $details = detailLaporan::where('periode_laporan_id', $id)->with('user')->get();
        
        $totalBudget = $budgets->sum('jumlah_budget');
        $totalRealized = $details->sum('jumlah_anggaran');

        return view('admin.finance.show', [
            'periode' => $periode,
            'budgets' => $budgets,
            'details' => $details,
            'totalBudget' => $totalBudget,
            'totalRealized' => $totalRealized,
            'remaining' => $totalBudget - $totalRealized,
        ]);
    }

    /**
     * Store a new budget entry.
     */
    public function storeBudget(Request $request)
    {
        $request->validate([
            'periode_laporan_id' => 'required|exists:periode_laporans,id',
            'jumlah_budget' => 'required|numeric|min:0',
        ]);

        budget::create($request->only('periode_laporan_id', 'jumlah_budget'));

        return redirect()->back()->with('success', 'Budget berhasil ditambahkan');
    }

    /**
     * Store a new detail laporan entry.
     */
    public function storeDetail(Request $request)
    {
        $request->validate([
            'periode_laporan_id' => 'required|exists:periode_laporans,id',
            'user_id' => 'required|exists:users,id',
            'kegiatan' => 'required|string',
            'deskripsi' => 'required|string',
            'jumlah_anggaran' => 'required|numeric|min:0',
            'bukti_foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('periode_laporan_id', 'user_id', 'kegiatan', 'deskripsi', 'jumlah_anggaran');

        if ($request->hasFile('bukti_foto')) {
            $path = $request->file('bukti_foto')->store('finance', 'public');
            $data['bukti_foto'] = $path;
        }

        detailLaporan::create($data);

        return redirect()->back()->with('success', 'Detail laporan berhasil ditambahkan');
    }
}
