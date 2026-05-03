<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Task_details;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'assigned_from',
    ];
public function details()
{
    return $this->hasMany(Task_details::class, 'task_id');
}

public function assignedTo()
{
    return $this->belongsTo(User::class, 'assigned_to');
}

public function assignedFrom()
{
    return $this->belongsTo(User::class, 'assigned_from');
}
}
