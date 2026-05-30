@extends('layout.employee_sidebar')

@section('title', 'Finance Management - MUMS')

@section('content')
@vite(['resources/css/dashboard.css', 'resources/css/finance.css'])

<div class="dashboard-container finance-container">
    <div class="dashboard-header finance-header">
        <div>
            <h1>Finance Management</h1>
            <p>Kelola periode laporan, budget, dan realisasi anggaran.</p>
        </div>
        <div class="finance-toolbar">
            <a href="/" class="finance-btn finance-btn--secondary">← Back</a>
            <button type="button" onclick="openAddPeriodModal()" class="finance-btn finance-btn--primary">+ Tambah Periode</button>
        </div>
    </div>

    @if(session('success'))
        <div class="finance-alert finance-alert--success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="finance-alert finance-alert--error">
            {{ session('error') }}
        </div>
    @endif

    @php
        $grandBudget = $finansialData->sum('totalBudget');
        $grandRealized = $finansialData->sum('totalRealized');
        $grandRemaining = $grandBudget - $grandRealized;
        $grandPercentage = $grandBudget > 0 ? round(($grandRealized / $grandBudget) * 100, 2) : 0;
        $grandPercentageForBar = max(0, min(100, $grandPercentage));

        $finansialByDivisi = $finansialData
            ->groupBy(fn ($item) => $item['periode']->divisi_id);
    @endphp

    <div class="stat-card finance-card" style="margin: 0 0 12px 0;">
        <div class="finance-graph">
            <div class="finance-graph__header">
                <div>
                    <h2 class="finance-graph__title">Grafik Umum Budget vs Realisasi</h2>
                    <p class="finance-graph__subtitle">
                        Total Budget <strong>Rp{{ number_format($grandBudget, 0, ',', '.') }}</strong> •
                        Realisasi <strong>Rp{{ number_format($grandRealized, 0, ',', '.') }}</strong> •
                        Sisa <strong>Rp{{ number_format($grandRemaining, 0, ',', '.') }}</strong>
                    </p>
                </div>
                <span class="finance-badge @if($grandPercentage <= 70) finance-badge--good @elseif($grandPercentage <= 90) finance-badge--warning @else finance-badge--danger @endif">
                    {{ $grandPercentage }}%
                </span>
            </div>

            <div class="finance-progress" aria-label="Progress realisasi terhadap total budget">
                <div class="finance-progress__value" style="width: {{ $grandPercentageForBar }}%;"></div>
            </div>

            <div class="finance-progress-legend">
                <span><span class="finance-dot finance-dot--budget"></span>Budget</span>
                <span><span class="finance-dot finance-dot--realisasi"></span>Realisasi</span>
                <span><span class="finance-dot finance-dot--sisa"></span>Sisa</span>
            </div>
        </div>
    </div>

    <h2 style="margin:16px 0 10px 0; font-size:18px;">Daftar Periode per Divisi</h2>
    @if($finansialData->isEmpty())
        <div class="stat-card finance-card" style="margin: 0 0 12px 0;">
            <div class="finance-empty">
                Tidak ada data periode laporan.
                <a href="#" onclick="openAddPeriodModal(); return false;">Tambah periode sekarang.</a>
            </div>
        </div>
    @else
        @foreach ($finansialByDivisi as $divisiId => $items)
            @php
                $firstPeriode = $items->first()['periode'] ?? null;
                $divisiName = $firstPeriode?->divisi?->nama_divisi ?? '-';
            @endphp

            <h3 style="margin:14px 0 10px 0; font-size:16px;">{{ $divisiName }}</h3>

            <div class="finance-divisi-section" data-page-size="5">
                <table class="finance-table">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Total Budget</th>
                            <th>Total Realisasi</th>
                            <th>Sisa Anggaran</th>
                            <th>% Realisasi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $data)
                            <tr class="periode-row" data-periode-id="{{ $data['periode']->id }}" data-divisi="{{ $data['periode']->divisi_id }}" data-tahun="{{ $data['periode']->tahun_id }}" data-bulan="{{ $data['periode']->bulan_id }}">
                                <td>
                                    <strong>{{ $data['periode']->bulan->bulan ?? '-' }} {{ $data['periode']->tahun->tahun ?? '-' }}</strong>
                                </td>
                                <td class="currency">Rp{{ number_format($data['totalBudget'], 0, ',', '.') }}</td>
                                <td class="currency">Rp{{ number_format($data['totalRealized'], 0, ',', '.') }}</td>
                                <td class="currency">
                                    <span class="finance-badge {{ $data['remaining'] >= 0 ? 'finance-badge--good' : 'finance-badge--danger' }}">
                                        Rp{{ number_format($data['remaining'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="percentage">
                                    <span class="finance-badge @if($data['percentage'] <= 70) finance-badge--good @elseif($data['percentage'] <= 90) finance-badge--warning @else finance-badge--danger @endif">
                                        {{ $data['percentage'] }}%
                                    </span>
                                </td>
                                <td>
                                    <div class="finance-action-buttons">
                                        <a href="{{ route('finance.show', $data['periode']->id) }}" class="finance-btn finance-btn--secondary finance-btn--sm">View</a>
                                        <button type="button" onclick="openBudgetModal({{ $data['periode']->id }})" class="finance-btn finance-btn--secondary finance-btn--sm">+ Budget</button>
                                        <button type="button" onclick="openDetailModal({{ $data['periode']->id }})" class="finance-btn finance-btn--secondary finance-btn--sm">+ Detail</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="finance-pagination">
                    <button type="button" class="finance-btn finance-btn--secondary finance-btn--sm" data-action="prev">Prev</button>
                    <span class="finance-pagination__info">Page 1</span>
                    <button type="button" class="finance-btn finance-btn--secondary finance-btn--sm" data-action="next">Next</button>
                </div>
            </div>
        @endforeach
    @endif

    <div id="budgetModal" class="modal">
        <div class="modal-content">
            <div class="finance-modal-header">
                <h3>Tambah Budget</h3>
                <button type="button" class="finance-modal-close" onclick="closeBudgetModal()">&times;</button>
            </div>
            <form action="{{ route('finance.budget.store') }}" method="POST">
                @csrf
                <div class="finance-field">
                    <label>Periode</label>
                    <input type="hidden" name="periode_laporan_id" id="budget_periode_id">
                    <input type="text" id="budget_periode_display" disabled>
                </div>
                <div class="finance-field">
                    <label>Jumlah Budget (Rp)</label>
                    <input type="number" name="jumlah_budget" required min="0" step="100">
                </div>
                <div class="finance-form-actions">
                    <button type="button" onclick="closeBudgetModal()" class="finance-btn finance-btn--secondary">Batal</button>
                    <button type="submit" class="finance-btn finance-btn--primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="finance-modal-header">
                <h3>Tambah Detail Laporan</h3>
                <button type="button" class="finance-modal-close" onclick="closeDetailModal()">&times;</button>
            </div>
            <form action="{{ route('finance.detail.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="finance-field">
                    <label>Periode</label>
                    <input type="hidden" name="periode_laporan_id" id="detail_periode_id">
                    <input type="text" id="detail_periode_display" disabled>
                </div>
                <div class="finance-field">
                    <label>User (PIC)</label>
                    <select name="user_id" required>
                        <option value="">-- Pilih User --</option>
                        @foreach ($finansialData as $data)
                            @foreach ($data['details'] as $detail)
                                <option value="{{ $detail->user_id }}">{{ $detail->user->name ?? '-' }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="finance-field">
                    <label>Kegiatan</label>
                    <input type="text" name="kegiatan" required>
                </div>
                <div class="finance-field">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" required></textarea>
                </div>
                <div class="finance-field">
                    <label>Jumlah Anggaran (Rp)</label>
                    <input type="number" name="jumlah_anggaran" required min="0" step="100">
                </div>
                <div class="finance-field">
                    <label>Bukti Foto</label>
                    <input type="file" name="bukti_foto" accept="image/*">
                </div>
                <div class="finance-form-actions">
                    <button type="button" onclick="closeDetailModal()" class="finance-btn finance-btn--secondary">Batal</button>
                    <button type="submit" class="finance-btn finance-btn--primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="periodeModal" class="modal">
        <div class="modal-content">
            <div class="finance-modal-header">
                <h3>Tambah Periode Laporan</h3>
                <button type="button" class="finance-modal-close" onclick="closeAddPeriodModal()">&times;</button>
            </div>
            <form action="/periode-laporan" method="POST">
                @csrf
                <div class="finance-field">
                    <label>Tahun</label>
                    <select name="tahun_id" required>
                        <option value="">-- Pilih Tahun --</option>
                        @foreach ($tahun as $t)
                            <option value="{{ $t->id }}">{{ $t->tahun }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="finance-field">
                    <label>Bulan</label>
                    <select name="bulan_id" required>
                        <option value="">-- Pilih Bulan --</option>
                        @foreach ($bulan as $b)
                            <option value="{{ $b->id }}">{{ $b->bulan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="finance-field">
                    <label>Divisi</label>
                    <select name="divisi_id" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach ($divisi as $d)
                            <option value="{{ $d->id }}">{{ $d->nama_divisi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="finance-form-actions">
                    <button type="button" onclick="closeAddPeriodModal()" class="finance-btn finance-btn--secondary">Batal</button>
                    <button type="submit" class="finance-btn finance-btn--primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function initDivisiPaginations() {
            document.querySelectorAll('.finance-divisi-section[data-page-size]').forEach(section => {
                const pageSize = Math.max(1, parseInt(section.dataset.pageSize || '5', 10));
                const rows = Array.from(section.querySelectorAll('tbody tr'));
                const pagination = section.querySelector('.finance-pagination');
                const infoEl = section.querySelector('.finance-pagination__info');
                const prevBtn = section.querySelector('button[data-action="prev"]');
                const nextBtn = section.querySelector('button[data-action="next"]');

                if (!pagination || !infoEl || !prevBtn || !nextBtn) {
                    return;
                }

                const totalPages = Math.max(1, Math.ceil(rows.length / pageSize));
                let currentPage = 1;

                function render() {
                    const start = (currentPage - 1) * pageSize;
                    const end = start + pageSize;

                    rows.forEach((row, idx) => {
                        row.style.display = (idx >= start && idx < end) ? '' : 'none';
                    });

                    infoEl.textContent = `Page ${currentPage} of ${totalPages}`;
                    prevBtn.disabled = currentPage <= 1;
                    nextBtn.disabled = currentPage >= totalPages;

                    pagination.style.display = totalPages <= 1 ? 'none' : '';
                }

                prevBtn.addEventListener('click', () => {
                    if (currentPage > 1) {
                        currentPage -= 1;
                        render();
                    }
                });

                nextBtn.addEventListener('click', () => {
                    if (currentPage < totalPages) {
                        currentPage += 1;
                        render();
                    }
                });

                render();
            });
        }

        window.addEventListener('DOMContentLoaded', initDivisiPaginations);

        function openBudgetModal(periodeId) {
            const row = document.querySelector(`tr.periode-row[data-periode-id="${periodeId}"]`);
            const periodeDisplay = row?.querySelector('td strong')?.textContent || 'Unknown';

            document.getElementById('budget_periode_id').value = periodeId;
            document.getElementById('budget_periode_display').value = periodeDisplay;
            document.getElementById('budgetModal').style.display = 'block';
        }

        function closeBudgetModal() {
            document.getElementById('budgetModal').style.display = 'none';
        }

        function openDetailModal(periodeId) {
            const row = document.querySelector(`tr.periode-row[data-periode-id="${periodeId}"]`);
            const periodeDisplay = row?.querySelector('td strong')?.textContent || 'Unknown';

            document.getElementById('detail_periode_id').value = periodeId;
            document.getElementById('detail_periode_display').value = periodeDisplay;
            document.getElementById('detailModal').style.display = 'block';
        }

        function closeDetailModal() {
            document.getElementById('detailModal').style.display = 'none';
        }

        function openAddPeriodModal() {
            document.getElementById('periodeModal').style.display = 'block';
        }

        function closeAddPeriodModal() {
            document.getElementById('periodeModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const budgetModal = document.getElementById('budgetModal');
            const detailModal = document.getElementById('detailModal');
            const periodeModal = document.getElementById('periodeModal');

            if (event.target === budgetModal) {
                closeBudgetModal();
            }
            if (event.target === detailModal) {
                closeDetailModal();
            }
            if (event.target === periodeModal) {
                closeAddPeriodModal();
            }
        };
    </script>
</div>
@endsection
