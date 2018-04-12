<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    protected $table = 'tasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "is_ready",
        "worker",
        "comment",
        "period"
    ];
}
