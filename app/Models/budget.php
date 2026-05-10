<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class budget extends Model
{
    /** @use HasFactory<\Database\Factories\BudgetFactory> */
    use HasFactory;

    protected $fillable = ['periode_laporan_id', 'jumlah_budget'];

    public function periodeLaporan()
    {
        return $this->belongsTo(periodeLaporan::class, 'periode_laporan_id');
    }
}
