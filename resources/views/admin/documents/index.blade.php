@extends('layout.sidebar')

@section('title', 'Document Management - MUMS')

@section('content')
    @vite(['resources/css/dashboard.css', 'resources/css/documents.css'])

    <div class="dashboard-container documents-page">
        <div class="dashboard-header documents-header">
            <div>
                <h1>Document Management</h1>
                <p>Kelola template dokumen dan persetujuan dokumen.</p>
            </div>
            <a href="{{ route('documents.create') }}" class="doc-btn doc-btn--primary">Buat Dokumen</a>
        </div>

        @if (session('success'))
            <div class="doc-alert doc-alert--success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="doc-alert doc-alert--error">{{ session('error') }}</div>
        @endif

        <div class="doc-card">
            <div class="doc-table-header">
                <h2>Daftar Dokumen</h2>
                <span class="doc-muted">Template: Surat Jalan</span>
            </div>

            @if ($documents->isEmpty())
                <div class="doc-empty">Belum ada dokumen.</div>
            @else
                <div class="doc-table-wrap">
                    <table class="doc-table">
                        <thead>
                            <tr>
                                <th>No. Surat</th>
                                <th>Kota & Tanggal</th>
                                <th>Kepada</th>
                                <th>Penerima</th>
                                <th>Pengirim</th>
                                <th>Status</th>
                                <th>Pemohon</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                                <tr>
                                    <td>{{ $document->nomor_surat }}</td>
                                    <td>{{ $document->kota_surat }}, {{ optional($document->tanggal_surat)->format('d M Y') }}</td>
                                    <td>
                                        <div class="doc-cell-title">{{ $document->kepada }}</div>
                                        <div class="doc-cell-subtitle">{{ $document->alamat_kepada }}</div>
                                    </td>
                                    <td>{{ $document->penerima }}</td>
                                    <td>{{ $document->pengirim }}</td>
                                    <td>
                                        <span class="doc-status doc-status--{{ $document->status }}">
                                            {{ ucfirst($document->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $document->creator->name ?? '-' }}</td>
                                    <td>
                                        <div class="doc-actions">
                                            @if ($document->status === 'draft' && ($currentRole === 'employee' || $currentRole === 'admin' || $currentRole === 'spv'))
                                                <form method="POST" action="{{ route('documents.submit', $document) }}">
                                                    @csrf
                                                    <button type="submit" class="doc-btn doc-btn--secondary">Submit</button>
                                                </form>
                                            @endif

                                            @if ($document->status === 'pending' && in_array($currentRole, ['admin', 'spv'], true))
                                                <form method="POST" action="{{ route('documents.approve', $document) }}">
                                                    @csrf
                                                    <button type="submit" class="doc-btn doc-btn--primary">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('documents.reject', $document) }}">
                                                    @csrf
                                                    <button type="submit" class="doc-btn doc-btn--danger">Reject</button>
                                                </form>
                                            @endif

                                            @if ($document->status === 'approved')
                                                <a href="{{ route('documents.download', $document) }}" class="doc-btn doc-btn--ghost">Download PDF</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
