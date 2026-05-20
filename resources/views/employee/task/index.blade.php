@extends('layout.employee_sidebar')

@section('title', 'Task Management - MUMS')

@section('content')
@vite(['resources/css/dashboard.css'])

<style>
    table {
        border-collapse: collapse;
        width: 100%;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
    }
    th, td {
        border: 1px solid #e2e8f0;
        padding: 10px;
    }
    thead th {
        background: #f8fafc;
    }

    .task-stats {
        grid-template-columns: repeat(5, 1fr);
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
    }

    .modal-content {
        background: #fff;
        padding: 20px;
        width: 420px;
        margin: 100px auto;
        border-radius: 12px;
    }

    @media (max-width: 1024px) {
        .task-stats { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .task-stats { grid-template-columns: 1fr; }
        .modal-content { width: calc(100% - 32px); margin: 64px auto; }
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Task Management</h1>
        <p>Daftar task yang harus kamu kerjakan.</p>
    </div>

    @if (session('success'))
        <div style="margin: 10px 0; padding: 10px 12px; background: #dcfce7; border: 1px solid #86efac; border-radius: 12px; color: #166534;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div style="margin: 10px 0; padding: 10px 12px; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 12px; color: #991b1b;">
            {{ session('error') }}
        </div>
    @endif

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
                            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                @if($statusKey === 'todo' || $statusKey === 'rejected')
                                    <form method="POST" action="{{ route('tasks.start', $task->id) }}">
                                        @csrf
                                        <button type="submit">Kerjakan</button>
                                    </form>
                                @endif

                                @if($statusKey === 'on_progress')
                                    <button
                                        type="button"
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
                        <td colspan="5" style="text-align:center; padding:14px; color:#64748b;">Tidak ada task di status {{ $statusLabel }}.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach

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
            const modal = document.getElementById('submitModal');
            if (event.target === modal) {
                closeSubmitModal();
            }
        });
    </script>
</div>

@endsection
