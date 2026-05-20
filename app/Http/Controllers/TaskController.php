<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\Task_details;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Task::query();

        if ($user && $user->role === 'employee') {
            $query->where('assigned_to', $user->id);
        }

        $tasks = $query->with(['details', 'latestDetail', 'assignedTo', 'assignedFrom'])->get();

        $statusCounts = [
            'todo' => 0,
            'on_progress' => 0,
            'submitted' => 0,
            'accepted' => 0,
            'rejected' => 0,
        ];

        foreach ($tasks as $task) {
            $status = $task->latestDetail?->status;
            if (! $status) {
                $status = 'todo';
            }
            if ($status && array_key_exists($status, $statusCounts)) {
                $statusCounts[$status]++;
            }
        }

        $view = 'admin.task.index';
        if ($user && $user->role === 'employee') {
            $view = 'employee.task.index';
        }

        $users = collect();
        if ($user && in_array($user->role, ['admin', 'spv'], true)) {
            $users = User::select('id', 'name')->get();
        }

        return view($view, [
            'tasks' => $tasks,
            'users' => $users,
            'currentRole' => $user?->role,
            'statusCounts' => $statusCounts,
            'totalTasks' => $tasks->count(),
        ]);
    }

    public function startTask(Request $request, $id)
    {
        $user = Auth::user();
        if (! $user || $user->role !== 'employee') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengerjakan task ini.');
        }

        $task = Task::with(['latestDetail'])->findOrFail($id);

        if ((int) $task->assigned_to !== (int) $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengerjakan task ini.');
        }

        $latestStatus = $task->latestDetail?->status ?? 'todo';
        if (! in_array($latestStatus, ['todo', 'rejected'], true)) {
            return redirect()->back()->with('error', 'Task hanya bisa dikerjakan dari status Todo atau Rejected.');
        }

        $task->details()->create([
            'status' => 'on_progress',
            'notes' => null,
        ]);

        return redirect()->back()->with('success', 'Task berhasil dipindahkan ke On Progress.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Return view add task
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addTask(StoreTaskRequest $request)
    {
        $assignedFromId = Auth::id();
        $user = Auth::user();

        if (! $assignedFromId) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (! $user || ! in_array($user->role, ['admin', 'spv'], true)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menambahkan task.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $result = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'assigned_to' => $request->assigned_to,
            'assigned_from' => $assignedFromId,
        ]);

        $taskId = $result->id;
        Task_details::create([
            'task_id' => $taskId,
            'status' => 'todo',
            'notes' => null,
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
public function editTask(Request $request, $id)
{
    $user = Auth::user();

    if (! $user || ! in_array($user->role, ['admin', 'spv'], true)) {
        return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengubah task.');
    }

    $request->validate([
    'status' => 'required|in:todo,on_progress,submitted,accepted,rejected',
    'notes' => 'nullable|string|max:1000',
]);
    $task = Task::findOrFail($id);

    // update task utama
    $task->update([
        'title' => $request->title,
        'description' => $request->description,
    ]);

    // ambil status terakhir
    $lastDetail = $task->details()
        ->orderByDesc('created_at')
        ->first();

    // cek apakah status berubah
    if (!$lastDetail || $lastDetail->status !== $request->status) {

        Task_details::create([
            'task_id' => $task->id,
            'status' => $request->status,
            'notes' => $request->input('notes'),
        ]);
    }

    return redirect()->back();
}

    public function submitTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = Auth::user();

        if (! $user || ($user->role !== 'admin' && $user->role !== 'spv' && $task->assigned_to !== $user->id)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk submit task ini.');
        }

        if ($user->role === 'employee') {
            $latestStatus = $task->latestDetail?->status ?? 'todo';
            if ($latestStatus !== 'on_progress') {
                return redirect()->back()->with('error', 'Task hanya bisa disubmit dari status On Progress.');
            }
        }

        $task->details()->create([
            'status' => 'submitted',
            'notes' => $request->input('notes'),
        ]);

        return redirect()->back()->with('success', 'Task berhasil disubmit.');
    }

    public function reviewTask(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user || ! in_array($user->role, ['admin', 'spv'], true)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mereview task.');
        }

        $request->validate([
            'review_status' => 'required|in:accepted,rejected',
            'notes' => 'nullable|string|max:1000',
        ]);

        $task = Task::findOrFail($id);

        $task->details()->create([
            'status' => $request->review_status,
            'notes' => $request->review_status === 'rejected' ? $request->input('notes') : null,
        ]);

        return redirect()->back()->with('success', $request->review_status === 'accepted'
            ? 'Task berhasil disetujui.'
            : 'Task dikembalikan untuk revisi.');
    }
    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
{
    $user = Auth::user();

    if (! $user || ! in_array($user->role, ['admin', 'spv'], true)) {
        return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus task.');
    }

    Task::findOrFail($id)->delete();
    return redirect()->back();
}
}
