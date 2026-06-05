@extends('layout.employee_sidebar')

@section('title', 'Finance Management - MUMS')

@section('content')
@vite(['resources/css/dashboard.css', 'resources/css/finance.css'])

<div class="finance-container">
    <div class="page-header">
        <div class="header-text">
            <h1>Finance Management</h1>
            <p>Pantau periode laporan, budget, dan realisasi anggaran.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="finance-alert finance-alert--success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="finance-alert finance-alert--error">
            {{ session('error') }}
        </div>
    @endif

    @php
        $grandBudget = $finansialData->sum('totalBudget');
        $grandRealized = $finansialData->sum('totalRealized');
        $grandRemaining = $grandBudget - $grandRealized;
        $grandPercentage = $grandBudget > 0 ? round(($grandRealized / $grandBudget) * 100, 2) : 0;

        $finansialByDivisi = $finansialData->groupBy(fn ($item) => $item['periode']->divisi_id);
    @endphp

    <div class="stats-row four-stats mb-4">
        <div class="stat-card">
            <span class="stat-title">Total Keseluruhan Budget</span>
            <span class="stat-value">Rp{{ number_format($grandBudget, 0, ',', '.') }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Total Realisasi Digunakan</span>
            <span class="stat-value">Rp{{ number_format($grandRealized, 0, ',', '.') }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Total Sisa Anggaran</span>
            <span class="stat-value">Rp{{ number_format($grandRemaining, 0, ',', '.') }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">% Realisasi Keseluruhan</span>
            <span class="stat-value">
                <span class="status-badge @if($grandPercentage <= 70) badge-good @elseif($grandPercentage <= 90) badge-warning @else badge-danger @endif text-lg" style="padding: 4px 8px;">
                    {{ $grandPercentage }}%
                </span>
            </span>
        </div>
    </div>

    <h2 class="section-title">Daftar Periode per Divisi</h2>

    @if($finansialData->isEmpty())
        <div class="table-container">
            <p class="empty-data">Tidak ada data periode laporan saat ini.</p>
        </div>
    @else
        @foreach ($finansialByDivisi as $divisiId => $items)
            @php
                $firstPeriode = $items->first()['periode'] ?? null;
                $divisiName = $firstPeriode?->divisi?->nama_divisi ?? '-';
            @endphp

            <div class="table-container mb-4">
                <div class="table-header">
                    <h3>Divisi: {{ $divisiName }}</h3>
                    <div class="table-controls">
                        <span>Total Data: <strong>{{ $items->count() }} Periode</strong></span>
                    </div>
                </div>

                <div class="table-responsive finance-divisi-section">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Period</th>
                                <th>Total Budget</th>
                                <th>Total Realisasi</th>
                                <th>Sisa Anggaran</th>
                                <th>% Realisasi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $data)
                                <tr class="periode-row" data-periode-id="{{ $data['periode']->id }}">
                                    <td><strong>{{ $data['periode']->bulan->bulan ?? '-' }} {{ $data['periode']->tahun->tahun ?? '-' }}</strong></td>
                                    <td>Rp{{ number_format($data['totalBudget'], 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($data['totalRealized'], 0, ',', '.') }}</td>
                                    <td>
                                        <span class="status-badge {{ $data['remaining'] >= 0 ? 'badge-good' : 'badge-danger' }}">
                                            Rp{{ number_format($data['remaining'], 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge @if($data['percentage'] <= 70) badge-good @elseif($data['percentage'] <= 90) badge-warning @else badge-danger @endif">
                                            {{ $data['percentage'] }}%
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-cell">
                                            <a href="{{ route('finance.show', $data['periode']->id) }}" class="btn-icon" title="View Detail">
                                                <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                let s = document.getElementById('successModal');
                let e = document.getElementById('errorModal');
                if(s) s.style.display = 'none';
                if(e) e.style.display = 'none';
            }, 1000);
        });
    </script>
</div>
@endsection
