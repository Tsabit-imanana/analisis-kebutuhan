<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreweeklyLogRequest;
use App\Http\Requests\UpdateweeklyLogRequest;
use App\Models\User;
use App\Models\weeklyLog;
use Illuminate\Support\Facades\Storage;

class WeeklyLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.weekly_log.index', [
            'weeklyLogs' => weeklyLog::with('loggedBy')->get(),
            'users' => User::select('id', 'name')->get(),
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
}
