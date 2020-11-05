<?php

namespace App\Imports;

use App\Categoria;
use Maatwebsite\Excel\Concerns\{Importable, ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithCustomCsvSettings};

class CategoriasImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithCustomCsvSettings
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Categoria([
            'nombre' => $row['nombre'],
            'descripcion' => $row['descripcion'],
            'estado' => $row['estado'],
        ]);
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
