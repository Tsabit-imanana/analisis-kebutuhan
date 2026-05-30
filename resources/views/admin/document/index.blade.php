@extends('layout.sidebar')

@section('title', 'Document Management - MUMS')

@section('content')
    @vite('resources/css/document/index.css')

    <div class="docs-container">
        <div class="header">
            <div>
                <h1>Document Management</h1>
                <p class="muted">Surat jalan dan pelacakan riwayat dokumen.</p>
            </div>
            <button type="button" class="btn btn-primary" onclick="openAddModal()">
                + Ajukan Baru
            </button>
        </div>

        @if(session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif

        <div class="stats-row">
            <div class="stat-card">
                <span class="stat-title">Jumlah Dokumen</span>
                <span class="stat-value">1927</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Dokumen Pending</span>
                <span class="stat-value">29</span>
            </div>
            <div class="stat-card">
                <span class="stat-title">Jumlah Task Keseluruhan</span>
                <span class="stat-value">70</span>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Informasi Dokumen</h2>

            <div class="table-controls">
                <div class="filter-group">
                    <select class="form-control filter-select">
                        <option value="">Filter By: Semua</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                    </select>
                </div>
                <form method="GET" action="{{ route('document.index') }}" class="search-group">
                    <input type="text" name="search" class="search-input" placeholder="Search here" value="{{ request('search') }}">
                </form>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal Dibuat</th>
                            <th>Tags</th>
                            <th>Nomor Surat</th>
                            <th>Tujuan Surat</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1001</td>
                            <td>12/04/2026</td>
                            <td><span class="badge-tag">e.9 tag</span></td>
                            <td>001/A/HR/MUMS/12/2026</td>
                            <td>PT. PLN IDN</td>
                            <td>Contoh keterangan surat...</td>
                            <td><span class="badge-status badge-pending">Pending</span></td>
                            <td>
                                <div class="actions">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="openViewModal(this)"
                                        data-id="1001"
                                        data-tanggal="2026-04-12"
                                        data-tags="e.9 tag"
                                        data-nomorsurat="001/A/HR/MUMS/12/2026"
                                        data-tujuansurat="PT. PLN IDN"
                                        data-keterangan="Contoh keterangan surat..."
                                        data-status="Pending">
                                        View
                                    </button>

                                    <button type="button" class="btn btn-secondary"
                                        onclick="openEditModal(this)"
                                        data-id="1001"
                                        data-tanggal="2026-04-12"
                                        data-tags="e.9 tag"
                                        data-nomorsurat="001/A/HR/MUMS/12/2026"
                                        data-tujuansurat="PT. PLN IDN"
                                        data-keterangan="Contoh keterangan surat..."
                                        data-note="Format nomor surat salah, tolong perbaiki.">
                                        Edit
                                    </button>

                                    <form method="POST" action="{{ route('document.destroy', 1001) }}" class="inline-form" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="addModal" class="modal">
        <div class="modal-content modal-md">
            <div class="modal-header-text">
                <h2>Dokumen Baru</h2>
                <p class="muted">Ajukan dokumen baru.</p>
            </div>

            <form method="POST" action="{{ route('document.store') }}">
                @csrf
                <div class="field">
                    <label>ID</label>
                    <input type="text" placeholder="Auto-generated" class="readonly-input" disabled>
                </div>
                <div class="field">
                    <label>Tanggal Dibuat</label>
                    <input type="date" name="tanggal_dibuat" class="form-input" required>
                </div>
                <div class="field">
                    <label>Tags</label>
                    <input type="text" name="tags" class="form-input" placeholder="Pilih label dokumen" required>
                </div>
                <div class="field">
                    <label>Nomor Surat</label>
                    <input type="text" name="nomor_surat" class="form-input" placeholder="Contoh: 001/A/HR/MUMS/12/2026" required>
                </div>
                <div class="field">
                    <label>Tujuan Surat</label>
                    <input type="text" name="tujuan_surat" class="form-input" placeholder="Contoh: PT. PLN IDN" required>
                </div>
                <div class="field">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-input" rows="4" placeholder="Isi keterangan di sini..."></textarea>
                </div>

                <div class="form-actions-left">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Batalkan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content modal-md">
            <div class="modal-header-text">
                <h2>Update Dokumen</h2>
                <p class="muted">Perbarui/rubah informasi dokumen Anda.</p>
                <p id="spvNoteArea" class="spv-note" style="display: none;">
                    Note SPV: <span id="spvNoteText"></span>
                </p>
            </div>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="field">
                    <label>ID</label>
                    <input type="text" id="edit_id" class="readonly-input" readonly>
                </div>
                <div class="field">
                    <label>Tanggal Dibuat</label>
                    <input type="date" name="tanggal_dibuat" id="edit_tanggal_dibuat" class="form-input" required>
                </div>
                <div class="field">
                    <label>Tags</label>
                    <input type="text" name="tags" id="edit_tags" class="form-input" required>
                </div>
                <div class="field">
                    <label>Nomor Surat</label>
                    <input type="text" name="nomor_surat" id="edit_nomor_surat" class="form-input" required>
                </div>
                <div class="field">
                    <label>Tujuan Surat</label>
                    <input type="text" name="tujuan_surat" id="edit_tujuan_surat" class="form-input" required>
                </div>
                <div class="field">
                    <label>Keterangan</label>
                    <textarea name="keterangan" id="edit_keterangan" class="form-input" rows="4"></textarea>
                </div>

                <div class="form-actions-left">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batalkan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="viewModal" class="modal">
        <div class="modal-content modal-md">
            <div class="modal-header-text">
                <h2>Detail Dokumen</h2>
                <p class="muted">Informasi lengkap dokumen.</p>
            </div>

            <div class="field">
                <label>ID</label>
                <input type="text" id="view_id" class="readonly-input" readonly>
            </div>
            <div class="field">
                <label>Tanggal Dibuat</label>
                <input type="date" id="view_tanggal_dibuat" class="readonly-input" readonly>
            </div>
            <div class="field">
                <label>Tags</label>
                <input type="text" id="view_tags" class="readonly-input" readonly>
            </div>
            <div class="field">
                <label>Nomor Surat</label>
                <input type="text" id="view_nomor_surat" class="readonly-input" readonly>
            </div>
            <div class="field">
                <label>Tujuan Surat</label>
                <input type="text" id="view_tujuan_surat" class="readonly-input" readonly>
            </div>
            <div class="field">
                <label>Keterangan</label>
                <textarea id="view_keterangan" class="readonly-input" rows="4" readonly></textarea>
            </div>
            <div class="field">
                <label>Status Saat Ini</label>
                <input type="text" id="view_status" class="readonly-input" readonly>
            </div>

            <div class="form-actions-left">
                <button type="button" class="btn btn-primary" onclick="closeViewModal()">Tutup</button>
            </div>
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
            var spvNote = button.dataset.note;

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_tanggal_dibuat').value = button.dataset.tanggal;
            document.getElementById('edit_tags').value = button.dataset.tags;
            document.getElementById('edit_nomor_surat').value = button.dataset.nomorsurat;
            document.getElementById('edit_tujuan_surat').value = button.dataset.tujuansurat;
            document.getElementById('edit_keterangan').value = button.dataset.keterangan;

            var noteArea = document.getElementById('spvNoteArea');
            var noteText = document.getElementById('spvNoteText');

            if (spvNote && spvNote.trim() !== '') {
                noteArea.style.display = 'block';
                noteText.innerText = spvNote;
            } else {
                noteArea.style.display = 'none';
                noteText.innerText = '';
            }

            document.getElementById('editForm').action = "{{ url('document') }}/" + id;
        }
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function openViewModal(button) {
            document.getElementById('viewModal').style.display = 'block';

            document.getElementById('view_id').value = button.dataset.id;
            document.getElementById('view_tanggal_dibuat').value = button.dataset.tanggal;
            document.getElementById('view_tags').value = button.dataset.tags;
            document.getElementById('view_nomor_surat').value = button.dataset.nomorsurat;
            document.getElementById('view_tujuan_surat').value = button.dataset.tujuansurat;
            document.getElementById('view_keterangan').value = button.dataset.keterangan;
            document.getElementById('view_status').value = button.dataset.status;
        }
        function closeViewModal() {
            document.getElementById('viewModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('addModal')) closeAddModal();
            if (event.target == document.getElementById('editModal')) closeEditModal();
            if (event.target == document.getElementById('viewModal')) closeViewModal();
        };
    </script>
@endsection
