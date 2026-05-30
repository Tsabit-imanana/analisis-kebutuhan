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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('template_type', 50)->default('surat_jalan');
            $table->string('status', 20)->default('draft')->index();
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            // Template fields (Surat Jalan)
            $table->string('nomor_surat');
            $table->string('kota_surat');
            $table->date('tanggal_surat');
            $table->string('kepada');
            $table->text('alamat_kepada');
            $table->string('penerima');
            $table->string('pengirim');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
