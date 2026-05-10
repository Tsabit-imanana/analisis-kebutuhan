<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detailLaporan extends Model
{
    /** @use HasFactory<\Database\Factories\DetailLaporanFactory> */
    use HasFactory;

    protected $fillable = [
        'periode_laporan_id',
        'user_id',
        'kegiatan',
        'deskripsi',
        'jumlah_anggaran',
        'bukti_foto',
    ];

    public function periodeLaporan()
    {
        return $this->belongsTo(periodeLaporan::class, 'periode_laporan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
