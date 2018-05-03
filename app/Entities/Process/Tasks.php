<?php

namespace App\Entities\Process;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    protected $table = "process_tasks";

    protected $fillable = [
        "process_id",
        "title",
        "is_ready",
        "comment"
    ];

    public function getStatus($copyId)
    {
        return Status::where('task_id', $this->id)
            ->where('copy_id', $copyId)
            ->first();
    }
}