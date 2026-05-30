<?php

namespace App\Http\Controllers;

use App\Models\budget;
use App\Models\detailLaporan;
use App\Models\Task;
use App\Models\weeklyLog;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $userCount = $user ? 1 : 0;
        $divisiCount = $user && $user->divisi_id ? 1 : 0;

        $tasks = Task::with('latestDetail')
            ->where('assigned_to', $user->id)
            ->get();
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

        $weeklyTotal = weeklyLog::where('logged_by', $user->id)->count();
        $weeklyPending = weeklyLog::where('logged_by', $user->id)
            ->where(function ($query) {
                $query->where('status', 'pending')->orWhereNull('status');
            })
            ->count();
        $weeklyConfirmed = weeklyLog::where('logged_by', $user->id)
            ->where('status', 'confirmed')
            ->count();

        $periodeIds = detailLaporan::where('user_id', $user->id)
            ->distinct()
            ->pluck('periode_laporan_id');

        $totalBudget = budget::whereIn('periode_laporan_id', $periodeIds)
            ->sum('jumlah_budget');
        $totalRealized = detailLaporan::where('user_id', $user->id)
            ->sum('jumlah_anggaran');
        $remainingBudget = $totalBudget - $totalRealized;
        $realizedPercentage = $totalBudget > 0
            ? round(($totalRealized / $totalBudget) * 100, 2)
            : 0;

        return view('employee.dashboard', [
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