<?php

namespace App\Imports;

use DB;
use App\Factura;
use App\Punto;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class PuntosSheetImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $firstRowDone = false;

        foreach ($collection as $row)
        {
            if(!$firstRowDone) {
                $firstRowDone = true;
            }
            else {
                $factura = Factura::with('user')->where("IdDocumento", $row[2])->first();

                if(!empty($factura)){
                    $punto = Punto::create([
                        'puntos' => $row[9],
                        'estado' => 1
                      ]);

                    DB::table('_users_puntos')->insert(
                        ['user_id' => $factura->user[0]->id, 'punto_id' => $punto->id,'factura_id' => $factura->id, 'created_at' => NOW(), 'updated_at' => NOW()]
                    );
                }
            }
        }
    }
}
