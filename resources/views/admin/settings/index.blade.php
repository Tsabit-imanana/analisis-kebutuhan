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

        @if(session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="message error">{{ session('error') }}</div>
        @endif

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

                                        <form method="POST" action="{{ route('settings.destroy', $divisi) }}" class="inline-form" onsubmit="return confirm('Yakin ingin menghapus divisi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
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

    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }
        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        function openEditModal(button) {
            document.getElementById('editModal').style.display = 'block';

            var id = button.dataset.id;
            document.getElementById('edit_nama_divisi').value = button.dataset.nama;

            document.getElementById('editForm').action = "{{ url('settings') }}/" + id;
        }
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('addModal')) closeAddModal();
            if (event.target == document.getElementById('editModal')) closeEditModal();
        };
    </script>
@endsection
