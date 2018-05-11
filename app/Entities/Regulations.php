<?php

namespace App\Entities;

use App\Entities\Process\Workers;
use Illuminate\Database\Eloquent\Model;

class Regulations extends Model
{
    protected $table = 'regulations';

    public function worker()
    {
        return $this->hasOne(Workers::class, 'id', 'worker_id');
    }
}
