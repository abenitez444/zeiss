<?php

namespace App\Imports;

use App\Punto;
use Maatwebsite\Excel\Concerns\{Importable, WithChunkReading, WithBatchInserts, WithMultipleSheets};

class PuntosImport implements WithChunkReading, WithBatchInserts, WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            'Puntos' => new PuntosSheetImport(),
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
