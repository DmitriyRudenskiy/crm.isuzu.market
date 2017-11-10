<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    protected $table = "regions";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["name"];

    public $timestamps = false;
}
