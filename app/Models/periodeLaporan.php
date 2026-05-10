<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class periodeLaporan extends Model
{
    /** @use HasFactory<\Database\Factories\PeriodeLaporanFactory> */
    use HasFactory;

    protected $fillable = ['bulan_id', 'tahun_id', 'divisi_id'];

    public function bulan()
    {
        return $this->belongsTo(bulan_periode::class, 'bulan_id');
    }

    public function tahun()
    {
        return $this->belongsTo(tahun_periode::class, 'tahun_id');
    }

    public function divisi()
    {
        return $this->belongsTo(divisi::class, 'divisi_id');
    }

    public function budgets()
    {
        return $this->hasMany(budget::class, 'periode_laporan_id');
    }

    public function details()
    {
        return $this->hasMany(detailLaporan::class, 'periode_laporan_id');
    }
}
