<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Detail - {{ $periode->bulan->bulan }} {{ $periode->tahun->tahun }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #007bff;
        }

        .header h1 {
            color: #333;
        }

        .header p {
            color: #666;
            margin-top: 5px;
        }

        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .summary-section {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .summary-card.budget {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .summary-card.realisasi {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .summary-card.sisa {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .summary-card.percentage {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .summary-card h3 {
            font-size: 12px;
            font-weight: 600;
            opacity: 0.9;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .summary-card .amount {
            font-size: 24px;
            font-weight: bold;
        }

        h2 {
            color: #333;
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        thead {
            background-color: #007bff;
            color: white;
        }

        th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }

        .currency {
            text-align: right;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
            background-color: #f9f9f9;
            border-radius: 4px;
            border: 1px dashed #ddd;
        }

        .back-link {
            display: inline-block;
            padding: 8px 12px;
            background-color: #ddd;
            color: #000;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .back-link:hover {
            background-color: #bbb;
        }

        .photo {
            max-width: 100px;
            max-height: 100px;
            border-radius: 4px;
            cursor: pointer;
        }

        .photo:hover {
            opacity: 0.8;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-image {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            margin-top: 50px;
            max-height: 70vh;
            border-radius: 8px;
        }

        .close-modal {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-modal:hover {
            color: #bbb;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-item {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #007bff;
        }

        .info-item label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        .info-item p {
            color: #333;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Finance Detail</h1>
                <p>{{ $periode->bulan->bulan }} {{ $periode->tahun->tahun }} - {{ $periode->divisi->nama_divisi }}</p>
            </div>
            <a href="{{ route('finance.index') }}" class="back-link">← Back to Finance</a>
        </div>

        <!-- Summary Cards -->
        <div class="summary-section">
            <div class="summary-card budget">
                <h3>Total Budget</h3>
                <div class="amount">Rp{{ number_format($totalBudget, 0, ',', '.') }}</div>
            </div>
            <div class="summary-card realisasi">
                <h3>Total Realisasi</h3>
                <div class="amount">Rp{{ number_format($totalRealized, 0, ',', '.') }}</div>
            </div>
            <div class="summary-card sisa">
                <h3>Sisa Anggaran</h3>
                <div class="amount">Rp{{ number_format($remaining, 0, ',', '.') }}</div>
            </div>
            <div class="summary-card percentage">
                <h3>% Realisasi</h3>
                <div class="amount">{{ $totalBudget > 0 ? round(($totalRealized / $totalBudget) * 100, 2) : 0 }}%</div>
            </div>
        </div>

        <!-- Period Information -->
        <div class="info-grid">
            <div class="info-item">
                <label>Periode</label>
                <p>{{ $periode->bulan->bulan }} {{ $periode->tahun->tahun }}</p>
            </div>
            <div class="info-item">
                <label>Divisi</label>
                <p>{{ $periode->divisi->nama_divisi }}</p>
            </div>
        </div>

        <!-- Budget Section -->
        <h2>Daftar Budget</h2>
        @if($budgets->isEmpty())
            <div class="empty-state">
                <p>Tidak ada data budget untuk periode ini.</p>
            </div>
        @else
            <table>
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
                            <td class="currency">
                                <strong>Rp{{ number_format($budget->jumlah_budget, 0, ',', '.') }}</strong>
                            </td>
                            <td>{{ $budget->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Detail Laporan Section -->
        <h2>Detail Realisasi Anggaran</h2>
        @if($details->isEmpty())
            <div class="empty-state">
                <p>Tidak ada data detail laporan untuk periode ini.</p>
            </div>
        @else
            <table>
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
                            <td>
                                <strong>{{ $detail->kegiatan }}</strong>
                            </td>
                            <td>{{ substr($detail->deskripsi, 0, 50) }}{{ strlen($detail->deskripsi) > 50 ? '...' : '' }}</td>
                            <td class="currency">
                                <strong>Rp{{ number_format($detail->jumlah_anggaran, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @if ($detail->bukti_foto)
                                    <img src="{{ asset('storage/' . $detail->bukti_foto) }}" 
                                         alt="Bukti Foto" 
                                         class="photo" 
                                         onclick="openModal(this.src)">
                                @else
                                    <span style="color: #999;">Tidak ada foto</span>
                                @endif
                            </td>
                            <td>{{ $detail->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="modal">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <img class="modal-image" id="modalImage" src="" alt="Preview">
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
</body>
</html>
