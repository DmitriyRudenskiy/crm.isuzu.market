<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class DocsParams extends Model
{
    protected $table = 'docs_params';

    protected $fillable = [
        "doc_id",
        "visible",
        "position",
        "key",
        "value"
    ];

}

