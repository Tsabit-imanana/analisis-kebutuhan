@extends('layout.sidebar')

@section('title', 'Kelola Divisi - MUMS')

@section('content')
    @vite('resources/css/settings/index.css')

    <div class="settings-container">
        <div class="header">
            <div>
                <h1>Kelola Divisi</h1>
                <p class="muted">Tambahkan, ubah, atau hapus divisi.</p>
            </div>
            <button type="button" class="btn btn-primary" onclick="openAddModal()">Tambah Divisi</button>
        </div>

        @if($errors->any())
            <div class="message error">
                <strong>Terjadi kesalahan:</strong>
                <ul class="error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="search-row">
                <form method="GET" action="{{ route('settings.index') }}" class="search-form">
                    <input type="text" name="search" placeholder="Cari nama divisi" value="{{ $search ?? '' }}">
                    <button class="btn btn-primary" type="submit">Cari</button>
                    <a href="{{ route('settings.index') }}" class="btn btn-secondary">Reset</a>
                </form>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Divisi</th>
                            <th class="action-col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($divisis as $divisi)
                            <tr>
                                <td>{{ $divisi->nama_divisi }}</td>
                                <td>
                                    <div class="actions">
                                        <button type="button" class="btn btn-secondary"
                                            onclick="openEditModal(this)"
                                            data-id="{{ $divisi->id }}"
                                            data-nama="{{ htmlspecialchars($divisi->nama_divisi, ENT_QUOTES, 'UTF-8') }}">
                                            Edit
                                        </button>

                                        <button type="button" class="btn btn-danger" onclick="openDeleteModal('{{ route('settings.destroy', $divisi->id) }}')">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="empty-state">Tidak ada data divisi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pager">
                {{ $divisis->links() }}
            </div>
        </div>

        <div class="card">
            <div class="header" style="margin-bottom: 12px;">
                <div>
                    <h1>Report Rekap</h1>
                    <p class="muted">Unduh rangkuman data Task, Weekly Log, dan Finance Budgeting.</p>
                </div>
                <button type="button" class="btn btn-primary" onclick="openReportModal()">Buat Report</button>
            </div>
        </div>
    </div>

    <div id="addModal" class="modal">
        <div class="modal-content modal-md">
            <h2>Tambah Divisi</h2>
            <form method="POST" action="{{ route('settings.store') }}">
                @csrf
                <div class="field">
                    <label>Nama Divisi</label>
                    <input type="text" name="nama_divisi" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content modal-md">
            <h2>Edit Divisi</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="field">
                    <label>Nama Divisi</label>
                    <input type="text" name="nama_divisi" id="edit_nama_divisi" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="reportModal" class="modal">
        <div class="modal-content modal-md">
            <h2>Buat Report Rekap</h2>
            <form method="POST" action="{{ route('settings.report') }}">
                @csrf
                <div class="field">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="start_date" required>
                </div>
                <div class="field">
                    <label>Tanggal Akhir</label>
                    <input type="date" name="end_date" required>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeReportModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Unduh PDF</button>
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
            <p class="delete-subtitle">Data divisi akan dihapus secara permanen</p>
            <div class="modal-actions">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary btn-modal-action">Ya</button>
                </form>
                <button type="button" class="btn btn-secondary btn-modal-action" onclick="closeDeleteModal()">Tidak</button>
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
        function openAddModal() { document.getElementById('addModal').style.display = 'block'; }
        function closeAddModal() { document.getElementById('addModal').style.display = 'none'; }

        function openEditModal(button) {
            document.getElementById('editModal').style.display = 'block';
            var id = button.dataset.id;
            document.getElementById('edit_nama_divisi').value = button.dataset.nama;
            document.getElementById('editForm').action = "{{ url('settings') }}/" + id;
        }
        function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }

        function openReportModal() { document.getElementById('reportModal').style.display = 'block'; }
        function closeReportModal() { document.getElementById('reportModal').style.display = 'none'; }

        function openDeleteModal(actionUrl) {
            document.getElementById('deleteModal').style.display = 'block';
            document.getElementById('deleteForm').action = actionUrl;
        }
        function closeDeleteModal() { document.getElementById('deleteModal').style.display = 'none'; }

        window.onclick = function(event) {
            if (event.target == document.getElementById('addModal')) closeAddModal();
            if (event.target == document.getElementById('editModal')) closeEditModal();
            if (event.target == document.getElementById('reportModal')) closeReportModal();
            if (event.target == document.getElementById('deleteModal')) closeDeleteModal();
        };

        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                var s = document.getElementById('successModal');
                var e = document.getElementById('errorModal');
                if (s) s.style.display = 'none';
                if (e) e.style.display = 'none';
            }, 1500);
        });
    </script>
@endsection
