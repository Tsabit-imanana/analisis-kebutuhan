<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Log</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        img {
            max-width: 150px;
            max-height: 120px;
            display: block;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 80px auto;
            padding: 20px;
            border: 1px solid #888;
            width: 400px;
            border-radius: 6px;
        }
        .modal-content input,
        .modal-content textarea,
        .modal-content select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <h1>Weekly Log</h1>

    <a href="/" style="display:inline-block; margin-bottom:16px; padding:8px 12px; background:#ddd; color:#000; text-decoration:none; border-radius:4px;">Back</a>
    <button type="button" onclick="openAddModal()" style="margin-left:8px; padding:8px 12px;">Tambah Weekly Log</button>

    @if(session('success'))
        <div style="margin:16px 0; padding:12px; background:#d4edda; color:#155724; border:1px solid #c3e6cb; border-radius:4px;">
            {{ session('success') }}
        </div>
    @endif

    <div id="addModal" class="modal">
        <div class="modal-content">
            <h3>Tambah Weekly Log</h3>
            <form action="{{ route('weekly_log.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label>Tanggal Mulai</label><br>
                <input type="date" name="s_date" required><br><br>

                <label>Tanggal Selesai</label><br>
                <input type="date" name="f_date" required><br><br>

                @auth
                    <input type="hidden" name="logged_by" value="{{ auth()->id() }}">
                    <p>Logged by: {{ auth()->user()->name }}</p>
                @else
                    <label>Logged By</label><br>
                    <select name="logged_by" required>
                        <option value="">-- Pilih User --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select><br><br>
                @endauth

                <label>Title</label><br>
                <input type="text" name="title" required><br><br>

                <label>Description</label><br>
                <textarea name="description"></textarea><br><br>

                <label>Notes</label><br>
                <textarea name="notes"></textarea><br><br>

                <label>Photo</label><br>
                <input type="file" name="photo" accept="image/*"><br><br>

                <button type="submit">Simpan</button>
                <button type="button" onclick="closeAddModal()">Batal</button>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Edit Weekly Log</h3>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <label>Tanggal Mulai</label><br>
                <input type="date" name="s_date" id="edit_s_date" required><br><br>

                <label>Tanggal Selesai</label><br>
                <input type="date" name="f_date" id="edit_f_date" required><br><br>

                <label>Logged By</label><br>
                <select name="logged_by" id="edit_logged_by" required>
                    <option value="">-- Pilih User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select><br><br>

                <label>Title</label><br>
                <input type="text" name="title" id="edit_title" required><br><br>

                <label>Description</label><br>
                <textarea name="description" id="edit_description"></textarea><br><br>

                <label>Notes</label><br>
                <textarea name="notes" id="edit_notes"></textarea><br><br>

                <label>Photo</label><br>
                <input type="file" name="photo" accept="image/*"><br><br>

                <button type="submit">Update</button>
                <button type="button" onclick="closeEditModal()">Batal</button>
            </form>
        </div>
    </div>

    @if ($weeklyLogs->isEmpty())
        <p>Tidak ada data weekly log.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Start Date</th>
                    <th>Finish Date</th>
                    <th>Logged By</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Notes</th>
                    <th>Photo</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($weeklyLogs as $log)
                    <tr>
                        <td>{{ $log->s_date }}</td>
                        <td>{{ $log->f_date }}</td>
                        <td>{{ $log->loggedBy->name ?? $log->logged_by }}</td>
                        <td>{{ $log->title }}</td>
                        <td>{{ $log->description }}</td>
                        <td>{{ $log->notes }}</td>
                        <td>
                            @if ($log->photo)
                                <img src="{{ $log->photo }}" alt="Foto Weekly Log" onerror="this.outerHTML='<span style=\'color:red;\'>Error: Foto tidak bisa dimuat</span>';" />
                            @else
                                <span>Tidak ada foto.</span>
                            @endif
                        </td>
                        <td>
                            <button type="button"
                                onclick="openEditModal(this)"
                                data-id="{{ $log->id }}"
                                data-s-date="{{ $log->s_date }}"
                                data-f-date="{{ $log->f_date }}"
                                data-logged-by="{{ $log->logged_by }}"
                                data-title="{{ htmlspecialchars($log->title, ENT_QUOTES, 'UTF-8') }}"
                                data-description="{{ htmlspecialchars($log->description, ENT_QUOTES, 'UTF-8') }}"
                                data-notes="{{ htmlspecialchars($log->notes, ENT_QUOTES, 'UTF-8') }}"
                            >Edit</button>
                            <form action="{{ route('weekly_log.destroy', $log->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus weekly log ini?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        function openEditModal(button) {
            var id = button.dataset.id;
            document.getElementById('editModal').style.display = 'block';
            document.getElementById('edit_s_date').value = button.dataset.sDate;
            document.getElementById('edit_f_date').value = button.dataset.fDate;
            document.getElementById('edit_logged_by').value = button.dataset.loggedBy;
            document.getElementById('edit_title').value = button.dataset.title || '';
            document.getElementById('edit_description').value = button.dataset.description || '';
            document.getElementById('edit_notes').value = button.dataset.notes || '';
            document.getElementById('editForm').action = '{{ url('weekly_log') }}/' + id;
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        window.onclick = function(event) {
            var addModal = document.getElementById('addModal');
            var editModal = document.getElementById('editModal');
            if (event.target == addModal) {
                closeAddModal();
            }
            if (event.target == editModal) {
                closeEditModal();
            }
        };
    </script>
</body>
</html>
