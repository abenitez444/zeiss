<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'codigo', 'mensaje', 'autorizacion', 'referencia', 'importe', 'mediopago', 'financiado', 'plazos',
        's_transm', 'hash', 'tarjetahabiente', 'cveTipoPago', 'signature'
    ];

    public function factura()
    {
        return $this->belongsToMany(Factura::class, 'payments_facturas');
    }
}
