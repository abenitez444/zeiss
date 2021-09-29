<?php

namespace App\Exports;

use App\Factura;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FacturasClientesExport implements FromArray, WithHeadings
{
    protected $facturas;

    public function __construct(array $facturas = null)
    {
        $this->facturas = $facturas;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Cliente',
            '# Factura',
            '# Factura',
            'Costo Total',
            'Estado',
            'Usuario Asociado',
        ];
    }

    public function array(): array
    {
        return $this->facturas;
    }

    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function collection()
    // {
    //     return $this->facturas ?: Factura::all();
    // }
}
