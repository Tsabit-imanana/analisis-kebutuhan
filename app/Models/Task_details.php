<?php

namespace App\Models;
use App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task_details extends Model
{
    /** @use HasFactory<\Database\Factories\TaskDetailsFactory> */
    use HasFactory;

    protected $fillable = [
        'task_id',
        'status',
    ];
    public function task()
{
    return $this->belongsTo(Task::class, 'task_id');
}
    
}
