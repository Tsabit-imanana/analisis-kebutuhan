@extends('layout.sidebar')

@section('title', 'Task Management - MUMS')

@section('content')
@vite(['resources/css/dashboard.css', 'resources/css/task/index.css'])

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Task Management</h1>
        <p>Ringkasan jumlah task berdasarkan status terbaru.</p>
    </div>

    <div class="stats-row task-stats">
        <div class="stat-card">
            <span class="stat-title">Todo</span>
            <span class="stat-value">{{ $statusCounts['todo'] ?? 0 }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">On Progress</span>
            <span class="stat-value">{{ $statusCounts['on_progress'] ?? 0 }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Submitted</span>
            <span class="stat-value">{{ $statusCounts['submitted'] ?? 0 }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Accepted</span>
            <span class="stat-value">{{ $statusCounts['accepted'] ?? 0 }}</span>
        </div>
        <div class="stat-card">
            <span class="stat-title">Rejected</span>
            <span class="stat-value">{{ $statusCounts['rejected'] ?? 0 }}</span>
        </div>
    </div>

    @if(($currentRole ?? '') === 'admin' || ($currentRole ?? '') === 'spv')
        <div style="margin: 0 0 12px 0; display:flex; gap:8px; align-items:center;">
            <button type="button" onclick="openAddModal()" style="padding:10px 14px; border-radius:10px; border:0; cursor:pointer; font-weight:700; background:#0f172a; color:#fff;">Tambah Task</button>
        </div>
    @endif

    @php
        $statusTableOrder = [
            'todo' => 'Todo',
            'on_progress' => 'On Progress',
            'submitted' => 'Submitted',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
        ];
    @endphp

    @foreach ($statusTableOrder as $statusKey => $statusLabel)
        @php
            $tasksForStatus = $tasks->filter(function ($task) use ($statusKey) {
                $latestStatus = $task->latestDetail?->status;
                if (! $latestStatus) {
                    $latestStatus = 'todo';
                }
                return $latestStatus === $statusKey;
            });
        @endphp

        <h2 style="margin:16px 0 10px 0; font-size:18px;">{{ $statusLabel }}</h2>

        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Assigned To</th>
                    <th>Assigned From</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($tasksForStatus as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->assignedTo->name ?? '-' }}</td>
                        <td>{{ $task->assignedFrom->name ?? '-' }}</td>
                        <td>{{ $task->latestDetail?->notes ?? '-' }}</td>

                        <td>
                            <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                @if(($currentRole ?? '') === 'admin' || ($currentRole ?? '') === 'spv')
                                    <button onclick='openEditModal(
                                        {{ $task->id }},
                                        @json($task->title),
                                        @json($task->description),
                                        @json($task->latestDetail?->status)
                                    )'>
                                        Edit
                                    </button>

                                    <button onclick="confirmDelete({{ $task->id }})">
                                        Delete
                                    </button>
                                @endif

                                @if(($currentRole ?? '') === 'employee' && $task->assigned_to === auth()->id() && in_array($task->latestDetail?->status, ['todo', 'on_progress', 'rejected'], true))
                                    <button type="button" onclick='openSubmitModal({{ $task->id }}, @json($task->title))'>Submit</button>
                                @endif

                                @if(($currentRole ?? '') === 'admin' || ($currentRole ?? '') === 'spv')
                                    @if($task->latestDetail?->status === 'submitted')
                                        <button type="button" onclick='openReviewModal({{ $task->id }}, @json($task->title))'>Review</button>
                                    @endif
                                @endif
                            </div>

                            @if(($currentRole ?? '') === 'admin' || ($currentRole ?? '') === 'spv')
                                <form id="delete-form-{{ $task->id }}"
                                    action="{{ route('tasks.destroy', $task->id) }}"
                                    method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:14px; color:#64748b;">Tidak ada task di status {{ $statusLabel }}.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach

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
                <option value="todo">Todo</option>
                <option value="on_progress">On Progress</option>
                <option value="submitted">Submitted</option>
                <option value="accepted">Accepted</option>
                <option value="rejected">Rejected</option>
            </select><br><br>

            <label>Notes</label><br>
            <textarea name="notes" id="edit_notes"></textarea><br><br>

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
            <input type="text" value="{{ auth()->user()->name ?? '-' }}" readonly>
            <br><br>

            <button type="submit">Simpan</button>
            <button type="button" onclick="closeAddModal()">Batal</button>
        </form>
    </div>
</div>

<!-- SUBMIT TASK MODAL -->
<div id="submitModal" class="modal">
    <div class="modal-content">
        <h3>Submit Task</h3>

        <form id="submitForm" method="POST">
            @csrf

            <label>Task</label><br>
            <input type="text" id="submit_task_title" disabled><br><br>

            <label>Notes</label><br>
            <textarea name="notes" id="submit_notes" placeholder="Opsional, isi jika ada catatan pengerjaan"></textarea><br><br>

            <button type="submit">Submit</button>
            <button type="button" onclick="closeSubmitModal()">Cancel</button>
        </form>
    </div>
</div>

<!-- REVIEW TASK MODAL -->
<div id="reviewModal" class="modal">
    <div class="modal-content">
        <h3>Review Task</h3>

        <form id="reviewForm" method="POST">
            @csrf

            <label>Task</label><br>
            <input type="text" id="review_task_title" disabled><br><br>

            <label>Status Review</label><br>
            <select name="review_status" id="review_status" required>
                <option value="accepted">Accepted</option>
                <option value="rejected">Rejected</option>
            </select><br><br>

            <label>Notes</label><br>
            <textarea name="notes" id="review_notes" placeholder="Isi catatan jika task dikembalikan"></textarea><br><br>

            <button type="submit">Save Review</button>
            <button type="button" onclick="closeReviewModal()">Cancel</button>
        </form>
    </div>
</div>

<h2>Task Status History</h2>

<table>
    <thead>
        <tr>
            <th>Task</th>
            <th>Status</th>
            <th>Notes</th>
            <th>Updated At</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tasks as $task)
            @foreach ($task->details as $detail)
                <tr>
                    <td>{{ $task->title }}</td>
                    <td>{{ $detail->status }}</td>
                    <td>{{ $detail->notes ?? '-' }}</td>
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
        status = 'todo';
    }

    document.getElementById('edit_status').value = status;
    document.getElementById('edit_notes').value = '';

    // gunakan route dari Laravel
    document.getElementById('editForm').action = "{{ url('tasks') }}/" + id;
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

function openSubmitModal(id, title) {
    document.getElementById('submitModal').style.display = 'block';
    document.getElementById('submit_task_title').value = title || '';
    document.getElementById('submit_notes').value = '';
    document.getElementById('submitForm').action = "{{ url('tasks') }}/" + id + "/submit";
}

function closeSubmitModal() {
    document.getElementById('submitModal').style.display = 'none';
}

function openReviewModal(id, title) {
    document.getElementById('reviewModal').style.display = 'block';
    document.getElementById('review_task_title').value = title || '';
    document.getElementById('review_notes').value = '';
    document.getElementById('review_status').value = 'accepted';
    document.getElementById('reviewForm').action = "{{ url('tasks') }}/" + id + "/review";
}

function closeReviewModal() {
    document.getElementById('reviewModal').style.display = 'none';
}

function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus data ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>

</div>

@endsection
