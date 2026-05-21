<?php

namespace App\Http\Controllers;

use App\Models\budget;
use App\Models\detailLaporan;
use App\Models\divisi;
use App\Models\Task;
use App\Models\User;
use App\Models\weeklyLog;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $divisiCount = divisi::count();

        $tasks = Task::with('latestDetail')->get();
        $taskTotal = $tasks->count();
        $taskStatusCounts = [
            'todo' => 0,
            'on_progress' => 0,
            'submitted' => 0,
            'accepted' => 0,
            'rejected' => 0,
        ];

        foreach ($tasks as $task) {
            $status = $task->latestDetail?->status ?? 'todo';
            if (array_key_exists($status, $taskStatusCounts)) {
                $taskStatusCounts[$status] += 1;
            }
        }

        $weeklyTotal = weeklyLog::count();
        $weeklyPending = weeklyLog::where('status', 'pending')->orWhereNull('status')->count();
        $weeklyConfirmed = weeklyLog::where('status', 'confirmed')->count();

        $totalBudget = budget::sum('jumlah_budget');
        $totalRealized = detailLaporan::sum('jumlah_anggaran');
        $remainingBudget = $totalBudget - $totalRealized;
        $realizedPercentage = $totalBudget > 0
            ? round(($totalRealized / $totalBudget) * 100, 2)
            : 0;

        return view('admin.dashboard', [
            'userCount' => $userCount,
            'divisiCount' => $divisiCount,
            'taskTotal' => $taskTotal,
            'taskStatusCounts' => $taskStatusCounts,
            'weeklyTotal' => $weeklyTotal,
            'weeklyPending' => $weeklyPending,
            'weeklyConfirmed' => $weeklyConfirmed,
            'totalBudget' => $totalBudget,
            'totalRealized' => $totalRealized,
            'remainingBudget' => $remainingBudget,
            'realizedPercentage' => $realizedPercentage,
        ]);
    }
}
