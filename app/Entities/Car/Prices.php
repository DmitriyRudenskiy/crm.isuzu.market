<?php

namespace App\Entities\Car;

use Illuminate\Database\Eloquent\Model;

class Prices extends Model
{
    protected $table = 'car_prices';

    protected $fillable = [
        "type_id",
        "base_id",
        "price"
    ];

    public $timestamps = false;
}