<?php

namespace App\Entities\Process;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = "process_tasks_status";

    protected $fillable = [
        "copy_id",
        "task_id",
        "is_ready",
        "comment"
    ];

    public $timestamps = false;
}