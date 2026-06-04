@extends('layout.sidebar')

@section('title', 'Finance Detail - MUMS')

@section('content')
@vite(['resources/css/dashboard.css', 'resources/css/finance.css'])

<div class="finance-container">
    <div class="page-header">
        <div class="header-text">
            <h1>Finance Detail</h1>
            <p>{{ $periode->bulan->bulan }} {{ $periode->tahun->tahun }} - {{ $periode->divisi->nama_divisi }}</p>
        </div>
        <a href="{{ route('finance.index') }}" class="btn-white">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Finance
        </a>
    </div>

    <div class="stats-row four-stats mb-4">
        <div class="stat-card">
            <span class="stat-title">Total Budget</span>
            <span class="stat-value">Rp{{ number_format($totalBudget, 0, ',', '.') }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Total Realisasi</span>
            <span class="stat-value">Rp{{ number_format($totalRealized, 0, ',', '.') }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Sisa Anggaran</span>
            <span class="stat-value">Rp{{ number_format($remaining, 0, ',', '.') }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">% Realisasi</span>
            <span class="stat-value">{{ $totalBudget > 0 ? round(($totalRealized / $totalBudget) * 100, 2) : 0 }}%</span>
        </div>
    </div>

    <div class="table-container mb-4">
        <div class="table-header">
            <h3>Daftar Budget</h3>
        </div>
        @if($budgets->isEmpty())
            <p class="empty-data">Tidak ada data budget untuk periode ini.</p>
        @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jumlah Budget</th>
                            <th>Tanggal Input</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($budgets as $index => $budget)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>Rp{{ number_format($budget->jumlah_budget, 0, ',', '.') }}</td>
                                <td>{{ $budget->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <button type="button" onclick="openDeleteModal('{{ route('finance.budget.destroy', $budget->id) }}')" class="btn-icon" title="Delete">
                                        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3>Detail Realisasi Anggaran</h3>
        </div>
        @if($details->isEmpty())
            <p class="empty-data">Tidak ada data detail laporan untuk periode ini.</p>
        @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>PIC</th>
                            <th>Kegiatan</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Anggaran</th>
                            <th>Bukti Foto</th>
                            <th>Tanggal Input</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->user->name ?? '-' }}</td>
                                <td><strong>{{ $detail->kegiatan }}</strong></td>
                                <td>{{ substr($detail->deskripsi, 0, 50) }}{{ strlen($detail->deskripsi) > 50 ? '...' : '' }}</td>
                                <td>Rp{{ number_format($detail->jumlah_anggaran, 0, ',', '.') }}</td>
                                <td>
                                    @if ($detail->bukti_foto)
                                        <img src="{{ asset('storage/' . $detail->bukti_foto) }}" alt="Bukti Foto" class="table-img cursor-pointer" onclick="openImageModal(this.src)">
                                    @else
                                        <span class="text-muted">Tidak ada foto</span>
                                    @endif
                                </td>
                                <td>{{ $detail->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <button type="button" onclick="openDeleteModal('{{ route('finance.detail.destroy', $detail->id) }}')" class="btn-icon" title="Delete">
                                        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div id="imageModal" class="modal">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        <img class="image-modal-content" id="modalImage" src="" alt="Preview">
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

    <script>
        function openImageModal(src) {
            document.getElementById('imageModal').style.display = 'block';
            document.getElementById('modalImage').src = src;
        }
        function closeImageModal() { document.getElementById('imageModal').style.display = 'none'; }

        function openDeleteModal(actionUrl) {
            document.getElementById('deleteModal').style.display = 'block';
            document.getElementById('deleteForm').action = actionUrl;
        }
        function closeDeleteModal() { document.getElementById('deleteModal').style.display = 'none'; }

        window.onclick = function(event) {
            if (event.target == document.getElementById('imageModal')) closeImageModal();
            if (event.target == document.getElementById('deleteModal')) closeDeleteModal();
        };
    </script>
</div>
@endsection
