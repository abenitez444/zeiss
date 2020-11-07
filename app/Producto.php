<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //
    protected $fillable = [
        'categorias_id', 'puntos', 'codigo', 'nombre', 'stock', 'descripcion', 'estado',
    ];

    protected $guarded = [];

    public function categoria()
    {
        return $this->belongsToMany(Categoria::class, 'categorias');
    }
}
