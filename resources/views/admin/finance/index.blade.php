@extends('layout.sidebar')

@section('title', 'Finance Management - MUMS')

@section('content')
    <style>
        .finance-page * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .finance-page {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            min-height: calc(100vh - 40px);
        }

        .finance-page .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .finance-page h1 {
            color: #333;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .finance-page .button-group {
            display: flex;
            gap: 10px;
        }

        .finance-page .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .finance-page .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .finance-page .btn-primary:hover {
            background-color: #0056b3;
        }

        .finance-page .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .finance-page .btn-secondary:hover {
            background-color: #545b62;
        }

        .finance-page .btn-info {
            background-color: #17a2b8;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
        }

        .finance-page .btn-info:hover {
            background-color: #138496;
        }

        .finance-page .btn-danger {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
        }

        .finance-page .btn-danger:hover {
            background-color: #c82333;
        }

        .finance-page .filter-section {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }

        .finance-page .filter-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .finance-page .filter-group label {
            display: flex;
            flex-direction: column;
            gap: 5px;
            font-weight: bold;
            color: #555;
        }

        .finance-page .filter-group select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            min-width: 150px;
        }

        .finance-page .alert {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .finance-page .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .finance-page table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .finance-page thead {
            background-color: #007bff;
            color: white;
        }

        .finance-page th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }

        .finance-page td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .finance-page tbody tr:hover {
            background-color: #f9f9f9;
        }

        .finance-page tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .finance-page .status-badge {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
        }

        .finance-page .status-good {
            background-color: #d4edda;
            color: #155724;
        }

        .finance-page .status-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .finance-page .status-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .finance-page .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .finance-page .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 500px;
            border-radius: 6px;
        }

        .finance-page .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .finance-page .close {
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
        }

        .finance-page .close:hover {
            color: #000;
        }

        .finance-page .form-group {
            margin-bottom: 15px;
        }

        .finance-page .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .finance-page .form-group input,
        .finance-page .form-group select,
        .finance-page .form-group textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .finance-page .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .finance-page .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .finance-page .back-link {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 12px;
            background-color: #ddd;
            color: #000;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .finance-page .back-link:hover {
            background-color: #bbb;
        }

        .finance-page .currency {
            text-align: right;
            font-weight: bold;
        }

        .finance-page .percentage {
            text-align: center;
            font-weight: bold;
        }

        .finance-page .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .finance-page .action-buttons {
            display: flex;
            gap: 5px;
        }
    </style>

    <div class="finance-page">
    <div class="container">
        <h1>
            Finance Management
            <div class="button-group">
                <a href="/" class="back-link">← Back</a>
                <button type="button" onclick="openAddPeriodModal()" class="btn btn-primary">+ Tambah Periode</button>
            </div>
        </h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" style="background-color:#f8d7da;color:#721c24;border:1px solid #f5c6cb;padding:12px 16px;border-radius:4px;margin-bottom:20px;">
                {{ session('error') }}
            </div>
            <script>
                window.addEventListener('DOMContentLoaded', function() {
                    const msg = @json(session('error'));
                    if (msg) {
                        // show a small popup notification
                        alert(msg);
                    }
                });
            </script>
        @endif

        @if($finansialData->isEmpty())
            <div class="empty-state">
                <p>Tidak ada data periode laporan. <a href="#" onclick="openAddPeriodModal()">Tambah periode sekarang.</a></p>
            </div>
        @else
            <div class="filter-section">
                <div class="filter-group">
                    <label>
                        Filter Divisi:
                        <select id="filterDivisi">
                            <option value="">-- Semua Divisi --</option>
                            @foreach ($divisi as $d)
                                <option value="{{ $d->id }}">{{ $d->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>
                        Filter Tahun:
                        <select id="filterTahun">
                            <option value="">-- Semua Tahun --</option>
                            @foreach ($tahun as $t)
                                <option value="{{ $t->id }}">{{ $t->tahun }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>
                        Filter Bulan:
                        <select id="filterBulan">
                            <option value="">-- Semua Bulan --</option>
                            @foreach ($bulan as $b)
                                <option value="{{ $b->id }}">{{ $b->bulan }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="button" onclick="resetFilter()" class="btn btn-secondary">Reset</button>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>Divisi</th>
                        <th>Total Budget</th>
                        <th>Total Realisasi</th>
                        <th>Sisa Anggaran</th>
                        <th>% Realisasi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($finansialData as $data)
                        <tr class="periode-row" data-periode-id="{{ $data['periode']->id }}" data-divisi="{{ $data['periode']->divisi_id }}" data-tahun="{{ $data['periode']->tahun_id }}" data-bulan="{{ $data['periode']->bulan_id }}">
                            <td>
                                <strong>{{ $data['periode']->bulan->bulan ?? '-' }} {{ $data['periode']->tahun->tahun ?? '-' }}</strong>
                            </td>
                            <td>
                                {{ $data['periode']->divisi->nama_divisi ?? '-' }}
                            </td>
                            <td class="currency">
                                Rp{{ number_format($data['totalBudget'], 0, ',', '.') }}
                            </td>
                            <td class="currency">
                                Rp{{ number_format($data['totalRealized'], 0, ',', '.') }}
                            </td>
                            <td class="currency">
                                <span class="@if($data['remaining'] >= 0) status-good @else status-danger @endif"
                                      style="padding: 4px 8px; border-radius: 4px;">
                                    Rp{{ number_format($data['remaining'], 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="percentage">
                                <div class="status-badge @if($data['percentage'] <= 70) status-good @elseif($data['percentage'] <= 90) status-warning @else status-danger @endif">
                                    {{ $data['percentage'] }}%
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('finance.show', $data['periode']->id) }}" class="btn btn-info">View</a>
                                    <button type="button" onclick="openBudgetModal({{ $data['periode']->id }})" class="btn btn-info">+ Budget</button>
                                    <button type="button" onclick="openDetailModal({{ $data['periode']->id }})" class="btn btn-info">+ Detail</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Modal Add Budget -->
    <div id="budgetModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Budget</h3>
                <span class="close" onclick="closeBudgetModal()">&times;</span>
            </div>
            <form action="{{ route('finance.budget.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Periode</label>
                    <input type="hidden" name="periode_laporan_id" id="budget_periode_id">
                    <input type="text" id="budget_periode_display" disabled>
                </div>
                <div class="form-group">
                    <label>Jumlah Budget (Rp)</label>
                    <input type="number" name="jumlah_budget" required min="0" step="100">
                </div>
                <div class="form-actions">
                    <button type="button" onclick="closeBudgetModal()" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Add Detail Laporan -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Detail Laporan</h3>
                <span class="close" onclick="closeDetailModal()">&times;</span>
            </div>
            <form action="{{ route('finance.detail.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Periode</label>
                    <input type="hidden" name="periode_laporan_id" id="detail_periode_id">
                    <input type="text" id="detail_periode_display" disabled>
                </div>
                <div class="form-group">
                    <label>User (PIC)</label>
                    <select name="user_id" required>
                        <option value="">-- Pilih User --</option>
                        @foreach ($finansialData as $data)
                            @foreach ($data['details'] as $detail)
                                <option value="{{ $detail->user_id }}">{{ $detail->user->name ?? '-' }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Kegiatan</label>
                    <input type="text" name="kegiatan" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" required></textarea>
                </div>
                <div class="form-group">
                    <label>Jumlah Anggaran (Rp)</label>
                    <input type="number" name="jumlah_anggaran" required min="0" step="100">
                </div>
                <div class="form-group">
                    <label>Bukti Foto</label>
                    <input type="file" name="bukti_foto" accept="image/*">
                </div>
                <div class="form-actions">
                    <button type="button" onclick="closeDetailModal()" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Add Periode -->
    <div id="periodeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Periode Laporan</h3>
                <span class="close" onclick="closeAddPeriodModal()">&times;</span>
            </div>
            <form action="/periode-laporan" method="POST">
                @csrf
                <div class="form-group">
                    <label>Tahun</label>
                    <select name="tahun_id" required>
                        <option value="">-- Pilih Tahun --</option>
                        @foreach ($tahun as $t)
                            <option value="{{ $t->id }}">{{ $t->tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Bulan</label>
                    <select name="bulan_id" required>
                        <option value="">-- Pilih Bulan --</option>
                        @foreach ($bulan as $b)
                            <option value="{{ $b->id }}">{{ $b->bulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Divisi</label>
                    <select name="divisi_id" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach ($divisi as $d)
                            <option value="{{ $d->id }}">{{ $d->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" onclick="closeAddPeriodModal()" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openBudgetModal(periodeId) {
            const row = document.querySelector(`tr.periode-row[data-periode-id="${periodeId}"]`);
            const periodeDisplay = row?.querySelector('td strong')?.textContent || 'Unknown';
            
            document.getElementById('budget_periode_id').value = periodeId;
            document.getElementById('budget_periode_display').value = periodeDisplay;
            document.getElementById('budgetModal').style.display = 'block';
        }

        function closeBudgetModal() {
            document.getElementById('budgetModal').style.display = 'none';
        }

        function openDetailModal(periodeId) {
            const row = document.querySelector(`tr.periode-row[data-periode-id="${periodeId}"]`);
            const periodeDisplay = row?.querySelector('td strong')?.textContent || 'Unknown';
            
            document.getElementById('detail_periode_id').value = periodeId;
            document.getElementById('detail_periode_display').value = periodeDisplay;
            document.getElementById('detailModal').style.display = 'block';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').style.display = 'none';
        }

        function openAddPeriodModal() {
            document.getElementById('periodeModal').style.display = 'block';
        }

        function closeAddPeriodModal() {
            document.getElementById('periodeModal').style.display = 'none';
        }

        function resetFilter() {
            document.getElementById('filterDivisi').value = '';
            document.getElementById('filterTahun').value = '';
            document.getElementById('filterBulan').value = '';
            filterTable();
        }

        function filterTable() {
            const divisiFilter = document.getElementById('filterDivisi').value;
            const tahunFilter = document.getElementById('filterTahun').value;
            const bulanFilter = document.getElementById('filterBulan').value;

            document.querySelectorAll('.periode-row').forEach(row => {
                let show = true;

                if (divisiFilter && row.dataset.divisi !== divisiFilter) show = false;
                if (tahunFilter && row.dataset.tahun !== tahunFilter) show = false;
                if (bulanFilter && row.dataset.bulan !== bulanFilter) show = false;

                row.style.display = show ? '' : 'none';
            });
        }

        document.getElementById('filterDivisi')?.addEventListener('change', filterTable);
        document.getElementById('filterTahun')?.addEventListener('change', filterTable);
        document.getElementById('filterBulan')?.addEventListener('change', filterTable);

        window.onclick = function(event) {
            const budgetModal = document.getElementById('budgetModal');
            const detailModal = document.getElementById('detailModal');
            const periodeModal = document.getElementById('periodeModal');

            if (event.target === budgetModal) {
                closeBudgetModal();
            }
            if (event.target === detailModal) {
                closeDetailModal();
            }
            if (event.target === periodeModal) {
                closeAddPeriodModal();
            }
        };
    </script>
    </div>
    </div>
@endsection
