<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Telegram extends Model
{
    protected $table = 'telegram';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "message_id",
        "update_id",
        "message",
        "added_on"
    ];

    public $timestamps = false;
}