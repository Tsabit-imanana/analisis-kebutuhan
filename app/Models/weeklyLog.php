<?php

namespace App\Models;

use App\Models\User;
use App\Models\divisi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class weeklyLog extends Model
{
    /** @use HasFactory<\Database\Factories\WeeklyLogFactory> */
    use HasFactory;

    protected $fillable = [
        's_date',
        'f_date',
        'logged_by',
        'divisi_id',
        'status',
        'title',
        'description',
        'notes',
        'photo'
    ];

    public function loggedBy()
    {
        return $this->belongsTo(User::class, 'logged_by');
    }

    public function divisi()
    {
        return $this->belongsTo(divisi::class, 'divisi_id');
    }
}
