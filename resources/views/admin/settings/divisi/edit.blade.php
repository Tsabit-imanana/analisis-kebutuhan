<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Divisi</title>
    <style>
        body { font-family: Arial, sans-serif; margin:0; background:#f8fafc; color:#0f172a }
        .page { padding:24px }
        .card { background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:18px; margin-bottom:18px }
        input { padding:10px; border:1px solid #cbd5e1; border-radius:8px; width:100% }
        .btn { padding:10px 14px; border-radius:8px; border:0; cursor:pointer; font-weight:700 }
        .btn-primary { background:#0f172a; color:#fff }
        .btn-secondary { background:#e2e8f0; color:#0f172a }
    </style>
</head>
<body>
    <div class="page">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
            <h1 style="margin:0">Edit Divisi</h1>
            <a href="{{ route('settings.divisi.index') }}" class="btn btn-secondary">Kembali</a>
        </div>

        @if($errors->any())
            <div class="card" style="background:#fef2f2;color:#b91c1c">
                <ul style="margin:0;padding-left:18px">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <form method="POST" action="{{ route('settings.divisi.update', $divisi) }}">
                @csrf
                @method('PUT')
                <div style="margin-bottom:12px">
                    <label>Nama Divisi</label>
                    <input type="text" name="nama_divisi" value="{{ old('nama_divisi', $divisi->nama_divisi) }}" required>
                </div>
                <div>
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
