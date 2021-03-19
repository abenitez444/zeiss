<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order', 'reference', 'status', 'client', 'dateTime', 'code', 'montage', 'coating', 'color', 'name_file', 'EstadoOrden'
    ];
}
