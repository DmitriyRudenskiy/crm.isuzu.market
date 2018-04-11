<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class SpareParts extends Model
{
    protected $table = 'spare_parts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "is_ready",
        "start_work",
        "company",
        "vin",
        "type",
        "comment"
    ];
}
