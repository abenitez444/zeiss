<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Punto extends Model
{
    //
    protected $table = 'puntos'; //Tabla que hace referencia el Modelo

    protected $primaryKey = 'id'; //Atributo primaryKey de la tabla categoria

    
    protected $fillable = [
        'puntos', 'estado'
    ];

    public function user()
    {
        return $this->belongsToMany(User::class, '_users_puntos');
    }

    public function producto()
    {
        return $this->belongsToMany(Producto::class, 'productos');
		//Una Categoria puede tener muchos artículos
        //return $this->hasMany(Articulo::class);
    }
}
