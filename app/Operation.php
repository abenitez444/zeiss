<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'puntos', 'producto_id', 'user_id', 'cantidad'
    ];

    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function producto()
    {
        return $this->belongsToMany(Producto::class);
    }
}
