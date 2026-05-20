<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f8fafc;
            color: #0f172a;
        }

        .page {
            padding: 24px;
            max-width: 960px;
            margin: 0 auto;
        }

        .card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
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
            margin-top: 16px;
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

        .message {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
        }

        .message.error { background: #fef2f2; color: #b91c1c; }
    </style>
</head>
<body>
    <div class="page">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom: 20px;">
            <div>
                <h1 style="margin: 0;">Edit User</h1>
                <p style="margin: 6px 0 0; color:#64748b;">Perbarui data akun dan role user.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
        </div>

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
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="grid">
                    <div class="field"><label>Nama</label><input type="text" name="name" value="{{ old('name', $user->name) }}" required></div>
                    <div class="field"><label>Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
                    <div class="field"><label>Password Baru</label><input type="password" name="password" placeholder="Kosongkan jika tidak diubah"></div>
                    <div class="field"><label>NIK</label><input type="text" name="nik" value="{{ old('nik', $user->nik) }}" required></div>
                    <div class="field"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir?->format('Y-m-d') ?? $user->tanggal_lahir) }}" required></div>
                    <div class="field"><label>Jenis Kelamin</label><input type="text" name="jenis_kelamin" value="{{ old('jenis_kelamin', $user->jenis_kelamin) }}" required></div>
                    <div class="field full"><label>Alamat</label><textarea name="alamat" required>{{ old('alamat', $user->alamat) }}</textarea></div>
                    <div class="field"><label>No Telepon</label><input type="text" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" required></div>
                    <div class="field"><label>Divisi</label>
                        <select name="divisi_id">
                            <option value="">-- Tanpa Divisi --</option>
                            @foreach ($divisis as $divisi)
                                <option value="{{ $divisi->id }}" @selected(old('divisi_id', $user->divisi_id) == $divisi->id)>
                                    {{ $divisi->nama_divisi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field"><label>Role</label>
                        <select name="role" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ strtoupper($role) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
