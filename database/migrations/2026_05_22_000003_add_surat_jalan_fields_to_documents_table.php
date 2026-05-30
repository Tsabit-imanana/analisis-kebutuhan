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
        Schema::table('documents', function (Blueprint $table) {
            $table->string('up_kepada')->nullable()->after('alamat_kepada');
            $table->boolean('faktur_menyusul')->default(true)->after('up_kepada');
            $table->string('volume')->nullable()->after('faktur_menyusul');
            $table->text('nama_barang')->nullable()->after('volume');
            $table->text('nomer_seri')->nullable()->after('nama_barang');
            $table->string('kontrak_khs_no')->nullable()->after('nomer_seri');
            $table->date('kontrak_khs_tanggal')->nullable()->after('kontrak_khs_no');
            $table->string('kontrak_rinci_no')->nullable()->after('kontrak_khs_tanggal');
            $table->date('kontrak_rinci_tanggal')->nullable()->after('kontrak_rinci_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn([
                'up_kepada',
                'faktur_menyusul',
                'volume',
                'nama_barang',
                'nomer_seri',
                'kontrak_khs_no',
                'kontrak_khs_tanggal',
                'kontrak_rinci_no',
                'kontrak_rinci_tanggal',
            ]);
        });
    }
};
