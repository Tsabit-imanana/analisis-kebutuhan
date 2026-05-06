<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\Task_details;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.task.index', [
            'tasks' => Task::with(['details', 'assignedTo', 'assignedFrom'])->get(),
            'users' => User::select('id', 'name')->get(),
        ]);
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'required|exists:users,id',
            'assigned_from' => 'required|exists:users,id',
        ]);

        $result = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'assigned_to' => $request->assigned_to,
            'assigned_from' => $request->assigned_from,
        ]);

        $taskId = $result->id;
        Task_details::create([
            'task_id' => $taskId,
            'status' => 'pending',
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
public function update(Request $request, $id)
{
    $request->validate([
    'status' => 'required|in:pending,on_progress,submitted,accepted'
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
        ]);
    }

    return redirect()->back();
}
    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
{
    Task::findOrFail($id)->delete();
    return redirect()->back();
}
}
