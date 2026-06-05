<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Rekap</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111111;
        }
        h1, h2 {
            margin: 0 0 8px 0;
        }
        h1 {
            font-size: 18px;
        }
        h2 {
            font-size: 14px;
            margin-top: 18px;
        }
        .meta {
            margin-bottom: 12px;
        }
        .summary {
            margin: 8px 0 16px 0;
            padding: 10px 12px;
            background: #f3f4f6;
            border-radius: 6px;
        }
        .summary-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-grid td {
            padding: 4px 6px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #f9fafb;
        }
        .muted {
            color: #6b7280;
        }
        .section-note {
            margin-top: 6px;
            color: #6b7280;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <h1>Report Rekap Modul</h1>
    <div class="meta">
        <div>Periode: {{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}</div>
        <div class="muted">Dibuat: {{ now()->format('d M Y H:i') }}</div>
    </div>

    <div class="summary">
        <strong>Ringkasan Task</strong>
        <table class="summary-grid">
            <tr>
                <td>Total Task</td>
                <td>{{ $tasks->count() }}</td>
                <td>Todo</td>
                <td>{{ $taskStatusCounts['todo'] ?? 0 }}</td>
                <td>On Progress</td>
                <td>{{ $taskStatusCounts['on_progress'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Submitted</td>
                <td>{{ $taskStatusCounts['submitted'] ?? 0 }}</td>
                <td>Accepted</td>
                <td>{{ $taskStatusCounts['accepted'] ?? 0 }}</td>
                <td>Rejected</td>
                <td>{{ $taskStatusCounts['rejected'] ?? 0 }}</td>
            </tr>
        </table>
    </div>

    <div class="summary">
        <strong>Ringkasan Weekly Log</strong>
        <table class="summary-grid">
            <tr>
                <td>Total Log</td>
                <td>{{ $weeklyLogs->count() }}</td>
                <td>Pending</td>
                <td>{{ $weeklyStatusCounts['pending'] ?? 0 }}</td>
                <td>Confirmed</td>
                <td>{{ $weeklyStatusCounts['confirmed'] ?? 0 }}</td>
            </tr>
        </table>
    </div>

    <div class="summary">
        <strong>Ringkasan Finance Budgeting</strong>
        <table class="summary-grid">
            <tr>
                <td>Total Budget</td>
                <td>Rp {{ number_format($totalBudget, 0, ',', '.') }}</td>
                <td>Total Realisasi</td>
                <td>Rp {{ number_format($totalRealization, 0, ',', '.') }}</td>
                <td>Sisa</td>
                <td>Rp {{ number_format($remaining, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Persentase Realisasi</td>
                <td>{{ $percentage }}%</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>

    <h2>Detail Task Management</h2>
    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Status</th>
                <th>Assigned To</th>
                <th>Assigned From</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->latestDetail?->status ?? 'todo' }}</td>
                    <td>{{ $task->assignedTo?->name ?? '-' }}</td>
                    <td>{{ $task->assignedFrom?->name ?? '-' }}</td>
                    <td>{{ $task->created_at?->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="muted">Tidak ada data task pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Detail Weekly Log</h2>
    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Periode</th>
                <th>Divisi</th>
                <th>Status</th>
                <th>Logged By</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($weeklyLogs as $log)
                <tr>
                    <td>{{ $log->title }}</td>
                    <td>{{ $log->s_date }} - {{ $log->f_date }}</td>
                    <td>{{ $log->divisi?->nama_divisi ?? '-' }}</td>
                    <td>{{ $log->status ?? 'pending' }}</td>
                    <td>{{ $log->loggedBy?->name ?? '-' }}</td>
                    <td>{{ $log->created_at?->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="muted">Tidak ada data weekly log pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Detail Budget</h2>
    <table>
        <thead>
            <tr>
                <th>Divisi</th>
                <th>Periode</th>
                <th>Budget</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($budgets as $budget)
                <tr>
                    <td>{{ $budget->periodeLaporan?->divisi?->nama_divisi ?? '-' }}</td>
                    <td>{{ $budget->periodeLaporan?->bulan?->bulan ?? '-' }} {{ $budget->periodeLaporan?->tahun?->tahun ?? '' }}</td>
                    <td>Rp {{ number_format($budget->jumlah_budget, 0, ',', '.') }}</td>
                    <td>{{ $budget->created_at?->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="muted">Tidak ada data budget pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h2>Detail Realisasi Budget</h2>
    <table>
        <thead>
            <tr>
                <th>Divisi</th>
                <th>Periode</th>
                <th>Kegiatan</th>
                <th>User</th>
                <th>Jumlah</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($details as $detail)
                <tr>
                    <td>{{ $detail->periodeLaporan?->divisi?->nama_divisi ?? '-' }}</td>
                    <td>{{ $detail->periodeLaporan?->bulan?->bulan ?? '-' }} {{ $detail->periodeLaporan?->tahun?->tahun ?? '' }}</td>
                    <td>{{ $detail->kegiatan }}</td>
                    <td>{{ $detail->user?->name ?? '-' }}</td>
                    <td>Rp {{ number_format($detail->jumlah_anggaran, 0, ',', '.') }}</td>
                    <td>{{ $detail->created_at?->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="muted">Tidak ada data realisasi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-note">Sumber data berdasarkan created_at tiap modul sesuai rentang tanggal yang dipilih.</div>
</body>
</html>
