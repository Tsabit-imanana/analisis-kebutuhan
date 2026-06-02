<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        $user = $request->user();

        if (! $user || $user->role !== 'admin') {
            return redirect()->back()->with('error', 'Hubungi admin untuk mengubah data.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'no_telepon' => ['nullable', 'string', 'max:30'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->no_telepon = $validated['no_telepon'] ?? null;
        $user->alamat = $validated['alamat'] ?? null;

        if (Schema::hasColumn('users', 'photo') && $request->hasFile('photo')) {
            if ($user->photo) {
                $photoPath = str_replace('/storage/', '', $user->photo);
                Storage::disk('public')->delete($photoPath);
            }

            $path = $request->file('photo')->store('profile', 'public');
            $user->photo = Storage::url($path);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
