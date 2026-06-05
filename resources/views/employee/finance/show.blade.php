@extends('layout.employee_sidebar')

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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($budgets as $index => $budget)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>Rp{{ number_format($budget->jumlah_budget, 0, ',', '.') }}</td>
                                <td>{{ $budget->created_at->format('d/m/Y H:i') }}</td>
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
            <button type="button" class="btn-dark btn-sm" onclick="openDetailModal()">
                + Tambah Detail
            </button>
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
                                    <div class="action-cell">
                                        <button type="button" class="btn-icon" title="Edit"
                                            onclick="openEditDetailModal(
                                                {{ $detail->id }},
                                                '{{ addslashes($detail->kegiatan) }}',
                                                '{{ addslashes($detail->deskripsi) }}',
                                                {{ $detail->jumlah_anggaran }}
                                            )">
                                            <svg viewBox="0 0 24 24">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="finance-modal-header">
                <h3>Tambah Detail Laporan</h3>
                <button type="button" class="finance-modal-close" onclick="closeDetailModal()">&times;</button>
            </div>
            <form action="{{ route('finance.detail.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="periode_laporan_id" value="{{ $periode->id }}">

                @if(isset($users))
                    <label>User (PIC)</label>
                    <select name="user_id" required class="form-control">
                        <option value="">-- Pilih User --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                @endif

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

    <div id="editDetailModal" class="modal">
        <div class="modal-content">
            <div class="finance-modal-header">
                <h3>Edit Detail Laporan</h3>
                <button type="button" class="finance-modal-close" onclick="closeEditDetailModal()">&times;</button>
            </div>
            <form id="editDetailForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="periode_laporan_id" value="{{ $periode->id }}">

                <label>Kegiatan</label>
                <input type="text" name="kegiatan" id="edit_kegiatan" required class="form-control">

                <label>Deskripsi</label>
                <textarea name="deskripsi" id="edit_deskripsi" required class="form-control" rows="3"></textarea>

                <label>Jumlah Anggaran (Rp)</label>
                <input type="number" name="jumlah_anggaran" id="edit_jumlah_anggaran" required min="0" step="100" class="form-control">

                <label>Bukti Foto (Kosongkan jika tidak diubah)</label>
                <input type="file" name="bukti_foto" accept="image/*" class="form-control" style="border:none; padding-left:0;">

                <div class="form-actions">
                    <button type="submit" class="btn-dark">Update</button>
                    <button type="button" class="btn-light" onclick="closeEditDetailModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div id="imageModal" class="modal">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        <img class="image-modal-content" id="modalImage" src="" alt="Preview">
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
        function openDetailModal() { document.getElementById('detailModal').style.display = 'block'; }
        function closeDetailModal() { document.getElementById('detailModal').style.display = 'none'; }

        function openEditDetailModal(id, kegiatan, deskripsi, jumlah) {
            document.getElementById('editDetailModal').style.display = 'block';
            document.getElementById('edit_kegiatan').value = kegiatan;
            document.getElementById('edit_deskripsi').value = deskripsi;
            document.getElementById('edit_jumlah_anggaran').value = jumlah;

            document.getElementById('editDetailForm').action = "{{ url('finance/detail') }}/" + id;
        }
        function closeEditDetailModal() { document.getElementById('editDetailModal').style.display = 'none'; }

        function openImageModal(src) {
            document.getElementById('imageModal').style.display = 'block';
            document.getElementById('modalImage').src = src;
        }
        function closeImageModal() { document.getElementById('imageModal').style.display = 'none'; }

        window.onclick = function(event) {
            if (event.target == document.getElementById('detailModal')) closeDetailModal();
            if (event.target == document.getElementById('editDetailModal')) closeEditDetailModal();
            if (event.target == document.getElementById('imageModal')) closeImageModal();
        };

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
