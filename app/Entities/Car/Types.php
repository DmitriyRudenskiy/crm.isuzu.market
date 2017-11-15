<?php

namespace App\Entities\Car;

use Illuminate\Database\Eloquent\Model;

class Types extends Model
{
    protected $table = 'telegram';

    protected $fillable = [
        "message_id",
        "update_id",
        "message",
        "added_on"
    ];

    public $timestamps = false;
}