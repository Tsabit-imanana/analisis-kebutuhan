<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class divisi extends Model
{
    /** @use HasFactory<\Database\Factories\DivisiFactory> */
    use HasFactory;

    protected $fillable = ['nama_divisi'];

    public function periodeLaporans()
    {
        return $this->hasMany(periodeLaporan::class, 'divisi_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'divisi_id');
    }
}
