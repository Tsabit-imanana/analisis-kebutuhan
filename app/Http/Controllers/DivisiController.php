<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoredivisiRequest;
use App\Http\Requests\UpdatedivisiRequest;
use App\Models\divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $query = divisi::query();
        if ($search) {
            $query->where('nama_divisi', 'LIKE', "%{$search}%");
        }

        $divisis = $query->orderBy('nama_divisi')->paginate(12)->withQueryString();

        return view('admin.settings.divisi.index', compact('divisis', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.settings.divisi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoredivisiRequest $request)
    {
        $data = $request->validated();
        divisi::create($data);

        return Redirect::route('settings.divisi.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(divisi $divisi)
    {
        return view('admin.settings.divisi.show', compact('divisi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(divisi $divisi)
    {
        return view('admin.settings.divisi.edit', compact('divisi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedivisiRequest $request, divisi $divisi)
    {
        $data = $request->validated();
        $divisi->update($data);

        return Redirect::route('settings.divisi.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(divisi $divisi)
    {
        // Prevent deletion if in use by users or periodeLaporans
        if ($divisi->users()->count() > 0 || $divisi->periodeLaporans()->count() > 0) {
            return Redirect::back()->with('error', 'Divisi tidak dapat dihapus karena masih direferensi oleh data lain.');
        }

        $divisi->delete();

        return Redirect::route('settings.divisi.index')->with('success', 'Divisi berhasil dihapus.');
    }
}
