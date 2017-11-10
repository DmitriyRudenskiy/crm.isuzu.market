<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Phones extends Model
{
    protected $table = 'phones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "number",
        "vendor",
        "region_id",
        "comment"
    ];
}
