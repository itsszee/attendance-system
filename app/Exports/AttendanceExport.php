<?php

namespace App\Exports;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Attendance::with('user')->get()->map(function ($a) {
            return [
                'Nama' => $a->user->name,
                'Tanggal' => $a->date,
                'Mode' => $a->mode,
                'Status' => $a->status,
                'Check In' => $a->check_in,
                'Task' => $a->task,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Tanggal',
            'Mode',
            'Status',
            'Check In',
            'Task',
        ];
    }
}

