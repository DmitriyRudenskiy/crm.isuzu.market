<?php

namespace App\Entities\Car;

use Illuminate\Database\Eloquent\Model;

class Bases extends Model
{
    protected $table = "car_bases";
    
    protected $fillable = [
        "position",
        "category_id",
        "title"
    ];

    public $timestamps = false;
}