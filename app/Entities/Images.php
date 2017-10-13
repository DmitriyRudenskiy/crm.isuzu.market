<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'variant_id',
        'visible',
        'priority',
        'hash',
        'title'
    ];
}