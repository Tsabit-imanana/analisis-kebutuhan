<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            width: 400px;
            margin: 100px auto;
        }
    </style>
</head>
<body>

<h1>Task List</h1>

<a href="/" style="display:inline-block; margin-bottom:12px; padding:8px 12px; background:#ddd; color:#000; text-decoration:none; border-radius:4px;">Back</a>
<button type="button" onclick="openAddModal()" style="margin-left:8px;">Tambah Task</button>

<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Assigned To</th>
            <th>Assigned From</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($tasks as $task)
        <tr>
            <td>{{ $task->title }}</td>
            <td>{{ $task->description }}</td>
            <td>{{ $task->assignedTo->name ?? '-' }}</td>
            <td>{{ $task->assignedFrom->name ?? '-' }}</td>
            <td>{{ $task->details->first()?->status ?? '-' }}</td>

            <td>
                <button onclick='openEditModal(
                    {{ $task->id }},
                    @json($task->title),
                    @json($task->description),
                    @json($task->details->first()?->status)
                )'>
                    Edit
                </button>

                <button onclick="confirmDelete({{ $task->id }})">
                    Delete
                </button>

                <form id="delete-form-{{ $task->id }}"
                      action="{{ route('tasks.destroy', $task->id) }}"
                      method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- MODAL -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h3>Edit Task</h3>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <label>Title</label><br>
            <input type="text" name="title" id="edit_title"><br><br>

            <label>Description</label><br>
            <textarea name="description" id="edit_description"></textarea><br><br>

            <label>Status</label><br>
            <select name="status" id="edit_status">
                <option value="pending">Pending</option>
                <option value="on_progress">On Progress</option>
                <option value="submitted">Submitted</option>
                <option value="accepted">Accepted</option>
            </select><br><br>

            <button type="submit">Update</button>
            <button type="button" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>
</div>

<!-- ADD TASK MODAL -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <h3>Tambah Task</h3>

        <form id="addForm" action="{{ route('tasks.store') }}" method="POST">
            @csrf

            <label>Title</label><br>
            <input type="text" name="title" id="add_title" required><br><br>

            <label>Description</label><br>
            <textarea name="description" id="add_description"></textarea><br><br>

            <label>Assigned To</label><br>
            <select name="assigned_to" id="add_assigned_to" required>
                <option value="">-- Pilih User --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select><br><br>

            <label>Assigned From</label><br>
            <select name="assigned_from" id="add_assigned_from" required>
                <option value="">-- Pilih Pengirim --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @if(auth()->check() && auth()->user()->id === $user->id) selected @endif>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select><br><br>

            <button type="submit">Simpan</button>
            <button type="button" onclick="closeAddModal()">Batal</button>
        </form>
    </div>
</div>

<h2>Task Status History</h2>

<table>
    <thead>
        <tr>
            <th>Task</th>
            <th>Status</th>
            <th>Updated At</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
            @foreach ($task->details as $detail)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $detail->status }}</td>
                    <td>{{ $detail->created_at }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

<script>
function openAddModal() {
    document.getElementById('addModal').style.display = 'block';
    document.getElementById('add_title').value = '';
    document.getElementById('add_description').value = '';
    document.getElementById('add_assigned_to').value = '';
    document.getElementById('add_assigned_from').value = '';
}

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
}

function openEditModal(id, title, description, status) {
    document.getElementById('editModal').style.display = 'block';

    document.getElementById('edit_title').value = title || '';
    document.getElementById('edit_description').value = description || '';

    // fallback kalau status null
    if (!status) {
        status = 'pending';
    }

    document.getElementById('edit_status').value = status;

    // gunakan route dari Laravel
    document.getElementById('editForm').action = "{{ url('tasks') }}/" + id;
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus data ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>

</body>
</html>