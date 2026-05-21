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
        Schema::table('weekly_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('weekly_logs', 'divisi_id')) {
                $table->foreignId('divisi_id')
                    ->nullable()
                    ->after('logged_by')
                    ->constrained('divisis')
                    ->nullOnDelete()
                    ->index();
            }

            if (! Schema::hasColumn('weekly_logs', 'status')) {
                $table->string('status', 20)
                    ->default('pending')
                    ->after('divisi_id')
                    ->index();
            }
        });

        // Backfill status for any existing rows.
        if (Schema::hasColumn('weekly_logs', 'status')) {
            DB::table('weekly_logs')
                ->whereNull('status')
                ->update(['status' => 'pending']);
        }

        // Backfill divisi_id snapshot from the logged_by user's current divisi_id.
        if (Schema::hasColumn('weekly_logs', 'divisi_id') && Schema::hasColumn('weekly_logs', 'logged_by')) {
            $logs = DB::table('weekly_logs')->select('id', 'logged_by')->get();

            foreach ($logs as $log) {
                if (! $log->logged_by) {
                    continue;
                }

                $userDivisiId = DB::table('users')
                    ->where('id', $log->logged_by)
                    ->value('divisi_id');

                DB::table('weekly_logs')
                    ->where('id', $log->id)
                    ->update(['divisi_id' => $userDivisiId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weekly_logs', function (Blueprint $table) {
            if (Schema::hasColumn('weekly_logs', 'status')) {
                $table->dropIndex(['status']);
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('weekly_logs', 'divisi_id')) {
                $table->dropConstrainedForeignId('divisi_id');
            }
        });
    }
};
