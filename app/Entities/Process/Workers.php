<?php

namespace App\Entities\Process;

use Illuminate\Database\Eloquent\Model;

class Workers extends Model
{
    protected $table = "process_workers";

    public $timestamps = false;

    public function __toString()
    {
        return $this->name;
    }
}
