@extends('layout.sidebar')

@section('title', 'Weekly Log - MUMS')

@section('content')
    @vite(['resources/css/weekly_log/index.css'])

    <div class="weekly-container">
        <div class="page-header">
            <div class="header-text">
                <h1>Agenda & Weekly Log</h1>
                <p>Pencatatan aktivitas harian dan dokumentasi antar divisi.</p>
            </div>
            <button class="btn-dark" onclick="openAddModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Log Baru
            </button>
        </div>

        <div class="summary-cards">
            <div class="card">
                <span class="card-title">Jumlah Total Log</span>
                <span class="card-value">{{ $totalLogs ?? 0 }}</span>
            </div>
            <div class="card">
                <span class="card-title">Jumlah Log Belum Dikonfirmasi</span>
                <span class="card-value">{{ $pendingLogs ?? 0 }}</span>
            </div>
            <div class="card">
                <span class="card-title">Jumlah Log Sudah Dikonfirmasi</span>
                <span class="card-value">{{ $confirmedLogs ?? 0 }}</span>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h3>Weekly Log</h3>
                <div class="table-controls">
                    <label>Tampilkan</label>
                    <select class="form-select"> {{-- Backend untuk show data --}}
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <label>Data</label>
                </div>
            </div>

            @if ($weeklyLogs->isEmpty())
                <p class="empty-data">Tidak ada data weekly log.</p>
            @else
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr> {{-- Ngikut Tsabit --}}
                                <th>Start Date</th>
                                <th>Finish Date</th>
                                <th>Logged By</th>
                                <th>Divisi</th>
                                <th>Status</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Notes</th>
                                <th>Photo</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $weeklyLogsByDivisi = $weeklyLogs->groupBy(function ($log) {
                                    return $log->divisi_id ?? $log->loggedBy?->divisi_id ?? 'none';
                                });
                            @endphp

                            @foreach ($weeklyLogsByDivisi as $divisiKey => $logs)
                                @php
                                    $firstLog = $logs->first();
                                    $divisiName = '-';
                                    if ($divisiKey === 'none') {
                                        $divisiName = 'Tanpa Divisi';
                                    } else {
                                        $divisiName = $firstLog?->divisi?->nama_divisi
                                            ?? $firstLog?->loggedBy?->divisi?->nama_divisi
                                            ?? ('Divisi #' . $divisiKey);
                                    }
                                @endphp

                                <tr class="divisi-header">
                                    <td colspan="10">
                                        {{ $divisiName }}
                                        <span class="divisi-count">({{ $logs->count() }} log)</span>
                                    </td>
                                </tr>

                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{ $log->s_date }}</td>
                                        <td>{{ $log->f_date }}</td>
                                        <td>{{ $log->loggedBy->name ?? $log->logged_by }}</td>
                                        <td>{{ $log->divisi->nama_divisi ?? $log->loggedBy?->divisi?->nama_divisi ?? '-' }}</td>
                                        <td>
                                            @php
                                                $status = $log->status ?? 'pending';
                                            @endphp
                                            <span class="status-badge status-badge--{{ $status }}">
                                                {{ $status === 'confirmed' ? 'Confirmed' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td>{{ $log->title }}</td>
                                        <td>{{ $log->description }}</td>
                                        <td>{{ $log->notes }}</td>
                                        <td>
                                            @if ($log->photo)
                                                <img src="{{ $log->photo }}" alt="Foto Weekly Log" class="table-img" onerror="this.outerHTML='<span style=\'color:red;\'>Error: Foto tidak bisa dimuat</span>';" />
                                            @else
                                                <span class="text-muted">Tidak ada foto.</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="aksi-cell">
                                                <button type="button" class="btn-icon"
                                                    onclick="openEditModal(this)"
                                                    data-id="{{ $log->id }}"
                                                    data-s-date="{{ $log->s_date }}"
                                                    data-f-date="{{ $log->f_date }}"
                                                    data-logged-by="{{ $log->logged_by }}"
                                                    data-status="{{ $log->status ?? 'pending' }}"
                                                    data-title="{{ htmlspecialchars($log->title, ENT_QUOTES, 'UTF-8') }}"
                                                    data-description="{{ htmlspecialchars($log->description, ENT_QUOTES, 'UTF-8') }}"
                                                    data-notes="{{ htmlspecialchars($log->notes, ENT_QUOTES, 'UTF-8') }}"
                                                >
                                                    <svg
                                                        width="18"
                                                        height="18"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                    </svg>
                                                </button>

                                                <button type="button" class="btn-icon" onclick="openDeleteModal('{{ route('weekly_log.destroy', $log->id) }}')">
                                                    <svg
                                                        width="18"
                                                        height="18"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pagination-wrapper"> {{-- Backend Pagination --}}
                    <ul class="pagination">
                        <li><a href="#" class="disabled">&laquo;</a></li>
                        <li><a href="#" class="active">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">&raquo;</a></li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <h3>Tambah Weekly Log</h3> {{-- Add Data --}}
            <form action="{{ route('weekly_log.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label>Tanggal Mulai</label><br>
                <input type="date" name="s_date" required class="form-control"><br>

                <label>Tanggal Selesai</label><br>
                <input type="date" name="f_date" required class="form-control"><br>

                @auth
                    <input type="hidden" name="logged_by" value="{{ auth()->id() }}">
                    <p>Logged by: {{ auth()->user()->name }}</p><br>
                @else
                    <label>Logged By</label><br>
                    <select name="logged_by" required class="form-control">
                        <option value="">-- Pilih User --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select><br>
                @endauth

                <label>Divisi</label><br>
                <select name="divisi_id" class="form-control">
                    <option value="">-- Pilih Divisi --</option>
                    @foreach ($divisis as $divisi)
                        <option value="{{ $divisi->id }}">{{ $divisi->nama_divisi }}</option>
                    @endforeach
                </select><br>

                <label>Title</label><br>
                <input type="text" name="title" required class="form-control"><br>

                <label>Description</label><br>
                <textarea name="description" class="form-control"></textarea><br>

                <label>Notes</label><br>
                <textarea name="notes" class="form-control"></textarea><br>

                <label>Photo</label><br>
                <input type="file" name="photo" accept="image/*" class="form-control"><br><br> {{-- Butuh image adjustment --}}

                <div class="form-actions">
                    <button type="submit" class="btn-dark">Simpan</button>
                    <button type="button" class="btn-light" onclick="closeAddModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Edit Weekly Log</h3> {{-- Edit Data --}}
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <label>Tanggal Mulai</label><br>
                <input type="date" name="s_date" id="edit_s_date" required class="form-control"><br>

                <label>Tanggal Selesai</label><br>
                <input type="date" name="f_date" id="edit_f_date" required class="form-control"><br>

                <label>Logged By</label><br>
                <select name="logged_by" id="edit_logged_by" required class="form-control">
                    <option value="">-- Pilih User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select><br>

                <label>Status</label><br>
                <select name="status" id="edit_status" class="form-control">
                    <option value="pending">Pending (Belum dikonfirmasi)</option>
                    <option value="confirmed">Confirmed (Sudah dikonfirmasi)</option>
                </select><br>

                <label>Title</label><br>
                <input type="text" name="title" id="edit_title" required class="form-control"><br>

                <label>Description</label><br>
                <textarea name="description" id="edit_description" class="form-control"></textarea><br>

                <label>Notes</label><br>
                <textarea name="notes" id="edit_notes" class="form-control"></textarea><br>

                <label>Photo (Kosongkan jika tidak diubah)</label><br>
                <input type="file" name="photo" accept="image/*" class="form-control"><br><br> {{-- Butuh image adjustment --}}

                <div class="form-actions">
                    <button type="submit" class="btn-dark">Simpan</button>
                    <button type="button" class="btn-light" onclick="closeEditModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content modal-center modal-sm">
            <div class="warning-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="black" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L1 21H23L12 2ZM13 18H11V16H13V18ZM13 14H11V10H13V14Z" fill="black"/>
                </svg>
            </div>
            <h3 class="delete-title">Apakah Anda Yakin Ingin Menghapus Data?</h3>
            <p class="delete-subtitle">Data akan dihapus secara permanen</p>

            <div class="modal-actions">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-dark btn-modal-action">Ya</button>
                </form>
                <button type="button" class="btn-dark btn-modal-action" onclick="closeDeleteModal()">Tidak</button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div id="successModal" class="modal" style="display: block;">
        <div class="modal-content modal-center modal-sm">
            <div class="warning-icon" style="color: #10b981;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            </div>
            <h3 class="delete-title">Berhasil!</h3>
            <p class="delete-subtitle" style="margin-bottom: 0;">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <script>
        // Call Tambah
        function openAddModal() { document.getElementById('addModal').style.display = 'block'; }
        function closeAddModal() { document.getElementById('addModal').style.display = 'none'; }

        // Call Edit
        function openEditModal(button) {
            var id = button.dataset.id;
            document.getElementById('editModal').style.display = 'block';
            document.getElementById('edit_s_date').value = button.dataset.sDate;
            document.getElementById('edit_f_date').value = button.dataset.fDate;
            document.getElementById('edit_logged_by').value = button.dataset.loggedBy;
            document.getElementById('edit_status').value = button.dataset.status || 'pending';
            document.getElementById('edit_title').value = button.dataset.title || '';
            document.getElementById('edit_description').value = button.dataset.description || '';
            document.getElementById('edit_notes').value = button.dataset.notes || '';
            document.getElementById('editForm').action = '{{ url("weekly_log") }}/' + id;
        }
        function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }

        // Call Delete
        function openDeleteModal(actionUrl) {
            document.getElementById('deleteModal').style.display = 'block';
            document.getElementById('deleteForm').action = actionUrl;
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        // Menutup pop up saat diluar kotak
        window.onclick = function(event) {
            if (event.target == document.getElementById('addModal')) closeAddModal();
            if (event.target == document.getElementById('editModal')) closeEditModal();
            if (event.target == document.getElementById('deleteModal')) closeDeleteModal();
        };

        // Timer pop up success
        document.addEventListener("DOMContentLoaded", function() {
            var successModal = document.getElementById('successModal');
            if (successModal) {
                setTimeout(function() {
                    successModal.style.display = 'none';
                }, 1000);
            }
        });
    </script>
@endsection
