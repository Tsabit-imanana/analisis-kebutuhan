<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        return view('admin.document.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('document.index')->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('document.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        return redirect()->route('document.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
