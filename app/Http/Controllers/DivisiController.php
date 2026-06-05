<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoredivisiRequest;
use App\Http\Requests\UpdatedivisiRequest;
use App\Models\budget;
use App\Models\detailLaporan;
use App\Models\divisi;
use App\Models\Task;
use App\Models\weeklyLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $query = divisi::query();
        if ($search) {
            $query->where('nama_divisi', 'LIKE', "%{$search}%");
        }

        $divisis = $query->orderBy('nama_divisi')->paginate(12)->withQueryString();

        return view('admin.settings.index', compact('divisis', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.settings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoredivisiRequest $request)
    {
        $data = $request->validated();
        divisi::create($data);

        return Redirect::route('settings.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(divisi $divisi)
    {
        return view('admin.settings.show', compact('divisi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(divisi $divisi)
    {
        return view('admin.settings.edit', compact('divisi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatedivisiRequest $request, divisi $divisi)
    {
        $data = $request->validated();
        $divisi->update($data);

        return Redirect::route('settings.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(divisi $divisi)
    {
        // Prevent deletion if in use by users or periodeLaporans
        if ($divisi->users()->count() > 0 || $divisi->periodeLaporans()->count() > 0) {
            return Redirect::back()->with('error', 'Divisi tidak dapat dihapus karena masih direferensi oleh data lain.');
        }

        $divisi->delete();

        return Redirect::route('settings.index')->with('success', 'Divisi berhasil dihapus.');
    }

    public function report(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $start = Carbon::parse($validated['start_date'])->startOfDay();
        $end = Carbon::parse($validated['end_date'])->endOfDay();

        if ($start->greaterThan($end)) {
            return Redirect::back()->withErrors(['end_date' => 'Tanggal akhir harus setelah tanggal awal.']);
        }

        $tasks = Task::with(['latestDetail', 'assignedTo', 'assignedFrom'])
            ->whereBetween('created_at', [$start, $end])
            ->get();

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
                $taskStatusCounts[$status]++;
            }
        }

        $weeklyLogs = weeklyLog::with(['loggedBy', 'divisi'])
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $weeklyStatusCounts = [
            'pending' => 0,
            'confirmed' => 0,
            'rejected' => 0,
        ];

        foreach ($weeklyLogs as $log) {
            $status = $log->status ?? 'pending';
            if (array_key_exists($status, $weeklyStatusCounts)) {
                $weeklyStatusCounts[$status]++;
            }
        }

        $budgets = budget::with(['periodeLaporan.divisi', 'periodeLaporan.bulan', 'periodeLaporan.tahun'])
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $details = detailLaporan::with(['periodeLaporan.divisi', 'periodeLaporan.bulan', 'periodeLaporan.tahun', 'user'])
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalBudget = $budgets->sum('jumlah_budget');
        $totalRealization = $details->sum('jumlah_anggaran');
        $remaining = $totalBudget - $totalRealization;
        $percentage = $totalBudget > 0 ? round(($totalRealization / $totalBudget) * 100, 2) : 0;

        $payload = [
            'start' => $start,
            'end' => $end,
            'tasks' => $tasks,
            'taskStatusCounts' => $taskStatusCounts,
            'weeklyLogs' => $weeklyLogs,
            'weeklyStatusCounts' => $weeklyStatusCounts,
            'budgets' => $budgets,
            'details' => $details,
            'totalBudget' => $totalBudget,
            'totalRealization' => $totalRealization,
            'remaining' => $remaining,
            'percentage' => $percentage,
        ];

        $pdf = Pdf::loadView('admin.reports.summary', $payload)->setPaper('a4', 'portrait');
        $filename = 'report-rekap-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
