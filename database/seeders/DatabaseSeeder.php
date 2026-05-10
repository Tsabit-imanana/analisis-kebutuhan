<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use App\Models\tahun_periode;
use App\Models\bulan_periode;
use App\Models\Task_details;
use App\Models\weeklyLog;
use App\Models\periodeLaporan;
use App\Models\budget;
use App\Models\divisi;
use App\Models\detailLaporan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Test admin',
            'email' => 'test@admin.com',
            'password' => bcrypt('password'),
            'nik' => "1234567890",
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Contoh Alamat No. 123',
            'no_telepon' => '081234567890',
            'role' => 'admin',
            'divisi' => 'IT',
        ]);
        User::create([
            'name' => 'Test SPV',
            'email' => 'test@spv.com',
            'password' => bcrypt('password'),
            'nik' => "12345678910",
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Contoh Alamat No. 123',
            'no_telepon' => '081234567890',
            'role' => 'spv',
            'divisi' => 'IT',
        ]);
        User::create([
            'name' => 'Test employee',
            'email' => 'test@employee.com',
            'password' => bcrypt('password'),
            'nik' => "12345678911",
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Contoh Alamat No. 123',
            'no_telepon' => '081234567890',
            'role' => 'employee',
            'divisi' => 'IT',
        ]);

        Task::create([
            'title' => 'Task 1',
            'description' => 'Deskripsi task 1',
            'assigned_to' => 3, // ID user employee
            'assigned_from' => 2, // ID user spv  
        ]); 

        Task_details::create([
            'task_id' => 1,
            'status' => 'pending',
            ]);

        weeklyLog::create([
            's_date' => '2026-05-01',
            'f_date' => '2026-05-07',
            'logged_by' => 3, // ID user employee
            'title' => 'Weekly Log 1',
            'description' => 'Deskripsi weekly log 1',
        ]);

        tahun_periode::create([
            'tahun' => '2026',
        ]);
        tahun_periode::create([
            'tahun' => '2027',
        ]);
        tahun_periode::create([
            'tahun' => '2028',
        ]);
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        foreach ($bulan as $bln) {
            bulan_periode::create([
                'bulan' => $bln,
            ]);
        }

        divisi::create([
            'nama_divisi' => 'IT',
        ]);
        periodeLaporan::create([
            'bulan_id' => 2, // Februari
            'tahun_id' => 1, // 2026
            'divisi_id' => 1, // IT
        ]);
        budget::create([
            'periode_laporan_id' => 1, // Januari 2026
            'jumlah_budget' => 1000000,
        ]);
        budget::create([
            'periode_laporan_id' => 1, // Januari 2026
            'jumlah_budget' => 2000000,
        ]);

        detailLaporan::create([
            'periode_laporan_id' => 1, // Januari 2026
            'user_id' => 3, // ID user employee
            'kegiatan' => 'Kegiatan 1',
            'deskripsi' => 'Deskripsi kegiatan 1',
            'jumlah_anggaran' => 500000,
            'bukti_foto' => null,
        ]);

    }
}
