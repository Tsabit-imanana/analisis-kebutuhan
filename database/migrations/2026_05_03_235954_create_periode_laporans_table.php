<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('periode_laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bulan_id')
                  ->constrained('bulan_periodes')
                  ->cascadeOnDelete();
            $table->foreignId('tahun_id')
                  ->constrained('tahun_periodes')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_laporans');
    }
};
