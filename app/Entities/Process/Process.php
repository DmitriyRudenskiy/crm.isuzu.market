<?php

namespace App\Entities\Process;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $table = "process";

    protected $fillable = [
        "worker_id",
        "name"
    ];

    public function worker()
    {
        return $this->hasOne(Workers::class, 'id', 'worker_id');
    }

    public function tasks()
    {
        return $this->hasMany(Tasks::class);
    }
}