<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'puntos', 'producto_id', 'user_id'
    ];
}
