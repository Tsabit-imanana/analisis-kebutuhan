<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreweeklyLogRequest;
use App\Http\Requests\UpdateweeklyLogRequest;
use App\Models\User;
use App\Models\weeklyLog;
use App\Models\divisi;
use Illuminate\Support\Facades\Storage;

class WeeklyLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalLogs = weeklyLog::count();
        $pendingLogs = weeklyLog::where('status', 'pending')->orWhereNull('status')->count();
        $confirmedLogs = weeklyLog::where('status', 'confirmed')->count();

        return view('admin.weekly_log.index', [
            'weeklyLogs' => weeklyLog::with(['loggedBy.divisi', 'divisi'])->get(),
            'users' => User::select('id', 'name')->get(),
            'divisis' => divisi::select('id', 'nama_divisi')->orderBy('nama_divisi')->get(),
            'totalLogs' => $totalLogs,
            'pendingLogs' => $pendingLogs,
            'confirmedLogs' => $confirmedLogs,
        ]);
    }

    public function employeeIndex()
    {
        $user = auth()->user();

        $weeklyLogs = weeklyLog::with(['loggedBy.divisi', 'divisi'])
            ->where('logged_by', $user->id)
            ->orderByDesc('s_date')
            ->get();

        $totalLogs = $weeklyLogs->count();
        $pendingLogs = $weeklyLogs->where('status', 'pending')->count();
        $confirmedLogs = $weeklyLogs->where('status', 'confirmed')->count();

        return view('employee.weekly_log.index', [
            'weeklyLogs' => $weeklyLogs,
            'totalLogs' => $totalLogs,
            'pendingLogs' => $pendingLogs,
            'confirmedLogs' => $confirmedLogs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('weekly_log.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreweeklyLogRequest $request)
    {
        $data = $request->validated();

        if (auth()->check()) {
            $data['logged_by'] = auth()->id();
        }

        // Always start as pending unless explicitly changed later.
        $data['status'] = 'pending';

        if (empty($data['divisi_id'])) {
            $loggedByDivisiId = null;
            if (! empty($data['logged_by'])) {
                $loggedByDivisiId = User::where('id', $data['logged_by'])->value('divisi_id');
            }
            $data['divisi_id'] = $loggedByDivisiId;
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('weekly_logs', 'public');
            $data['photo'] = Storage::url($path);
        }

        weeklyLog::create($data);

        return redirect()->back()->with('success', 'Weekly log berhasil ditambahkan.');
    }

    public function employeeStore(StoreweeklyLogRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();

        $data['logged_by'] = $user->id;
        $data['status'] = 'pending';

        if (empty($data['divisi_id'])) {
            $data['divisi_id'] = $user->divisi_id;
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('weekly_logs', 'public');
            $data['photo'] = Storage::url($path);
        }

        weeklyLog::create($data);

        return redirect()->back()->with('success', 'Weekly log berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(weeklyLog $weeklyLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(weeklyLog $weeklyLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateweeklyLogRequest $request, weeklyLog $weeklyLog , $id)
    {
        $weeklyLog = weeklyLog::findOrFail($id);
        $data = $request->validated();

        // Keep divisi snapshot aligned to the selected logged_by.
        if (! empty($data['logged_by'])) {
            $data['divisi_id'] = User::where('id', $data['logged_by'])->value('divisi_id');
        }

        // Ensure status is not accidentally cleared.
        if (array_key_exists('status', $data) && $data['status'] === null) {
            unset($data['status']);
        }

        if ($request->hasFile('photo')) {
            if ($weeklyLog->photo) {
                $photoPath = str_replace('/storage/', '', $weeklyLog->photo);
                Storage::disk('public')->delete($photoPath);
            }
            $path = $request->file('photo')->store('weekly_logs', 'public');
            $data['photo'] = Storage::url($path);
        }

        $weeklyLog->update($data);

        return redirect()->back()->with('success', 'Weekly log berhasil diupdate.');
    }

    public function employeeUpdate(UpdateweeklyLogRequest $request, $id)
    {
        $user = auth()->user();
        $weeklyLog = weeklyLog::where('id', $id)
            ->where('logged_by', $user->id)
            ->firstOrFail();

        if ($weeklyLog->status === 'confirmed') {
            return redirect()->back()->with('error', 'Weekly log sudah dikonfirmasi dan tidak bisa diubah.');
        }

        $data = $request->validated();
        $data['logged_by'] = $user->id;
        $data['status'] = 'pending';
        $data['divisi_id'] = $user->divisi_id;

        if ($request->hasFile('photo')) {
            if ($weeklyLog->photo) {
                $photoPath = str_replace('/storage/', '', $weeklyLog->photo);
                Storage::disk('public')->delete($photoPath);
            }
            $path = $request->file('photo')->store('weekly_logs', 'public');
            $data['photo'] = Storage::url($path);
        }

        $weeklyLog->update($data);

        return redirect()->back()->with('success', 'Weekly log berhasil diupdate.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(weeklyLog $weeklyLog, $id)
    {
            $weeklyLog = weeklyLog::findOrFail($id);
            if ($weeklyLog->photo) {
                $photoPath = str_replace('/storage/', '', $weeklyLog->photo);
                Storage::disk('public')->delete($photoPath);
            }
        $weeklyLog->delete();

        return redirect()->back()->with('success', 'Weekly log berhasil dihapus.');
    }

    public function employeeDestroy($id)
    {
        $user = auth()->user();
        $weeklyLog = weeklyLog::where('id', $id)
            ->where('logged_by', $user->id)
            ->firstOrFail();

        if ($weeklyLog->status === 'confirmed') {
            return redirect()->back()->with('error', 'Weekly log sudah dikonfirmasi dan tidak bisa dihapus.');
        }

        if ($weeklyLog->photo) {
            $photoPath = str_replace('/storage/', '', $weeklyLog->photo);
            Storage::disk('public')->delete($photoPath);
        }

        $weeklyLog->delete();

        return redirect()->back()->with('success', 'Weekly log berhasil dihapus.');
    }
}
