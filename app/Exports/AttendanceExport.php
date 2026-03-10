<?php

namespace App\Exports;
use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function collection()
    {
        return Attendance::with('user')->get()->map(function ($a) {
            return [
                'Nama' => $a->user->name,
                'Tanggal' => $a->date ? $a->date->format('d M Y') : '',
                'Mode' => $a->mode,
                'Status' => $a->status == 'on_time' ? 'On Time' : 'Late',
                'Check In' => $a->check_in_at ? $a->check_in_at->format('H:i:s') : '',
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

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Style untuk data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A2:F' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
            ],
        ]);

        // Auto-size columns
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20, // Nama
            'B' => 15, // Tanggal
            'C' => 10, // Mode
            'D' => 10, // Status
            'E' => 15, // Check In
            'F' => 30, // Task
        ];
    }
}

