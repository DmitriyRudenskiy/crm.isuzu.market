<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Variants extends Model
{
    const VENDOR = 'ISUZU';

    protected $table = 'variants';

    protected $fillable = [ "parent_id", "title"];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getModel()
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    public function getFullTitle()
    {
        return implode(
            " ",
            [
                self::VENDOR,
                $this->getModel()->first()->title,
                $this->title
            ]
        );
    }
}