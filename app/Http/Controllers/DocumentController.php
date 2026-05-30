<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = Document::query()->with(['creator', 'approver'])->orderByDesc('created_at');
        if ($user && $user->role === 'employee') {
            $query->where('created_by', $user->id);
        }

        $view = match ($user?->role) {
            'employee' => 'employee.documents.index',
            'spv' => 'spv.documents.index',
            default => 'admin.documents.index',
        };

        return view($view, [
            'documents' => $query->get(),
            'currentRole' => $user?->role,
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        $view = match ($user?->role) {
            'employee' => 'employee.documents.create',
            'spv' => 'spv.documents.create',
            default => 'admin.documents.create',
        };

        return view($view);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor_surat' => 'required|string|max:255',
            'kota_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'kepada' => 'required|string|max:255',
            'alamat_kepada' => 'required|string',
            'up_kepada' => 'nullable|string|max:255',
            'faktur_menyusul' => 'nullable|boolean',
            'volume' => 'required|string|max:255',
            'nama_barang' => 'required|string',
            'nomer_seri' => 'nullable|string',
            'kontrak_khs_no' => 'nullable|string|max:255',
            'kontrak_khs_tanggal' => 'nullable|date',
            'kontrak_rinci_no' => 'nullable|string|max:255',
            'kontrak_rinci_tanggal' => 'nullable|date',
            'penerima' => 'required|string|max:255',
            'pengirim' => 'required|string|max:255',
        ]);

        $data['template_type'] = 'surat_jalan';
        $data['status'] = 'draft';
        $data['created_by'] = auth()->id();
        $data['faktur_menyusul'] = (bool) ($data['faktur_menyusul'] ?? false);

        Document::create($data);

        return redirect()->route('documents.index')->with('success', 'Draft dokumen berhasil dibuat.');
    }

    public function submit(Document $document)
    {
        $user = auth()->user();
        if (! $user || (int) $document->created_by !== (int) $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk submit dokumen ini.');
        }

        if (! in_array($document->status, ['draft', 'rejected'], true)) {
            return redirect()->back()->with('error', 'Dokumen tidak bisa disubmit dalam status saat ini.');
        }

        $document->update([
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil disubmit untuk persetujuan.');
    }

    public function approve(Document $document)
    {
        $user = auth()->user();
        if (! $user || ! in_array($user->role, ['admin', 'spv'], true)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyetujui dokumen ini.');
        }

        if ($document->status !== 'pending') {
            return redirect()->back()->with('error', 'Dokumen hanya bisa disetujui saat status pending.');
        }

        $document->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil disetujui.');
    }

    public function reject(Document $document)
    {
        $user = auth()->user();
        if (! $user || ! in_array($user->role, ['admin', 'spv'], true)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menolak dokumen ini.');
        }

        if ($document->status !== 'pending') {
            return redirect()->back()->with('error', 'Dokumen hanya bisa ditolak saat status pending.');
        }

        $document->update([
            'status' => 'rejected',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil ditolak.');
    }

    public function download(Document $document)
    {
        if ($document->status !== 'approved') {
            return redirect()->back()->with('error', 'Dokumen belum disetujui, belum bisa diunduh.');
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('documents.templates.surat-jalan', [
            'document' => $document,
        ]);

        $filename = 'surat-jalan-' . $document->id . '.pdf';

        return $pdf->download($filename);
    }
}
