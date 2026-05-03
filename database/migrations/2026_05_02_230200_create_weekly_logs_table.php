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
        Schema::create('weekly_logs', function (Blueprint $table) {
            $table->id();
            $table->date('s_date');
            $table->date('f_date');
            $table->foreignId('logged_by')
              ->constrained('users')
              ->cascadeOnDelete();
            $table->timestamps();
            $table->string('title');
            $table ->text('description')->nullable();
            $table ->text('notes')->nullable();
            $table->string('photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_logs');
    }
};
