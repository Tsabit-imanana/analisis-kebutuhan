<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\Task_details;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(Task::with('details')->get());
    return view('admin.task.index', [
        'tasks' => Task::with(['details', 'assignedTo', 'assignedFrom'])->get(),
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

        return $result;
        //add view nanti
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
    public function update(UpdateTaskRequest $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
