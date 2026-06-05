@extends('layout.employee_sidebar')

@section('title', 'Task Management - MUMS')

@section('content')
    @vite(['resources/css/dashboard.css', 'resources/css/task/index.css'])

    <div class="dashboard-container task-container">
        <div class="dashboard-header task-header">
            <div class="task-title">
                <h1>Task Management</h1>
                <p>Daftar task yang harus kamu kerjakan.</p>
            </div>

            @if(($currentRole ?? '') === 'admin' || ($currentRole ?? '') === 'spv')
                <div class="add-task-wrapper">
                    <button type="button" class="btn-add-task btn-dark" onclick="openAddModal()">Tambah Task</button>
                </div>
            @endif
        </div>

        <div class="stats-row task-stats-row">
            <div class="stat-card task-stat-card">
                <span class="stat-title task-stat-title">Todo</span>
                <span class="stat-value task-stat-value">{{ $statusCounts['todo'] ?? 0 }}</span>
            </div>
            <div class="stat-card task-stat-card">
                <span class="stat-title task-stat-title">On Progress</span>
                <span class="stat-value task-stat-value">{{ $statusCounts['on_progress'] ?? 0 }}</span>
            </div>
            <div class="stat-card task-stat-card">
                <span class="stat-title task-stat-title">Submitted</span>
                <span class="stat-value task-stat-value">{{ $statusCounts['submitted'] ?? 0 }}</span>
            </div>
            <div class="stat-card task-stat-card">
                <span class="stat-title task-stat-title">Accepted</span>
                <span class="stat-value task-stat-value">{{ $statusCounts['accepted'] ?? 0 }}</span>
            </div>
            <div class="stat-card task-stat-card">
                <span class="stat-title task-stat-title">Rejected</span>
                <span class="stat-value task-stat-value">{{ $statusCounts['rejected'] ?? 0 }}</span>
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
                            <td>{{ $task->assignedFrom->name ?? '-' }}</td>
                            <td>{{ $task->latestDetail?->notes ?? '-' }}</td>
                            <td>
                                <div class="action-cell">
                                    @if($statusKey === 'todo' || $statusKey === 'rejected')
                                        <form method="POST" action="{{ route('tasks.start', $task->id) }}" style="margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn-light">Kerjakan</button>
                                        </form>
                                    @endif

                                    @if($statusKey === 'on_progress')
                                        <button
                                            type="button"
                                            class="btn-light"
                                            data-action="{{ route('tasks.submit', $task->id) }}"
                                            data-title='@json($task->title)'
                                            onclick="openSubmitModal(this)"
                                        >
                                            Submit
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">Tidak ada task di status {{ $statusLabel }}.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endforeach

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
                        <button type="button" class="btn-light" onclick="closeSubmitModal()">Cancel</button>
                    </div>
                </form>
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

        @if(session('error'))
            <div id="errorModal" class="modal modal-show">
                <div class="modal-content modal-center modal-sm">
                    <div class="warning-icon text-error">
                        <svg class="icon-success-modal" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <h3 class="delete-title">Oops, Terjadi Kesalahan!</h3>
                    <p class="delete-subtitle mb-0">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <script>
            function openSubmitModal(buttonEl) {
                const action = buttonEl?.dataset?.action;
                const title = buttonEl?.dataset?.title ? JSON.parse(buttonEl.dataset.title) : '';

                if (!action) {
                    alert('Submit tidak bisa dijalankan: URL action tidak ditemukan.');
                    return;
                }

                document.getElementById('submitModal').style.display = 'block';
                document.getElementById('submit_task_title').value = title || '';
                document.getElementById('submit_notes').value = '';
                document.getElementById('submitForm').action = action;
            }

            function closeSubmitModal() {
                document.getElementById('submitModal').style.display = 'none';
            }

            window.addEventListener('click', function (event) {
                const submitModal = document.getElementById('submitModal');
                if (event.target === submitModal) {
                    closeSubmitModal();
                }
            });

            document.addEventListener("DOMContentLoaded", function() {
                setTimeout(() => {
                    let s = document.getElementById('successModal');
                    let e = document.getElementById('errorModal');
                    if(s) s.style.display = 'none';
                    if(e) e.style.display = 'none';
                }, 1500);
            });
        </script>
    </div>
@endsection
