<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreperiodeLaporanRequest;
use App\Http\Requests\UpdateperiodeLaporanRequest;
use App\Models\periodeLaporan;

class PeriodeLaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreperiodeLaporanRequest $request)
    {
        // use validated data from FormRequest
        $validated = $request->validated();

        // Prevent duplicate periode for same tahun/bulan/divisi
        $exists = periodeLaporan::where('tahun_id', $validated['tahun_id'])
            ->where('bulan_id', $validated['bulan_id'])
            ->where('divisi_id', $validated['divisi_id'])
            ->exists();

        if ($exists) {
            return redirect()->route('finance.index')->with('error', 'Periode untuk tahun, bulan, dan divisi tersebut sudah ada.');
        }

        periodeLaporan::create($validated);

        return redirect()->route('finance.index')->with('success', 'Periode laporan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(periodeLaporan $periodeLaporan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(periodeLaporan $periodeLaporan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateperiodeLaporanRequest $request, periodeLaporan $periodeLaporan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(periodeLaporan $periodeLaporan)
    {
        //
    }
}
