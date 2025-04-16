<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UserExport implements WithStrictNullComparison, FromView, WithEvents,ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {
        $users =  $this->data['body']??[];
       // Calculate the maximum number of rows for each multi-row column dynamically
        foreach ($users as &$user) {
            $maxRows = 1; // Initialize max_rows for the current user
            foreach ($user as $key => $value) {
                if (is_array($value)) {
                    $count = count($value);
                    $maxRows = max($maxRows, $count); // Keep track of the largest array count
                }
            }
            $user['max_rows'] = $maxRows; // Assign the max_rows value
        }
        unset($user);

        $this->data['body'] = $users;
        return view('report.export', [
            'data' => $this->data,
        ]);
    }
  public function columnWidths(): array
    {
        return [
            'A' => 30, // Width for column A
            'B' => 20, // Width for column B
            'C' => 20, // Width for column C
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
            'L' => 20,
            'M' => 20,
            'N' => 20,
            'O' => 20,
            'P' => 20,
            'Q' => 20,
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                // === Determine Dynamic Rows and Columns ===
                // $lastRow = $this->counter + 2; // Total rows with data
                $lastColumn = $sheet->getHighestColumn(); //$sheet->getHighestColumn(); // Find the last column letter
                $lastRow = $sheet->getHighestRow();
                // Header Styling
                $headerRange = 'A1:' . $lastColumn . '1';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '800080'], // Purple color
                    ],
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'], // White text
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Black border
                        ],
                    ],
                ]);

                // === 2. Apply Data Rows Background, Borders, and Styling ===
                $dataRange = 'A2:' . $lastColumn . $lastRow;
                $sheet->getStyle($dataRange)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D8BFD8'], // Light purple
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // Black border
                        ],
                    ],
                ]);
                // === 3. Add Footer with Logo and Borders ===
                $footerRow1 = $lastRow + 1;
                $footerRow2 = $lastRow + 2;
                // Assuming $footerRow1 is already defined
                $imagePath = public_path('images/logo.png');
                // Add Image to Column A
                if (file_exists($imagePath)) {
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setPath( $imagePath);
                    $drawing->setHeight(50);
                    $drawing->setCoordinates('A' . $footerRow1);
                    $drawing->setWorksheet($sheet);
                }
                // Determine last column for Footer Text
                $lastColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($lastColumn);
                $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastColumnIndex);

                // Add Footer Text
                $sheet->setCellValue($lastColumnLetter . $footerRow1, 'Sample Name');
                $sheet->setCellValue($lastColumnLetter . $footerRow2, now()->format('Y-m-d H:i:s'));

                // Apply Footer Border (Dark Black)
                $footerRange = 'A' . $footerRow1 . ':' . $lastColumnLetter . $footerRow2;
                $sheet->getStyle($footerRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            // 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            // 'color' => ['rgb' => '000000'], // Dark black border
                        ],
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                // Optional: Merge Columns A and B for the logo
                $sheet->mergeCells('A' . $footerRow1 . ':B' . $footerRow1);
            },
        ];
    } 
}
