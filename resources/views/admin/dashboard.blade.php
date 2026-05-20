<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/dashboard.css'])
    <title>Admin Dashboard</title>
</head>
<body>
    @extends('layout.sidebar')

    @section('title', 'Dashboard - MUMS')

    @section('content')
    @vite(['resources/css/dashboard.css'])

    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <p>Selamat datang di website PT. MUMS</p>
        </div>

        <div class="stats-row top-stats">
            <div class="stat-card">
                <span class="stat-title">Jumlah Pengguna</span>
                <span class="stat-value">69</span> {{-- Kurang Backend --}}
            </div>
            <div class="stat-card">
                <span class="stat-title">Jumlah Divisi</span>
                <span class="stat-value">5</span> {{-- Kurang Backend --}}
            </div>
            <div class="stat-card">
                <span class="stat-title">Task Selesai</span>
                <span class="stat-value">113</span> {{-- Kurang Backend --}}
            </div>
        </div>

        <div class="stats-row middle-stats">
            <div class="stat-card">
                <span class="stat-title">Total Pengeluaran</span>
                <span class="stat-value">Rp 12.753.897.655</span> {{-- Kurang Backend --}}
            </div>
            <div class="stat-card">
                <span class="stat-title">Total Budget</span>
                <span class="stat-value">Rp 20.967.412.33</span> {{-- Kurang Backend --}}
            </div>
        </div>

        <div class="stats-row bottom-stats">
            <div class="stat-card empty-card">
                {{-- Menunggu Thor --}}
            </div>
        </div>
    </div>
    @endsection
</body>
</html>
