<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('task_details')
            ->where('status', 'pending')
            ->update(['status' => 'todo']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('task_details')
            ->where('status', 'todo')
            ->update(['status' => 'pending']);
    }
};
