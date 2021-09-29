<?php

namespace App\Imports;

use App\Client;
use Maatwebsite\Excel\Concerns\{Importable, WithChunkReading, WithBatchInserts, WithMultipleSheets};

class ProvidersImport implements WithChunkReading, WithBatchInserts, WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            0 => new ProviderSheetImport(),
        ];
    }

    public function chunkSize(): int
    {
        return 2000;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
