<?php

namespace App\Imports;

use App\Categoria;
use App\Producto;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithCustomCsvSettings};

class ProductosImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithCustomCsvSettings
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $categoria = Categoria::where("nombre", $row['categorias'])->first();

        if($categoria){
            return new Producto([
                'categorias_id' => $categoria->id,
                'puntos' => $row['puntos'],
                'codigo' => $row['codigo'],
                'nombre' => $row['nombre'],
                'stock' => $row['stock'],
                'descripcion' => $row['descripcion'],
                'estado' => $row['estado'],
            ]);
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter'        => ',',
            'enclosure'        => '"',
            'input_encoding'   => 'ISO-8859-1',
        ];
    }
}
