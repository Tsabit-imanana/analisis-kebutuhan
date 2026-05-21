@extends('layout.sidebar')

@section('title', 'Kelola Divisi')

@section('content')
    <style>
        .page { padding:24px; font-family: Arial, sans-serif; color:#0f172a }
        .card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:18px; margin-bottom:18px }
        .grid { display:grid; grid-template-columns: 1fr 1fr; gap:12px }
        input { padding:10px; border:1px solid #cbd5e1; border-radius:8px; width:100% }
        .btn { padding:10px 14px; border-radius:8px; border:0; cursor:pointer; font-weight:700; display:inline-flex; align-items:center; justify-content:center; text-decoration:none }
        .btn-primary { background:#0f172a; color:#fff }
        .btn-secondary { background:#e2e8f0; color:#0f172a }
        table { width:100%; border-collapse:collapse; background:#fff; border-radius:12px; overflow:hidden }
        th, td { padding:10px; border-bottom:1px solid #e2e8f0; text-align:left }
    </style>

    <div class="page">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
            <div>
                <h1 style="margin:0">Kelola Divisi</h1>
                <p style="margin:6px 0 0;color:#64748b">Tambahkan, ubah, atau hapus divisi.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="card" style="background:#ecfdf5;color:#047857">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="card" style="background:#fef2f2;color:#b91c1c">{{ session('error') }}</div>
        @endif

        <div class="card">
            <h2 style="margin-top:0">Tambah Divisi</h2>
            <form method="POST" action="{{ route('settings.divisi.store') }}">
                @csrf
                <div class="grid">
                    <div>
                        <label>Nama Divisi</label>
                        <input type="text" name="nama_divisi" value="{{ old('nama_divisi') }}" required>
                    </div>
                    <div style="align-self:end">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card">
            <h3>Daftar Divisi</h3>
            <div style="margin:12px 0">
                <form method="GET" action="{{ route('settings.divisi.index') }}">
                    <input type="text" name="search" placeholder="Cari nama divisi" value="{{ $search ?? '' }}" style="max-width:320px"> 
                    <button class="btn btn-primary" type="submit">Cari</button>
                    <a href="{{ route('settings.divisi.index') }}" class="btn btn-secondary">Reset</a>
                </form>
            </div>
            <table>
                <thead>
                    <tr><th>Nama Divisi</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($divisis as $divisi)
                        <tr>
                            <td>{{ $divisi->nama_divisi }}</td>
                            <td>
                                <a href="{{ route('settings.divisi.edit', $divisi) }}" class="btn btn-secondary">Edit</a>
                                <form method="POST" action="{{ route('settings.divisi.destroy', $divisi) }}" style="display:inline-block" onsubmit="return confirm('Yakin ingin menghapus divisi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" style="background:#dc2626;color:#fff;border-radius:8px;padding:8px 10px">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="2">Tidak ada data divisi.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div style="margin-top:12px">{{ $divisis->links() }}</div>
        </div>
    </div>

@endsection
