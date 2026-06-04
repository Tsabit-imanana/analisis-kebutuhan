@extends('layout.sidebar')

@section('title', 'Finance Management - MUMS')

@section('content')
@vite([
    'resources/css/dashboard.css',
    'resources/css/finance.css',
    'resources/js/dashboard-finance-chart.js'
])

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="finance-container">
    <div class="page-header">
        <div class="header-text">
            <h1>Finance Management</h1>
            <p>Kelola periode laporan, budget, dan realisasi anggaran.</p>
        </div>
        <button type="button" onclick="openAddPeriodModal()" class="btn-dark">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Periode Baru
        </button>
    </div>

    @php
        $grandBudget = $finansialData->sum('totalBudget');
        $grandRealized = $finansialData->sum('totalRealized');
        $grandRemaining = $grandBudget - $grandRealized;
        $grandPercentage = $grandBudget > 0 ? round(($grandRealized / $grandBudget) * 100, 2) : 0;

        $finansialByDivisi = $finansialData->groupBy(fn ($item) => $item['periode']->divisi_id);
    @endphp

    <div class="dashboard-section mb-4">
        <div class="chart-card-inline">
            <h3 class="chart-title">Visualisasi Anggaran Keseluruhan</h3>
            <div class="canvas-wrapper">
                <canvas id="financeManagementChart"></canvas>
            </div>
        </div>

        <div class="stats-grid-2x2">
            <div class="stat-card">
                <span class="stat-title">Total Keseluruhan Budget</span>
                <span class="stat-value">Rp {{ number_format($grandBudget, 0, ',', '.') }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">% Realisasi Keseluruhan</span>
                <span class="stat-value">
                    <span class="status-badge @if($grandPercentage <= 70) badge-good @elseif($grandPercentage <= 90) badge-warning @else badge-danger @endif text-lg">
                        {{ $grandPercentage }}%
                    </span>
                </span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Total Realisasi Digunakan</span>
                <span class="stat-value">Rp {{ number_format($grandRealized, 0, ',', '.') }}</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Total Sisa Anggaran</span>
                <span class="stat-value">Rp {{ number_format($grandRemaining, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <h2 class="section-title">Daftar Periode per Divisi</h2>
    @if($finansialData->isEmpty())
        <div class="table-container">
            <p class="empty-data">Tidak ada data periode laporan. <a href="#" onclick="openAddPeriodModal(); return false;" style="color:#1E3A8A;">Tambah sekarang.</a></p>
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
                                    <td class="currency">Rp{{ number_format($data['remaining'], 0, ',', '.') }}</td>
                                    <td class="percentage">{{ $data['percentage'] }}%</td>
                                    <td>
                                        <div class="action-cell">
                                            <a href="{{ route('finance.show', $data['periode']->id) }}" class="btn-icon" title="View Detail">
                                                <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            </a>
                                            <button type="button" onclick="openBudgetModal({{ $data['periode']->id }}, '{{ $data['periode']->bulan->bulan ?? '' }} {{ $data['periode']->tahun->tahun ?? '' }}')" class="btn-icon" title="Tambah Budget">
                                                <svg viewBox="0 0 24 24">
                                                    <path d="M13.7678 12.7678C14.2366 12.2989 14.5 11.663 14.5 11C14.5 10.337 14.2366 9.70107 13.7678 9.23223C13.2989 8.76339 12.663 8.5 12 8.5C11.337 8.5 10.7011 8.76339 10.2322 9.23223C9.76339 9.70107 9.5 10.337 9.5 11C9.5 11.663 9.76339 12.2989 10.2322 12.7678C10.7011 13.2366 11.337 13.5 12 13.5C12.663 13.5 13.2989 13.2366 13.7678 12.7678Z"></path>
                                                    <path d="M22 13V5.927C22 5.359 21.676 4.845 21.133 4.677C20.19 4.383 18.479 4 16 4C11.42 4 10.197 5.677 3.878 4.424C2.921 4.234 2 4.945 2 5.92V15.937C2 16.625 2.473 17.23 3.145 17.378C8.39 18.535 10.332 17.796 13 17.361"></path>
                                                    <path d="M2 8C3.951 8 5.705 6.405 5.929 4.754M18.5 4.5C18.5 6.54 20.265 8.469 22 8.469M6 17.496C6 16.4351 5.57857 15.4177 4.82843 14.6676C4.07828 13.9174 3.06087 13.496 2 13.496M19 14V20M16 17H22"></path>
                                                </svg>
                                            </button>
                                            <button type="button" onclick="openDetailModal({{ $data['periode']->id }}, '{{ $data['periode']->bulan->bulan ?? '' }} {{ $data['periode']->tahun->tahun ?? '' }}')" class="btn-icon" title="Tambah Detail Laporan">
                                                <svg viewBox="0 0 24 24" class="icon-fill">
                                                    <path d="M7 11H17V13H7V11ZM7 7H17V9H7V7ZM7 17H12V15H7V17Z"></path>
                                                    <path d="M5 5H9V3H5C3.9 3 3 3.9 3 5V9H5V5ZM5 21H9V19H5V15H3V19C3 20.1 3.9 21 5 21ZM21 15H19V19H15V21H19C20.1 21 21 20.1 21 19V15ZM21 5C21 3.9 20.1 3 19 3H15V5H19V9H21V5Z"></path>
                                                </svg>
                                            </button>
                                            <button type="button" onclick="openDeleteModal('{{ route('periode.destroy', $data['periode']->id) }}')" class="btn-icon" title="Delete">
                                                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
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

    <div id="budgetModal" class="modal">
        <div class="modal-content">
            <h3>Tambah Budget</h3>
            <form action="{{ route('finance.budget.store') }}" method="POST">
                @csrf
                <label>Periode</label>
                <input type="hidden" name="periode_laporan_id" id="budget_periode_id">
                <input type="text" id="budget_periode_display" disabled class="form-control">

                <label>Jumlah Budget (Rp)</label>
                <input type="number" name="jumlah_budget" required min="0" step="100" class="form-control">

                <div class="form-actions">
                    <button type="submit" class="btn-dark">Simpan</button>
                    <button type="button" class="btn-light" onclick="closeBudgetModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div id="detailModal" class="modal">
        <div class="modal-content">
            <h3>Tambah Detail Laporan</h3>
            <form action="{{ route('finance.detail.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label>Periode</label>
                <input type="hidden" name="periode_laporan_id" id="detail_periode_id">
                <input type="text" id="detail_periode_display" disabled class="form-control">

                <label>User (PIC)</label>
                <select name="user_id" required class="form-control">
                    <option value="">-- Pilih User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>

                <label>Kegiatan</label>
                <input type="text" name="kegiatan" required class="form-control">

                <label>Deskripsi</label>
                <textarea name="deskripsi" required class="form-control" rows="3"></textarea>

                <label>Jumlah Anggaran (Rp)</label>
                <input type="number" name="jumlah_anggaran" required min="0" step="100" class="form-control">

                <label>Bukti Foto</label>
                <input type="file" name="bukti_foto" accept="image/*" class="form-control" style="border:none; padding-left:0;">

                <div class="form-actions">
                    <button type="submit" class="btn-dark">Simpan</button>
                    <button type="button" class="btn-light" onclick="closeDetailModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div id="periodeModal" class="modal">
        <div class="modal-content">
            <h3>Tambah Periode Laporan</h3>
            <form action="/periode-laporan" method="POST">
                @csrf
                <label>Tahun</label>
                <select name="tahun_id" required class="form-control">
                    <option value="">-- Pilih Tahun --</option>
                    @foreach ($tahun as $t)
                        <option value="{{ $t->id }}">{{ $t->tahun }}</option>
                    @endforeach
                </select>

                <label>Bulan</label>
                <select name="bulan_id" required class="form-control">
                    <option value="">-- Pilih Bulan --</option>
                    @foreach ($bulan as $b)
                        <option value="{{ $b->id }}">{{ $b->bulan }}</option>
                    @endforeach
                </select>

                <label>Divisi</label>
                <select name="divisi_id" required class="form-control">
                    <option value="">-- Pilih Divisi --</option>
                    @foreach ($divisi as $d)
                        <option value="{{ $d->id }}">{{ $d->nama_divisi }}</option>
                    @endforeach
                </select>

                <div class="form-actions">
                    <button type="submit" class="btn-dark">Simpan</button>
                    <button type="button" class="btn-light" onclick="closeAddPeriodModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content modal-center modal-sm">
            <div class="warning-icon">
                <svg class="icon-delete-modal" viewBox="0 0 24 24">
                    <path d="M12 2L1 21H23L12 2ZM13 18H11V16H13V18ZM13 14H11V10H13V14Z"/>
                </svg>
            </div>
            <h3 class="delete-title">Apakah Anda Yakin Ingin Menghapus Data?</h3>
            <p class="delete-subtitle">Data akan dihapus secara permanen</p>
            <div class="modal-actions">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-dark btn-modal-action">Ya</button>
                </form>
                <button type="button" class="btn-dark btn-modal-action" onclick="closeDeleteModal()">Tidak</button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div id="successModal" class="modal modal-show">
        <div class="modal-content modal-center modal-sm">
            <div class="warning-icon color-success">
                <svg class="icon-success-modal" viewBox="0 0 24 24">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <h3 class="delete-title">Berhasil!</h3>
            <p class="delete-subtitle mb-0">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div id="errorModal" class="modal modal-show">
        <div class="modal-content modal-center modal-sm">
            <div class="warning-icon text-error">
                <svg class="icon-success-modal" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <h3 class="delete-title">Oops, Terjadi Kesalahan!</h3>
            <p class="delete-subtitle mb-0">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <script>
        function openBudgetModal(periodeId, textDisplay) {
            document.getElementById('budget_periode_id').value = periodeId;
            document.getElementById('budget_periode_display').value = textDisplay;
            document.getElementById('budgetModal').style.display = 'block';
        }
        function closeBudgetModal() { document.getElementById('budgetModal').style.display = 'none'; }

        function openDetailModal(periodeId, textDisplay) {
            document.getElementById('detail_periode_id').value = periodeId;
            document.getElementById('detail_periode_display').value = textDisplay;
            document.getElementById('detailModal').style.display = 'block';
        }
        function closeDetailModal() { document.getElementById('detailModal').style.display = 'none'; }

        function openAddPeriodModal() { document.getElementById('periodeModal').style.display = 'block'; }
        function closeAddPeriodModal() { document.getElementById('periodeModal').style.display = 'none'; }

        function openDeleteModal(actionUrl) {
            document.getElementById('deleteModal').style.display = 'block';
            document.getElementById('deleteForm').action = actionUrl;
        }
        function closeDeleteModal() { document.getElementById('deleteModal').style.display = 'none'; }

        window.onclick = function(event) {
            if (event.target == document.getElementById('budgetModal')) closeBudgetModal();
            if (event.target == document.getElementById('detailModal')) closeDetailModal();
            if (event.target == document.getElementById('periodeModal')) closeAddPeriodModal();
            if (event.target == document.getElementById('deleteModal')) closeDeleteModal();
        };

        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(() => {
                let s = document.getElementById('successModal');
                let e = document.getElementById('errorModal');
                if(s) s.style.display = 'none';
                if(e) e.style.display = 'none';
            }, 1500);

            if(typeof renderFinanceChart === 'function') {
                renderFinanceChart('financeManagementChart', {{ $grandRealized ?? 0 }}, {{ $grandRemaining ?? 0 }}, {{ $grandPercentage ?? 0 }});
            }
        });
    </script>
</div>
@endsection
