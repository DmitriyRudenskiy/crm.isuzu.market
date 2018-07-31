<?php

namespace App\Entities\Parts;

use Illuminate\Database\Eloquent\Model;

class Phones extends Model
{
    protected $table = 'parts_phone';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "worker_id",
        "number",
        "source",
        "comment"
    ];
}