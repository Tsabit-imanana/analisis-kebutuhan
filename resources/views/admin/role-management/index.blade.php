@extends('layout.sidebar')

@section('title', 'Role Management')

@section('content')
    @vite(['resources/css/role-management/index.css'])
    <div class="role-container">
        <div class="header">
            <div>
                <h1>Role Management</h1>
                <p class="muted">Kelola user, role, dan data akun yang sudah terdaftar.</p>
            </div>
            <button type="button" class="btn btn-primary" onclick="openAddModal()">Tambah User</button>
        </div>

        @if (session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="message error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="message error">
                <strong>Terjadi kesalahan:</strong>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-table">
            <div class="search-row">
                <form method="GET" action="{{ route('admin.users.index') }}" class="search-form">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama, email, NIK, role, atau divisi">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
                </form>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>NIK</th>
                            <th>Role</th>
                            <th>Divisi</th>
                            <th>No Telepon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->nik }}</td>
                                <td>{{ strtoupper($user->role) }}</td>
                                <td>{{ $user->divisi->nama_divisi ?? '-' }}</td>
                                <td>{{ $user->no_telepon }}</td>
                                <td>
                                    <div class="actions">
                                        <button type="button" class="btn btn-secondary"
                                            onclick="openEditModal(this)"
                                            data-id="{{ $user->id }}"
                                            data-name="{{ htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8') }}"
                                            data-email="{{ htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8') }}"
                                            data-nik="{{ htmlspecialchars($user->nik, ENT_QUOTES, 'UTF-8') }}"
                                            data-tgl="{{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : '' }}"
                                            data-jk="{{ htmlspecialchars($user->jenis_kelamin, ENT_QUOTES, 'UTF-8') }}"
                                            data-alamat="{{ htmlspecialchars($user->alamat, ENT_QUOTES, 'UTF-8') }}"
                                            data-telp="{{ htmlspecialchars($user->no_telepon, ENT_QUOTES, 'UTF-8') }}"
                                            data-divisi="{{ $user->divisi_id }}"
                                            data-role="{{ $user->role }}"
                                        >
                                            Edit
                                        </button>

                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">Tidak ada data user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pager">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <div id="addModal" class="modal">
        <div class="modal-content modal-lg">
            <h2>Tambah User</h2>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="grid">
                    <div class="field"><label>Nama</label><input type="text" name="name" required></div>
                    <div class="field"><label>Email</label><input type="email" name="email" required></div>
                    <div class="field"><label>Password</label><input type="password" name="password" required></div>
                    <div class="field"><label>NIK</label><input type="text" name="nik" required></div>
                    <div class="field"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" required></div>
                    <div class="field"><label>Jenis Kelamin</label><input type="text" name="jenis_kelamin" placeholder="Laki-laki / Perempuan" required></div>
                    <div class="field full"><label>Alamat</label><textarea name="alamat" required></textarea></div>
                    <div class="field"><label>No Telepon</label><input type="text" name="no_telepon" required></div>
                    <div class="field"><label>Divisi</label>
                        <select name="divisi_id">
                            <option value="">-- Tanpa Divisi --</option>
                            @foreach ($divisis as $divisi)
                                <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field"><label>Role</label>
                        <select name="role" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}">{{ strtoupper($role) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content modal-lg">
            <h2>Edit User</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="grid">
                    <div class="field"><label>Nama</label><input type="text" name="name" id="edit_name" required></div>
                    <div class="field"><label>Email</label><input type="email" name="email" id="edit_email" required></div>
                    <div class="field"><label>Password Baru</label><input type="password" name="password" placeholder="Kosongkan jika tidak diubah"></div>
                    <div class="field"><label>NIK</label><input type="text" name="nik" id="edit_nik" required></div>
                    <div class="field"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" id="edit_tanggal_lahir" required></div>
                    <div class="field"><label>Jenis Kelamin</label><input type="text" name="jenis_kelamin" id="edit_jenis_kelamin" required></div>
                    <div class="field full"><label>Alamat</label><textarea name="alamat" id="edit_alamat" required></textarea></div>
                    <div class="field"><label>No Telepon</label><input type="text" name="no_telepon" id="edit_no_telepon" required></div>
                    <div class="field"><label>Divisi</label>
                        <select name="divisi_id" id="edit_divisi_id">
                            <option value="">-- Tanpa Divisi --</option>
                            @foreach ($divisis as $divisi)
                                <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field"><label>Role</label>
                        <select name="role" id="edit_role" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}">{{ strtoupper($role) }}</option>
                            @endforeach
                        </select>
                    </div>
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
            document.getElementById('edit_name').value = button.dataset.name;
            document.getElementById('edit_email').value = button.dataset.email;
            document.getElementById('edit_nik').value = button.dataset.nik;
            document.getElementById('edit_tanggal_lahir').value = button.dataset.tgl;
            document.getElementById('edit_jenis_kelamin').value = button.dataset.jk;
            document.getElementById('edit_alamat').value = button.dataset.alamat;
            document.getElementById('edit_no_telepon').value = button.dataset.telp;

            document.getElementById('edit_divisi_id').value = button.dataset.divisi || '';
            document.getElementById('edit_role').value = button.dataset.role;

            document.getElementById('editForm').action = "{{ url('admin/users') }}/" + id;
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
