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
}