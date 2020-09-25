<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{

	protected $table = 'categorias'; //Tabla que hace referencia el Modelo

    protected $primaryKey = 'id'; //Atributo primaryKey de la tabla categoria

    protected $fillable = [

    	'nombre',
    	'descripcion',
    	'estado'

    ];

    //
    public function producto()
    {
        return $this->belongsToMany(Producto::class, 'productos');
		//Una Categoria puede tener muchos artículos
        //return $this->hasMany(Articulo::class);
    }
}
