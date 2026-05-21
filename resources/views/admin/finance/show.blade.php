@extends('layout.sidebar')

@section('title', 'Finance Detail - MUMS')

@section('content')
@vite(['resources/css/dashboard.css', 'resources/css/finance.css'])

<div class="dashboard-container finance-container">
    <div class="dashboard-header finance-header">
        <div>
            <h1>Finance Detail</h1>
            <p>{{ $periode->bulan->bulan }} {{ $periode->tahun->tahun }} - {{ $periode->divisi->nama_divisi }}</p>
        </div>
        <div class="finance-toolbar">
            <a href="{{ route('finance.index') }}" class="finance-btn finance-btn--secondary">← Back to Finance</a>
        </div>
    </div>

    <div class="stats-row finance-summary">
        <div class="stat-card finance-card">
            <span class="stat-title">Total Budget</span>
            <span class="stat-value">Rp{{ number_format($totalBudget, 0, ',', '.') }}</span>
        </div>
        <div class="stat-card finance-card">
            <span class="stat-title">Total Realisasi</span>
            <span class="stat-value">Rp{{ number_format($totalRealized, 0, ',', '.') }}</span>
        </div>
        <div class="stat-card finance-card">
            <span class="stat-title">Sisa Anggaran</span>
            <span class="stat-value">Rp{{ number_format($remaining, 0, ',', '.') }}</span>
        </div>
        <div class="stat-card finance-card">
            <span class="stat-title">% Realisasi</span>
            <span class="stat-value">{{ $totalBudget > 0 ? round(($totalRealized / $totalBudget) * 100, 2) : 0 }}%</span>
        </div>
    </div>

    <div class="finance-info-grid">
        <div class="finance-info-item">
            <label>Periode</label>
            <p>{{ $periode->bulan->bulan }} {{ $periode->tahun->tahun }}</p>
        </div>
        <div class="finance-info-item">
            <label>Divisi</label>
            <p>{{ $periode->divisi->nama_divisi }}</p>
        </div>
    </div>

    <h2 style="margin:16px 0 10px 0; font-size:18px;">Daftar Budget</h2>
    @if($budgets->isEmpty())
        <div class="stat-card finance-card">
            <div class="finance-empty">Tidak ada data budget untuk periode ini.</div>
        </div>
    @else
        <table class="finance-table">
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
                        <td class="currency">Rp{{ number_format($budget->jumlah_budget, 0, ',', '.') }}</td>
                        <td>{{ $budget->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2 style="margin:16px 0 10px 0; font-size:18px;">Detail Realisasi Anggaran</h2>
    @if($details->isEmpty())
        <div class="stat-card finance-card">
            <div class="finance-empty">Tidak ada data detail laporan untuk periode ini.</div>
        </div>
    @else
        <table class="finance-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>PIC</th>
                    <th>Kegiatan</th>
                    <th>Deskripsi</th>
                    <th>Jumlah Anggaran</th>
                    <th>Bukti Foto</th>
                    <th>Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->user->name ?? '-' }}</td>
                        <td><strong>{{ $detail->kegiatan }}</strong></td>
                        <td>{{ substr($detail->deskripsi, 0, 50) }}{{ strlen($detail->deskripsi) > 50 ? '...' : '' }}</td>
                        <td class="currency">Rp{{ number_format($detail->jumlah_anggaran, 0, ',', '.') }}</td>
                        <td>
                            @if ($detail->bukti_foto)
                                <img
                                    src="{{ asset('storage/' . $detail->bukti_foto) }}"
                                    alt="Bukti Foto"
                                    class="finance-photo"
                                    onclick="openModal(this.src)"
                                >
                            @else
                                <span style="color:#64748b;">Tidak ada foto</span>
                            @endif
                        </td>
                        <td>{{ $detail->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div id="imageModal" class="finance-image-modal">
        <span class="finance-image-modal__close" onclick="closeModal()">&times;</span>
        <img class="finance-image-modal__img" id="modalImage" src="" alt="Preview">
    </div>

    <script>
        function openModal(src) {
            document.getElementById('imageModal').style.display = 'block';
            document.getElementById('modalImage').src = src;
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
    </script>
</div>
@endsection
