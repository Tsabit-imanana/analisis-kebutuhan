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
        $validated = $request->validate([
            'tahun_id' => 'required|exists:tahun_periodes,id',
            'bulan_id' => 'required|exists:bulan_periodes,id',
            'divisi_id' => 'required|exists:divisis,id',
        ]);

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
