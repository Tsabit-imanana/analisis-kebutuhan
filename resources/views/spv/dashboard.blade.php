@extends('layout.sidebar-spv')

@section('title', 'SPV Dashboard - MUMS')

@section('content')
    @vite(['resources/css/dashboard.css'])

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Dashboard SPV</h1>
            <p>Ringkasan Weekly Log dan Finance Budgeting.</p>
        </div>

        <h2 class="section-title">Weekly Log</h2>
        <div class="stats-row three-stats">
            <div class="stat-card">
                <span class="stat-title">Total Log</span>
                <span class="stat-value">{{ $weeklyTotal ?? 0 }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Belum Dikonfirmasi</span>
                <span class="stat-value">{{ $weeklyPending ?? 0 }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Sudah Dikonfirmasi</span>
                <span class="stat-value">{{ $weeklyConfirmed ?? 0 }}</span>
            </div>
        </div>

        <h2 class="section-title">Finance Budgeting</h2>
        <div class="stats-row four-stats">
            <div class="stat-card">
                <span class="stat-title">Total Budget</span>
                <span class="stat-value">Rp {{ number_format($totalBudget ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Total Realisasi</span>
                <span class="stat-value">Rp {{ number_format($totalRealized ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Sisa Anggaran</span>
                <span class="stat-value">Rp {{ number_format($remainingBudget ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">% Realisasi</span>
                <span class="stat-value">{{ $realizedPercentage ?? 0 }}%</span>
            </div>
        </div>
    </div>
@endsection