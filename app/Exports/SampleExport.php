<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class SampleExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        // empty collection, just headings
        return new Collection([]);
    }

    public function headings(): array
    {
        return [
            'first_name',
            'last_name',
            'department',
            'designation',
            'salary',
            'status',
            'role',
        ];
    }
}
