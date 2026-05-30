@extends('layout.sidebar')

@section('title', 'Dashboard - MUMS')

@section('content')
    @vite([
        'resources/css/dashboard.css',
        'resources/js/dashboard-finance-chart.js',
        'resources/js/dashboard-general-chart.js',
        'resources/js/dashboard-weekly-chart.js',
        'resources/js/dashboard-task-chart.js'
    ])

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <p>Ringkasan data dari seluruh menu utama.</p>
        </div>

        <h2 class="section-title">Ringkasan Umum</h2>
        <div class="dashboard-section">
            <div class="chart-card-inline">
                <h3 class="chart-title">Persebaran Pengguna</h3>
                <div class="canvas-wrapper">
                    <canvas id="generalPolarChart"></canvas>
                </div>
            </div>

            <div class="stats-grid-2x2">
                <div class="stat-card">
                    <span class="stat-title">Jumlah SPV</span>
                    <span class="stat-value">{{ $spvCount ?? 0 }}</span>
                </div>
                <div class="stat-card">
                    <span class="stat-title">Jumlah Employee</span>
                    <span class="stat-value">{{ $employeeCount ?? 0 }}</span>
                </div>
            </div>
        </div>

        <h2 class="section-title">Status Task</h2>
        <div class="dashboard-section">
            <div class="chart-card-inline">
                <h3 class="chart-title">Sebaran Status Task</h3>
                <div class="canvas-wrapper">
                    <canvas id="dashboardTaskRadarChart"></canvas>
                </div>
            </div>

            <div class="stats-grid-5">
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
        </div>

        <h2 class="section-title">Weekly Log</h2>
        <div class="dashboard-section">
            <div class="chart-card-inline">
                <h3 class="chart-title">Visualisasi Status Log</h3>
                <div class="canvas-wrapper">
                    <canvas id="dashboardWeeklyPieChart"></canvas>
                </div>
            </div>

            <div class="stats-grid-2x2">
                <div class="stat-card">
                    <span class="stat-title">Total Log</span>
                    <span class="stat-value">{{ $weeklyTotal ?? 0 }}</span>
                </div>
                <div class="stat-card">
                    <span class="stat-title">% Dikonfirmasi</span>
                    <span class="stat-value">
                        {{ $weeklyTotal > 0 ? round((($weeklyConfirmed ?? 0) / $weeklyTotal) * 100) : 0 }}%
                    </span>
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
        </div>

        <h2 class="section-title">Finance Budgeting</h2>
        <div class="dashboard-section">
            <div class="chart-card-inline">
                <h3 class="chart-title">Visualisasi Anggaran</h3>
                <div class="canvas-wrapper">
                    <canvas id="dashboardFinanceChart"></canvas>
                </div>
            </div>

            <div class="stats-grid-2x2">
                <div class="stat-card">
                    <span class="stat-title">Total Budget</span>
                    <span class="stat-value">Rp {{ number_format($totalBudget ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="stat-card">
                    <span class="stat-title">% Realisasi</span>
                    <span class="stat-value">{{ $realizedPercentage ?? 0 }}%</span>
                </div>
                <div class="stat-card">
                    <span class="stat-title">Total Realisasi</span>
                    <span class="stat-value">Rp {{ number_format($totalRealized ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="stat-card">
                    <span class="stat-title">Sisa Anggaran</span>
                    <span class="stat-value">Rp {{ number_format($remainingBudget ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if(typeof renderGeneralChart === 'function') {
                renderGeneralChart(
                    'generalPolarChart',
                    {{ $spvCount ?? 0 }},
                    {{ $employeeCount ?? 0 }}
                );
            }

            if(typeof renderTaskChart === 'function') {
                renderTaskChart(
                    'dashboardTaskRadarChart',
                    {{ $taskStatusCounts['todo'] ?? 0 }},
                    {{ $taskStatusCounts['on_progress'] ?? 0 }},
                    {{ $taskStatusCounts['submitted'] ?? 0 }},
                    {{ $taskStatusCounts['accepted'] ?? 0 }},
                    {{ $taskStatusCounts['rejected'] ?? 0 }}
                );
            }

            if(typeof renderWeeklyChart === 'function') {
                renderWeeklyChart(
                    'dashboardWeeklyPieChart',
                    {{ $weeklyPending ?? 0 }},
                    {{ $weeklyConfirmed ?? 0 }}
                );
            }

            if(typeof renderFinanceChart === 'function') {
                renderFinanceChart(
                    'dashboardFinanceChart',
                    {{ $totalRealized ?? 0 }},
                    {{ $remainingBudget ?? 0 }},
                    {{ $realizedPercentage ?? 0 }}
                );
            }
        });
    </script>
@endsection
