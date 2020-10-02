<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{

	//
    protected $fillable = [
        'numero_factura', 'nombre_factura', 'total_cost', 'estado',
    ];

    //
    public function user()
    {
        return $this->belongsToMany(User::class, '_users_facturas');
    }

    public function complements()
    {
        return $this->belongsToMany(Complement::class, 'complements');
    }
}
