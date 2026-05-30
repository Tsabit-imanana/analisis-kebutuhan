@extends('layout.sidebar')

@section('title', 'Task Management - MUMS')

@section('content')
@vite(['resources/css/task/index.css'])

<div class="task-container">
    <div class="task-header">
        <div class="task-title">
            <h1>Task Management</h1>
            <p>Ringkasan jumlah task berdasarkan status terbaru.</p>
        </div>

        @if(($currentRole ?? '') === 'admin' || ($currentRole ?? '') === 'spv')
            <div class="add-task-wrapper">
                <button type="button" class="btn-add-task" onclick="openAddModal()">Tambah Task</button>
            </div>
        @endif
    </div>

    <div class="task-stats-row">
        <div class="task-stat-card">
            <span class="task-stat-title">Todo</span>
            <span class="task-stat-value">{{ $statusCounts['todo'] ?? 0 }}</span>
        </div>
        <div class="task-stat-card">
            <span class="task-stat-title">On Progress</span>
            <span class="task-stat-value">{{ $statusCounts['on_progress'] ?? 0 }}</span>
        </div>
        <div class="task-stat-card">
            <span class="task-stat-title">Submitted</span>
            <span class="task-stat-value">{{ $statusCounts['submitted'] ?? 0 }}</span>
        </div>
        <div class="task-stat-card">
            <span class="task-stat-title">Accepted</span>
            <span class="task-stat-value">{{ $statusCounts['accepted'] ?? 0 }}</span>
        </div>
        <div class="task-stat-card">
            <span class="task-stat-title">Rejected</span>
            <span class="task-stat-value">{{ $statusCounts['rejected'] ?? 0 }}</span>
        </div>
    </div>

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

        <h2 class="status-title">{{ $statusLabel }}</h2>

        <table class="task-table">
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
                            <div class="action-cell">
                                @if(($currentRole ?? '') === 'admin' || ($currentRole ?? '') === 'spv')
                                    <button onclick='openEditModal(
                                        {{ $task->id }},
                                        @json($task->title),
                                        @json($task->description),
                                        @json($task->latestDetail?->status)
                                    )' class="btn-icon">
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

                                    <button onclick="confirmDelete({{ $task->id }})" class="btn-icon">
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
                                @endif

                                @if(($currentRole ?? '') === 'employee' && $task->assigned_to === auth()->id() && in_array($task->latestDetail?->status, ['todo', 'on_progress', 'rejected'], true))
                                    <button type="button" class="btn-submit" onclick='openSubmitModal({{ $task->id }}, @json($task->title))'>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M20 13V5.749C20.0001 5.67006 19.9845 5.59189 19.9543 5.51896C19.9241 5.44603 19.8798 5.37978 19.824 5.324L16.676 2.176C16.5636 2.06345 16.4111 2.00014 16.252 2H4.6C4.44087 2 4.28826 2.06321 4.17574 2.17574C4.06321 2.28826 4 2.44087 4 2.6V21.4C4 21.5591 4.06321 21.7117 4.17574 21.8243C4.28826 21.9368 4.44087 22 4.6 22H14" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M16 2V5.4C16 5.55913 16.0632 5.71174 16.1757 5.82426C16.2883 5.93679 16.4409 6 16.6 6H20M16 19H22M19 22L22 19L19 16" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                @endif

                                @if(($currentRole ?? '') === 'admin' || ($currentRole ?? '') === 'spv')
                                    @if($task->latestDetail?->status === 'submitted')
                                        <button type="button" class="btn-review" onclick='openReviewModal({{ $task->id }}, @json($task->title))'>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <g clip-path="url(#clip0_366_17)">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M18 6.85491C18.0163 6.73623 18.0039 6.61535 17.964 6.50241L17.9445 6.40791C17.8892 6.17808 17.7981 5.95838 17.6745 5.75692C17.5365 5.53192 17.3415 5.33692 16.9515 4.94692L13.0665 1.06041C12.678 0.670415 12.483 0.475415 12.2565 0.335915C12.0277 0.195837 11.7757 0.0977943 11.5125 0.0464149C11.3995 0.00643265 11.2787 -0.00591183 11.16 0.0104149C10.9935 0.00591493 10.791 0.00591494 10.5225 0.00591494H7.20746C4.68746 0.00591494 3.42746 0.00591493 2.46746 0.496415C1.62277 0.929372 0.93542 1.61672 0.502463 2.46142C0.0119629 3.42442 0.0119629 4.67992 0.0119629 7.19991V16.7999C0.0119629 19.3199 0.0119629 20.5799 0.502463 21.5399C0.93542 22.3846 1.62277 23.072 2.46746 23.5049C3.43046 23.9954 4.68746 23.9954 7.20746 23.9954H10.842C11.4435 23.9954 11.745 23.2109 11.3625 22.7474C11.3001 22.6699 11.2214 22.6071 11.1319 22.5635C11.0425 22.5199 10.9445 22.4967 10.845 22.4954H7.19996C5.91446 22.4954 5.03996 22.4939 4.36496 22.4384C3.70796 22.3844 3.37046 22.2884 3.13646 22.1684C2.572 21.8808 2.11308 21.4219 1.82546 20.8574C1.70546 20.6234 1.60796 20.2874 1.55546 19.6289C1.50146 18.9539 1.49996 18.0839 1.49996 16.7939V7.19391C1.49996 5.90841 1.49996 5.03391 1.55546 4.35891C1.60946 3.70191 1.70696 3.36441 1.82546 3.13041C2.11346 2.56641 2.57246 2.10742 3.13646 1.81942C3.37046 1.69942 3.70796 1.60191 4.36646 1.54941C5.03996 1.49541 5.90996 1.49541 7.19996 1.49541H10.5V6.74541C10.5 6.94433 10.579 7.13509 10.7196 7.27574C10.8603 7.4164 11.0511 7.49541 11.25 7.49541H16.5V8.31591C16.5 8.69091 16.8105 8.99091 17.184 9.02541C17.6115 9.06291 17.9985 8.74341 17.9985 8.31441V7.48491C17.9985 7.21791 17.9985 7.01541 17.991 6.84741L18 6.85491ZM12 2.11491L15.885 5.99992H12V2.11491Z" fill="black"/>
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.5 22.5C17.751 22.5 18.915 22.1175 19.875 21.4635L22.08 23.6685C22.2908 23.8794 22.5768 23.9978 22.875 23.9978C23.1731 23.9978 23.4591 23.8794 23.67 23.6685C23.8808 23.4577 23.9993 23.1717 23.9993 22.8735C23.9993 22.5754 23.8808 22.2894 23.67 22.0785L21.465 19.8735C22.119 18.912 22.5015 17.7585 22.5015 16.4985C22.5015 13.1835 19.8165 10.4985 16.5015 10.4985C13.1865 10.4985 10.5015 13.1835 10.5015 16.4985C10.5015 19.8135 13.1865 22.4985 16.5015 22.4985L16.5 22.5ZM16.5 21C18.99 21 21 18.99 21 16.5C21 14.01 18.99 12 16.5 12C14.01 12 12 14.01 12 16.5C12 18.99 14.01 21 16.5 21Z" fill="black"/>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_366_17">
                                                    <rect width="24" height="24" fill="white"/>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </button>
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
                        <td colspan="6" class="empty-state">Tidak ada task di status {{ $statusLabel }}.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach

    <h2 class="status-title">Task Status History</h2>
    <table class="task-table">
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
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <h3>Edit Task</h3>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <label>Title</label>
            <input type="text" name="title" id="edit_title">

            <label>Description</label>
            <textarea name="description" id="edit_description"></textarea>

            <label>Status</label>
            <select name="status" id="edit_status">
                <option value="todo">Todo</option>
                <option value="on_progress">On Progress</option>
                <option value="submitted">Submitted</option>
                <option value="accepted">Accepted</option>
                <option value="rejected">Rejected</option>
            </select>

            <label>Notes</label>
            <textarea name="notes" id="edit_notes"></textarea>

            <div class="form-actions">
                <button type="button" class="btn-light" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn-dark">Update</button>
            </div>
        </form>
    </div>
