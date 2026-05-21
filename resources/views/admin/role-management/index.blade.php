@extends('layout.sidebar')

@section('title', 'Role Management')

@section('content')
    <style>
        .page {
            font-family: Arial, sans-serif;
            color: #0f172a;
        }

        .page {
            padding: 24px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
            margin-bottom: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field label {
            font-size: 13px;
            font-weight: 700;
        }

        input, select, textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font: inherit;
        }

        textarea {
            min-height: 84px;
            resize: vertical;
        }

        .full {
            grid-column: 1 / -1;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary { background: #0f172a; color: #fff; }
        .btn-secondary { background: #e2e8f0; color: #0f172a; }
        .btn-danger { background: #dc2626; color: #fff; }

        .message {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
        }

        .message.success { background: #ecfdf5; color: #047857; }
        .message.error { background: #fef2f2; color: #b91c1c; }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
        }

        th, td {
            border-bottom: 1px solid #e2e8f0;
            padding: 12px;
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        th {
            background: #f8fafc;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .pager {
            margin-top: 16px;
        }

        .muted {
            color: #64748b;
            font-size: 14px;
        }

        .search-row {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .search-row input {
            max-width: 360px;
        }
    </style>
    </style>

    <div class="page">
        <div class="header">
            <div>
                <h1 style="margin: 0;">Role Management</h1>
                <p class="muted" style="margin: 6px 0 0;">Kelola user, role, dan data akun yang sudah terdaftar.</p>
            </div>
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
                <ul style="margin: 8px 0 0 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <h2 style="margin-top: 0;">Tambah User</h2>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="grid">
                    <div class="field"><label>Nama</label><input type="text" name="name" value="{{ old('name') }}" required></div>
                    <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required></div>
                    <div class="field"><label>Password</label><input type="password" name="password" required></div>
                    <div class="field"><label>NIK</label><input type="text" name="nik" value="{{ old('nik') }}" required></div>
                    <div class="field"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required></div>
                    <div class="field"><label>Jenis Kelamin</label><input type="text" name="jenis_kelamin" value="{{ old('jenis_kelamin') }}" placeholder="Laki-laki / Perempuan" required></div>
                    <div class="field full"><label>Alamat</label><textarea name="alamat" required>{{ old('alamat') }}</textarea></div>
                    <div class="field"><label>No Telepon</label><input type="text" name="no_telepon" value="{{ old('no_telepon') }}" required></div>
                    <div class="field"><label>Divisi</label>
                        <select name="divisi_id">
                            <option value="">-- Tanpa Divisi --</option>
                            @foreach ($divisis as $divisi)
                                <option value="{{ $divisi->id }}" @selected(old('divisi_id') == $divisi->id)>
                                    {{ $divisi->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field"><label>Role</label>
                        <select name="role" required>
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" @selected(old('role') === $role)>{{ strtoupper($role) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="actions" style="margin-top: 16px;">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>

        <div class="card">
            <div class="search-row">
                <form method="GET" action="{{ route('admin.users.index') }}" class="actions" style="width: 100%;">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, NIK, role, atau divisi">
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
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary">Edit</a>
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
                                <td colspan="7">Tidak ada data user.</td>
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

@endsection
