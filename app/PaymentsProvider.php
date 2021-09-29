<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentsProvider extends Model
{
    protected $fillable = [
        'ChequeQAD', 'VoucherQAD', 'FechaPago', 'Proveedor','Factura', 'name_file'
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }
}
