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
                                            <svg viewBox="0 0 24 24">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </button>

                                        <button onclick="openDeleteModal('{{ route('tasks.destroy', $task->id) }}')" class="btn-icon">
                                            <svg viewBox="0 0 24 24">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(($currentRole ?? '') === 'employee' && $task->assigned_to === auth()->id() && in_array($task->latestDetail?->status, ['todo', 'on_progress', 'rejected'], true))
                                        <button type="button" class="btn-icon" onclick='openSubmitModal({{ $task->id }}, @json($task->title))'>
                                            <svg viewBox="0 0 24 24">
                                                <path d="M20 13V5.749C20.0001 5.67006 19.9845 5.59189 19.9543 5.51896C19.9241 5.44603 19.8798 5.37978 19.824 5.324L16.676 2.176C16.5636 2.06345 16.4111 2.00014 16.252 2H4.6C4.44087 2 4.28826 2.06321 4.17574 2.17574C4.06321 2.28826 4 2.44087 4 2.6V21.4C4 21.5591 4.06321 21.7117 4.17574 21.8243C4.28826 21.9368 4.44087 22 4.6 22H14"/>
                                                <path d="M16 2V5.4C16 5.55913 16.0632 5.71174 16.1757 5.82426C16.2883 5.93679 16.4409 6 16.6 6H20M16 19H22M19 22L22 19L19 16"/>
                                            </svg>
                                        </button>
                                    @endif

                                    @if(($currentRole ?? '') === 'admin' || ($currentRole ?? '') === 'spv')
                                        @if($task->latestDetail?->status === 'submitted')
                                            <button type="button" class="btn-icon" onclick='openReviewModal({{ $task->id }}, @json($task->title))'>
                                                <svg viewBox="0 0 24 24" class="icon-fill">
                                                    <g clip-path="url(#clip_review)">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M18 6.85504C18.0164 6.73635 18.004 6.61547 17.964 6.50254L17.9445 6.40804C17.8893 6.1782 17.7982 5.95851 17.6745 5.75704C17.5365 5.53204 17.3415 5.33704 16.9515 4.94704L13.0665 1.06054C12.678 0.670537 12.483 0.475537 12.2565 0.336037C12.0278 0.195959 11.7758 0.0979164 11.5125 0.046537C11.3996 0.00655472 11.2787 -0.00578976 11.16 0.010537C10.9935 0.00603701 10.791 0.00603701 10.5225 0.00603701H7.20752C4.68752 0.00603701 3.42752 0.006037 2.46752 0.496537C1.62283 0.929494 0.935481 1.61685 0.502524 2.46154C0.012024 3.42454 0.0120239 4.68004 0.0120239 7.20004V16.8C0.0120239 19.32 0.012024 20.58 0.502524 21.54C0.935481 22.3847 1.62283 23.0721 2.46752 23.505C3.43052 23.9955 4.68752 23.9955 7.20752 23.9955H10.842C11.4435 23.9955 11.745 23.211 11.3625 22.7475C11.3002 22.67 11.2214 22.6072 11.132 22.5636C11.0425 22.52 10.9445 22.4968 10.845 22.4955H7.20002C5.91452 22.4955 5.04002 22.494 4.36502 22.4385C3.70802 22.3845 3.37052 22.2885 3.13652 22.1685C2.57206 21.8809 2.11314 21.422 1.82552 20.8575C1.70552 20.6235 1.60802 20.2875 1.55552 19.629C1.50152 18.954 1.50002 18.084 1.50002 16.794V7.19404C1.50002 5.90854 1.50002 5.03404 1.55552 4.35904C1.60952 3.70204 1.70702 3.36454 1.82552 3.13054C2.11352 2.56654 2.57252 2.10754 3.13652 1.81954C3.37052 1.69954 3.70802 1.60204 4.36652 1.54954C5.04002 1.49554 5.91002 1.49554 7.20002 1.49554H10.5V6.74554C10.5 6.94445 10.579 7.13521 10.7197 7.27587C10.8603 7.41652 11.0511 7.49554 11.25 7.49554H16.5V8.31604C16.5 8.69104 16.8105 8.99104 17.184 9.02554C17.6115 9.06304 17.9985 8.74354 17.9985 8.31454V7.48504C17.9985 7.21804 17.9985 7.01554 17.991 6.84754L18 6.85504ZM12 2.11504L15.885 6.00004H12V2.11504Z"/>
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16.5 22.5C17.751 22.5 18.915 22.1175 19.875 21.4635L22.08 23.6685C22.2909 23.8794 22.5768 23.9978 22.875 23.9978C23.1732 23.9978 23.4592 23.8794 23.67 23.6685C23.8809 23.4577 23.9993 23.1717 23.9993 22.8735C23.9993 22.5754 23.8809 22.2894 23.67 22.0785L21.465 19.8735C22.119 18.912 22.5015 17.7585 22.5015 16.4985C22.5015 13.1835 19.8165 10.4985 16.5015 10.4985C13.1865 10.4985 10.5015 13.1835 10.5015 16.4985C10.5015 19.8135 13.1865 22.4985 16.5015 22.4985L16.5 22.5ZM16.5 21C18.99 21 21 18.99 21 16.5C21 14.01 18.99 12 16.5 12C14.01 12 12 14.01 12 16.5C12 18.99 14.01 21 16.5 21Z"/>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip_review">
                                                            <rect width="24" height="24"/>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </button>
                                        @endif
                                    @endif
                                </div>
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

    <div id="addModal" class="modal">
        <div class="modal-content">
            <h3>Tambah Task</h3>
            <form id="addForm" action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <label>Title</label>
                <input type="text" name="title" id="add_title" required class="form-control">
                <label>Description</label>
                <textarea name="description" id="add_description" class="form-control" rows="3"></textarea>
                <label>Assigned To</label>
                <select name="assigned_to" id="add_assigned_to" required class="form-control">
                    <option value="">-- Pilih User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <label>Assigned From</label>
                <input type="text" value="{{ auth()->user()->name ?? '-' }}" readonly class="form-control">
                <div class="form-actions">
                    <button type="submit" class="btn-dark">Simpan</button>
                    <button type="button" class="btn-light" onclick="closeAddModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <h3>Edit Task</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <label>Title</label>
                <input type="text" name="title" id="edit_title" required class="form-control">
                <label>Description</label>
                <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                <label>Status</label>
                <select name="status" id="edit_status" class="form-control">
                    <option value="todo">Todo</option>
                    <option value="on_progress">On Progress</option>
                    <option value="submitted">Submitted</option>
                    <option value="accepted">Accepted</option>
                    <option value="rejected">Rejected</option>
                </select>
                <label>Notes</label>
                <textarea name="notes" id="edit_notes" class="form-control" rows="2"></textarea>
                <div class="form-actions">
                    <button type="submit" class="btn-dark">Update</button>
                    <button type="button" class="btn-light" onclick="closeEditModal()">Batal</button>
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
                <input type="text" id="submit_task_title" disabled class="form-control">
                <label>Notes</label>
                <textarea name="notes" id="submit_notes" placeholder="Opsional, isi jika ada catatan pengerjaan" class="form-control" rows="3"></textarea>
                <div class="form-actions">
                    <button type="submit" class="btn-dark">Submit</button>
                    <button type="button" class="btn-light" onclick="closeSubmitModal()">Batal</button>
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
                <input type="text" id="review_task_title" disabled class="form-control">
                <label>Status Review</label>
                <select name="review_status" id="review_status" required class="form-control">
                    <option value="accepted">Accepted</option>
                    <option value="rejected">Rejected</option>
                </select>
                <label>Notes</label>
                <textarea name="notes" id="review_notes" placeholder="Isi catatan jika task dikembalikan" class="form-control" rows="3"></textarea>
                <div class="form-actions">
                    <button type="submit" class="btn-dark">Save Review</button>
                    <button type="button" class="btn-light" onclick="closeReviewModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content modal-center modal-sm">
            <div class="warning-icon">
                <svg class="icon-delete-modal" viewBox="0 0 24 24">
                    <path d="M12 2L1 21H23L12 2ZM13 18H11V16H13V18ZM13 14H11V10H13V14Z"/>
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
    <div id="successModal" class="modal modal-show">
        <div class="modal-content modal-center modal-sm">
            <div class="warning-icon color-success">
                <svg class="icon-success-modal" viewBox="0 0 24 24">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <h3 class="delete-title">Berhasil!</h3>
            <p class="delete-subtitle mb-0">{{ session('success') }}</p>
        </div>
    </div>
    @endif

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

        function openDeleteModal(actionUrl) {
            document.getElementById('deleteModal').style.display = 'block';
            document.getElementById('deleteForm').action = actionUrl;
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('addModal')) closeAddModal();
            if (event.target == document.getElementById('editModal')) closeEditModal();
            if (event.target == document.getElementById('submitModal')) closeSubmitModal();
            if (event.target == document.getElementById('reviewModal')) closeReviewModal();
            if (event.target == document.getElementById('deleteModal')) closeDeleteModal();
        };

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