</div>

<div id="addModal" class="modal">
    <div class="modal-content">
        <h3>Tambah Task</h3>
        <form id="addForm" action="{{ route('tasks.store') }}" method="POST">
            @csrf
            <label>Title</label>
            <input type="text" name="title" id="add_title" required>

            <label>Description</label>
            <textarea name="description" id="add_description"></textarea>

            <label>Assigned To</label>
            <select name="assigned_to" id="add_assigned_to" required>
                <option value="">-- Pilih User --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>

            <label>Assigned From</label>
            <input type="text" value="{{ auth()->user()->name ?? '-' }}" readonly>

            <div class="form-actions">
                <button type="button" class="btn-light" onclick="closeAddModal()">Batal</button>
                <button type="submit" class="btn-dark">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="submitModal" class="modal">
    <div class="modal-content">
        <h3>Submit Task</h3>
        <form id="submitForm" method="POST">
            @csrf
            <label>Task</label>
            <input type="text" id="submit_task_title" disabled>

            <label>Notes</label>
            <textarea name="notes" id="submit_notes" placeholder="Opsional, isi jika ada catatan pengerjaan"></textarea>

            <div class="form-actions">
                <button type="button" class="btn-light" onclick="closeSubmitModal()">Cancel</button>
                <button type="submit" class="btn-dark">Submit</button>
            </div>
        </form>
    </div>
