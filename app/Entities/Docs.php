<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Docs extends Model
{
    protected $table = 'docs';

    protected $fillable = [ "visible", "name"];

    public function getParams()
    {
        return $this->hasMany(DocsParams::class, 'doc_id', 'id')
            //->orderBy('visible', 'desc')
            ->orderBy('position', 'desc')
            ->orderBy('id');
    }

}


