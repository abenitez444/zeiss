<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{

	//
    protected $fillable = [
        'numero_factura', 'nombre_factura', 'total_cost', 'estado', 'estadoOtro', 'IdDocumento', 'NumParcialidad', 'payment_promise_date', 'deadline_for_complement',
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
