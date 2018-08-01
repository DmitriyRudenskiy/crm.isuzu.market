<?php

namespace App\Entities\Parts;

use App\Entities\Process\Workers;
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

    public function worker()
    {
        return $this->hasOne(Workers::class, 'id', 'worker_id');
    }
}