<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AttendanceExport implements FromCollection,WithHeadings,ShouldAutoSize
{
    /**
     * Return the collection of data to export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
       return Attendance::with(relations: 'employee.user')
            ->get()
            ->map(function ($attendance) {
                return [
                    'first_name' => $attendance->employee->first_name ?? '',
                    'last_name'  => $attendance->employee->last_name ?? '',
                    'email'      => $attendance->employee->user->email ?? '',
                    'date'       => $attendance->date,
                    'check_in'   => $attendance->check_in,
                    'check_out'  => $attendance->check_out,
                    'status'     => $attendance->status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Email',
            'Date',
            'Check In Time',
            'Check Out Time',
            'Status',
        ];
    }
}

    


