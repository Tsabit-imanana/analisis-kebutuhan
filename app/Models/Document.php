<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_type',
        'status',
        'created_by',
        'approved_by',
        'submitted_at',
        'approved_at',
        'nomor_surat',
        'kota_surat',
        'tanggal_surat',
        'kepada',
        'alamat_kepada',
        'up_kepada',
        'faktur_menyusul',
        'volume',
        'nama_barang',
        'nomer_seri',
        'kontrak_khs_no',
        'kontrak_khs_tanggal',
        'kontrak_rinci_no',
        'kontrak_rinci_tanggal',
        'penerima',
        'pengirim',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'kontrak_khs_tanggal' => 'date',
        'kontrak_rinci_tanggal' => 'date',
        'faktur_menyusul' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
