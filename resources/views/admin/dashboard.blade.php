@extends('layout.sidebar')

@section('title', 'Dashboard - MUMS')

@section('content')
    @vite(['resources/css/dashboard.css'])

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <p>Ringkasan data dari seluruh menu utama.</p>
        </div>

        <h2 class="section-title">Ringkasan Umum</h2>
        <div class="stats-row four-stats">
            <div class="stat-card">
                <span class="stat-title">Jumlah Pengguna</span>
                <span class="stat-value">{{ $userCount ?? 0 }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Jumlah Divisi</span>
                <span class="stat-value">{{ $divisiCount ?? 0 }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Total Task</span>
                <span class="stat-value">{{ $taskTotal ?? 0 }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Total Weekly Log</span>
                <span class="stat-value">{{ $weeklyTotal ?? 0 }}</span>
            </div>
        </div>

        <h2 class="section-title">Status Task</h2>
        <div class="stats-row five-stats">
            <div class="stat-card">
                <span class="stat-title">Todo</span>
                <span class="stat-value">{{ $taskStatusCounts['todo'] ?? 0 }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">On Progress</span>
                <span class="stat-value">{{ $taskStatusCounts['on_progress'] ?? 0 }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Submitted</span>
                <span class="stat-value">{{ $taskStatusCounts['submitted'] ?? 0 }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Accepted</span>
                <span class="stat-value">{{ $taskStatusCounts['accepted'] ?? 0 }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Rejected</span>
                <span class="stat-value">{{ $taskStatusCounts['rejected'] ?? 0 }}</span>
            </div>
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