</div>

<div id="reviewModal" class="modal">
    <div class="modal-content">
        <h3>Review Task</h3>
        <form id="reviewForm" method="POST">
            @csrf
            <label>Task</label>
            <input type="text" id="review_task_title" disabled>

            <label>Status Review</label>
            <select name="review_status" id="review_status" required>
                <option value="accepted">Accepted</option>
                <option value="rejected">Rejected</option>
            </select>

            <label>Notes</label>
            <textarea name="notes" id="review_notes" placeholder="Isi catatan jika task dikembalikan"></textarea>

            <div class="form-actions">
                <button type="button" class="btn-light" onclick="closeReviewModal()">Cancel</button>
                <button type="submit" class="btn-dark">Save Review</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').style.display = 'block';
    document.getElementById('add_title').value = '';
    document.getElementById('add_description').value = '';
    document.getElementById('add_assigned_to').value = '';
}
function closeAddModal() { document.getElementById('addModal').style.display = 'none'; }

function openEditModal(id, title, description, status) {
    document.getElementById('editModal').style.display = 'block';
    document.getElementById('edit_title').value = title || '';
    document.getElementById('edit_description').value = description || '';
    if (!status) { status = 'todo'; }
    document.getElementById('edit_status').value = status;
    document.getElementById('edit_notes').value = '';
    document.getElementById('editForm').action = "{{ url('tasks') }}/" + id;
}
function closeEditModal() { document.getElementById('editModal').style.display = 'none'; }

function openSubmitModal(id, title) {
    document.getElementById('submitModal').style.display = 'block';
    document.getElementById('submit_task_title').value = title || '';
    document.getElementById('submit_notes').value = '';
    document.getElementById('submitForm').action = "{{ url('tasks') }}/" + id + "/submit";
}
function closeSubmitModal() { document.getElementById('submitModal').style.display = 'none'; }

function openReviewModal(id, title) {
    document.getElementById('reviewModal').style.display = 'block';
    document.getElementById('review_task_title').value = title || '';
    document.getElementById('review_notes').value = '';
    document.getElementById('review_status').value = 'accepted';
    document.getElementById('reviewForm').action = "{{ url('tasks') }}/" + id + "/review";
}
function closeReviewModal() { document.getElementById('reviewModal').style.display = 'none'; }

function confirmDelete(id) {
    if (confirm('Yakin ingin menghapus data ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection
