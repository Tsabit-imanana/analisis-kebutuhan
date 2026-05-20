<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('divisi_id')
                ->nullable()
                ->after('role')
                ->constrained('divisis')
                ->nullOnDelete();
        });

        if (! Schema::hasColumn('users', 'divisi')) {
            return;
        }

        $existingDivisiNames = DB::table('users')
            ->whereNotNull('divisi')
            ->where('divisi', '!=', '')
            ->distinct()
            ->pluck('divisi');

        foreach ($existingDivisiNames as $divisiName) {
            $divisiId = DB::table('divisis')->where('nama_divisi', $divisiName)->value('id');

            if (! $divisiId) {
                $divisiId = DB::table('divisis')->insertGetId([
                    'nama_divisi' => $divisiName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('users')
                ->where('divisi', $divisiName)
                ->update(['divisi_id' => $divisiId]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('divisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'divisi_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('divisi')->nullable()->after('role');
            });

            $users = DB::table('users')
                ->leftJoin('divisis', 'users.divisi_id', '=', 'divisis.id')
                ->select('users.id', 'divisis.nama_divisi')
                ->get();

            foreach ($users as $user) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'divisi' => $user->nama_divisi,
                    ]);
            }

            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('divisi_id');
            });
        }
    }
};
