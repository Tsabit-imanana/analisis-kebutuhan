<?php

namespace App\Http\Controllers;

use App\Models\divisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RoleManagementController extends Controller
{
    private const ROLES = ['admin', 'spv', 'employee'];

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $users = User::query()
            ->with('divisi')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($nestedQuery) use ($search) {
                    $nestedQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%")
                        ->orWhereHas('divisi', function ($divisiQuery) use ($search) {
                            $divisiQuery->where('nama_divisi', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $divisis = divisi::orderBy('nama_divisi')->get();

        return view('admin.role-management.index', [
            'users' => $users,
            'roles' => self::ROLES,
            'divisis' => $divisis,
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'nik' => ['required', 'string', 'max:255', 'unique:users,nik'],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_telepon' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:' . implode(',', self::ROLES)],
            'divisi_id' => ['nullable', 'exists:divisis,id'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'nik' => $validated['nik'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'],
            'no_telepon' => $validated['no_telepon'],
            'role' => $validated['role'],
            'divisi_id' => $validated['divisi_id'] ?? null,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.role-management.edit', [
            'user' => $user,
            'roles' => self::ROLES,
            'divisis' => divisi::orderBy('nama_divisi')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
            'nik' => ['required', 'string', 'max:255', 'unique:users,nik,' . $user->id],
            'tanggal_lahir' => ['required', 'date'],
            'jenis_kelamin' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'no_telepon' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:' . implode(',', self::ROLES)],
            'divisi_id' => ['nullable', 'exists:divisis,id'],
        ]);

        if ($user->role === 'admin' && $validated['role'] !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();

            if ($adminCount <= 1) {
                return back()->with('error', 'Admin terakhir tidak boleh diubah menjadi non-admin.')->withInput();
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->nik = $validated['nik'];
        $user->tanggal_lahir = $validated['tanggal_lahir'];
        $user->jenis_kelamin = $validated['jenis_kelamin'];
        $user->alamat = $validated['alamat'];
        $user->no_telepon = $validated['no_telepon'];
        $user->role = $validated['role'];
        $user->divisi_id = $validated['divisi_id'] ?? null;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun yang sedang login.');
        }

        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();

            if ($adminCount <= 1) {
                return back()->with('error', 'Admin terakhir tidak boleh dihapus.');
            }
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}