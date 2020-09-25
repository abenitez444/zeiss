<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //
    protected $fillable = [
        'categorias_id', 'puntos_id', 'codigo', 'nombre', 'stock', 'descripcion', 'estado', 'imagen',
    ];

    public function categoria()
    {
        return $this->belongsToMany(Categoria::class, 'categorias');
    }
    public function punto()
    {
        return $this->belongsToMany(Punto::class, 'puntos');
    }
}
