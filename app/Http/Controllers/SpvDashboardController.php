<?php

namespace App\Http\Controllers;

use App\Models\budget;
use App\Models\detailLaporan;
use App\Models\weeklyLog;

class SpvDashboardController extends Controller
{
    public function index()
    {
        $weeklyTotal = weeklyLog::count();
        $weeklyPending = weeklyLog::where('status', 'pending')->orWhereNull('status')->count();
        $weeklyConfirmed = weeklyLog::where('status', 'confirmed')->count();

        $totalBudget = budget::sum('jumlah_budget');
        $totalRealized = detailLaporan::sum('jumlah_anggaran');
        $remainingBudget = $totalBudget - $totalRealized;
        $realizedPercentage = $totalBudget > 0
            ? round(($totalRealized / $totalBudget) * 100, 2)
            : 0;

        return view('spv.dashboard', [
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
