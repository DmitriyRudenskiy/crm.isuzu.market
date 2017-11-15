<?php

namespace App\Entities\Car;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = "car_categories";

    protected $fillable = [
        "position",
        "title"
    ];

    public $timestamps = false;
}