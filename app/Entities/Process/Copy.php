<?php

namespace App\Entities\Process;

use Illuminate\Database\Eloquent\Model;

class Copy extends Model
{
    protected $table = "process_copy";

    protected $fillable = [
        "process_id",
        "name"
    ];

    public function process()
    {
        return $this->hasOne(Process::class, 'id', 'process_id');
    }
}