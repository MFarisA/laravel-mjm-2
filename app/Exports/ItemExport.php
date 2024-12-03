<?php

namespace App\Exports;

use App\Models\Project;
use App\Models\Useritem;
use App\Models\Item;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ItemExport implements FromCollection, ShouldAutoSize, WithEvents, WithDrawings, WithColumnWidths, WithCustomStartCell
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function columnWidths(): array
    {
        return [
            'A' => 2.57,
            'B' => 5.29,
            'C' => 13.71,
            'D' => 25.71,
            'E' => 11.29,
            'F' => 3.43,
            'G' => 10.71,
            'H' => 10.29,
            'I' => 9.57,
            'J' => 10.71,
            'K' => 21.29,
            'L' => 14.57,
            'M' => 10.14,
            'N' => 21.57,          
        ];
    }

    public function startCell(): string
    {
        return 'B13';
    }

    protected $selectedIds;

    // Constructor to accept selected project IDs
    public function __construct(array $selectedIds = [])
    {
        $this->selectedIds = $selectedIds;
    }

    public function collection()
{
    // Query the items instead of projects
    $items = Item::whereIn('id', $this->selectedIds)
                 ->with(['useritems']) // Eager load related useritems
                 ->select('id', 'project_id', 'name', 'description', 'quantity', 'status','voc') // Select item fields
                 ->get();
    
    $data = $items->flatMap(function ($item) {
        // Get item data
        $itemData = [
            'item_name' => $item->name,
            'item_description' => $item->description,
            'quantity' => $item->quantity,
            'status' => $item->status,
            'voc' => $item->voc,
            'project_id' => $item->project_id, // Including project_id to link back to the project
        ];

        // Map useritems related to each item
        return $item->useritems->map(function ($useritem, $index) use ($itemData) {
            return [
                'no' => $index + 1,
                'type_work' => $useritem->type_work ?? 'N/A',
                'ref' => $useritem->ref ?? 'N/A',
                // 'rev' => $useritem->revision ?? 'N/A',
                'machine_no' => $useritem->machine_no ?? 'N/A',
                'qty' => $useritem->qty ?? 0,
                // 'inspection' => $useritem->inspection ?? ' ',
                // 'operator_name' => $useritem->operator_name ?? ' ',
                // 'date' => $useritem->date ?? ' ',
                // 'record_no' => $useritem->record_no ?? ' ',
                // Add item data as additional columns
                // 'item_name' => $itemData['item_name'],
                // 'item_description' => $itemData['item_description'],
                // 'quantity' => $itemData['quantity'],
                // 'status' => $itemData['status'],
                // 'voc'=> $itemData['voc'],
                // 'project_id' => $itemData['project_id'],
            ];
        });
    });

    return collect($data);
}



public function registerEvents(): array
{
    return [
        AfterSheet::class => function (AfterSheet $event) {
            $projects = Project::with(['items.useritems'])->get();
            $sheet = $event->sheet;

            $borderStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '7F7F7F'],
                    ],
                ],
            ];
            
            // Bagian Atas - Merge & Border
            $mergeRangesAtas = [
                'B4:E5', 'F4:K4', 'F8:H8', 'I8:K8',
                'B10:N10', 'B11:B12',
                'C11:D12', 'E11:F12', 'G11:G12',
                'H11:H12', 'I11:I12', 'K11:K12', 'L11:L12', 'M11:N12',
            ];
            
            $borderRangesAtas = [
                'B4:E5', 'B6:E6', 'B7:E7', 'B8:E8', 'F4:K4', 'F5:K5', 'F6:K6', 'F7:K7', 'F8:K8',
                'L4:N4', 'L5:N5', 'L6:N6', 'L7:N7', 'L8:N8', 'B10:N10',
                'B11:B12', 'C11:D12', 'E11:F12', 'G11:G12', 'H11:H12', 'I11:I12', 'K11:K12',
                'L11:L12', 'M11:N12',
            ];
            
            // Menghitung jumlah baris data di bagian tengah
            $dataCount = $this->collection()->count();
            $rowStart = 13; // Baris awal bagian tengah
            $rowEnd = $rowStart + $dataCount - 1;
            
            // Bagian Tengah - Merge & Border (Dinamis)
            $mergeRangesTengah = [];
            $borderRangesTengah = [];
            
            for ($row = $rowStart; $row <= $rowEnd; $row++) {
                $mergeRangesTengah[] = "E{$row}:F{$row}";
                $mergeRangesTengah[] = "M{$row}:N{$row}";
                $mergeRangesTengah[] = "C{$row}:D{$row}";
                $borderRangesTengah[] = "B{$row}:N{$row}";
            }
            
            // Bagian Bawah - Merge, Nilai Sel, dan Border
            $rowStartBawah = $rowEnd + 2; // Baris awal bagian bawah (tambahkan jeda 1 baris)
            $rowEndBawah = $rowStartBawah + 3; // Bagian bawah mencakup 4 baris (29-32)
            
            // Merge Ranges Bagian Bawah
            $mergeRangesBawah = [
                "B{$rowStartBawah}:C{$rowEndBawah}", // Kolom untuk QRCode
                // "K{$rowStartBawah}:N{$rowStartBawah}", // Header Approved By
                "K" . ($rowStartBawah - 1) . ":N" . ($rowStartBawah - 1), 
                "K" . ($rowStartBawah - 1) . ":L" . ($rowStartBawah - 1), // Sub-header Department
            ];
            
            // Menambahkan Nilai ke Bagian Bawah
            $sheet->setCellValue("B" . ($rowStartBawah - 1), 'QRCode'); // QRCode dinaikkan 1 baris
            $sheet->setCellValue("D" . ($rowStartBawah - 1), 'Note :');
            $sheet->setCellValue("K". ($rowStartBawah - 1), 'Approved By');
            $sheet->getStyle("K" . ($rowStartBawah - 1))
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->setCellValue("K{$rowStartBawah}", 'Department');
            $sheet->mergeCells("K{$rowStartBawah}:L{$rowStartBawah}");
            $sheet->getStyle("K{$rowStartBawah}:L{$rowStartBawah}")
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->setCellValue("K" . ($rowStartBawah + 1), 'Engineering');
            $sheet->setCellValue("K" . ($rowStartBawah + 2), 'Quality');
            $sheet->setCellValue('K'. ($rowStartBawah + 3), 'Production Control');
            $sheet->setCellValue("M" . ($rowStartBawah), 'Sign');
            $sheet->setCellValue("N" . ($rowStartBawah), 'Date');
            $sheet->getStyle("M" . ($rowStartBawah) . ":N" . ($rowStartBawah))
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            
            // Border untuk bagian bawah
            $borderRangesBawah = [
                "B" . ($rowStartBawah - 1) . ":N" . ($rowEndBawah), // Seluruh area bawah
            ];
            
            // Merge Cells untuk Range Atas
            foreach ($mergeRangesAtas as $range) {
                $event->sheet->mergeCells($range);
            }
            
            // Apply Border untuk Range Atas
            foreach ($borderRangesAtas as $range) {
                $event->sheet->getStyle($range)->applyFromArray($borderStyle);
            }
            
            // Merge Cells untuk Range Tengah
            foreach ($mergeRangesTengah as $range) {
                $event->sheet->mergeCells($range);
            }
            
            // Apply Border untuk Range Tengah
            foreach ($borderRangesTengah as $range) {
                $event->sheet->getStyle($range)->applyFromArray($borderStyle);
            }
            
            // Merge Cells untuk Range Bawah
            foreach ($mergeRangesBawah as $range) {
                $event->sheet->mergeCells($range);
            }
            
            // Apply Border untuk Range Bawah
            foreach ($borderRangesBawah as $range) {
                $event->sheet->getStyle($range)->applyFromArray($borderStyle);
            }
            
            
            // $sheet->setCellValue("B" . ($rowStartBawah - 1), 'QRCode'); // QRCode dinaikkan 1 baris
            // $sheet->setCellValue("D" . ($rowStartBawah - 1), 'Note :');
            // $sheet->setCellValue("K". ($rowStartBawah - 1), 'Approved By');
            // $sheet->setCellValue("K{$rowStartBawah}", 'Department');
            // $sheet->setCellValue("K" . ($rowStartBawah + 1), 'Engineering');
            // $sheet->setCellValue("K" . ($rowStartBawah + 2), 'Quality');
            // $sheet->setCellValue('K'. ($rowStartBawah + 3), 'Production Control');
            // $sheet->setCellValue("M" . ($rowStartBawah + 1), 'Sign');
            // $sheet->setCellValue("N" . ($rowStartBawah + 1), 'Date');
            
            

            $headerCells = [
                'B2', 'B4', 'B10', 'B11', 'C11', 'E11', 'G11', 'H11', 'I11', 'J11', 'J12', 'K11', 
                'L11', 'M11', 'K29', 'K30', 'M30', 'N30'
            ];
            
            

            foreach ($headerCells as $cell) {
                $sheet->setCellValue($cell, $sheet->getCell($cell)->getValue()); // Reset value to preserve formatting
                $style = $sheet->getStyle($cell);
                $style->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Special alignment for B4
                if ($cell === 'B4') {
                    $style->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
                    $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                }
            }

        // Existing functionality here (if any)
        $rowStart = 13; // Row where data starts
        foreach ($this->collection() as $rowIndex => $data) {
            $currentRow = $rowStart + $rowIndex; // Menyesuaikan nomor baris

            // Mengisi nilai pada kolom yang sesuai dengan data yang ada
            $sheet->setCellValue('B' . $currentRow, $data['no']);
            $sheet->setCellValue('C' . $currentRow, $data['type_work']);
            $sheet->setCellValue('E' . $currentRow, $data['ref']);
            // $sheet->setCellValue('G' . $currentRow, $data['rev']);
            $sheet->setCellValue('H' . $currentRow, $data['machine_no']);
            $sheet->setCellValue('I' . $currentRow, $data['qty'] . 'Ea');
            // $sheet->setCellValue('J' . $currentRow, $data['inspection']);
            // $sheet->setCellValue('K' . $currentRow, $data['operator_name']);  // Menyesuaikan kolom untuk nama operator
            // $sheet->setCellValue('L' . $currentRow, $data['date']);
            // $sheet->setCellValue('M' . $currentRow, $data['record_no']);
            
            // Center align untuk kolom B, C, E, H, I
            $sheet->getStyle("B" . $currentRow)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            // $sheet->getStyle("C" . $currentRow)
            //       ->getAlignment()
            //       ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            //       ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            $sheet->getStyle("E" . $currentRow)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            $sheet->getStyle("H" . $currentRow)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            $sheet->getStyle("I" . $currentRow)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        }

        
        
        
        // Merge cells B2 to N2
        $sheet->mergeCells('B2:N2');

        // Set the value for the merged cell B2
        $sheet->setCellValue('B2', 'JOB TRAVELERS');

        // Make the text bold
        $sheet->getStyle('B2')->getFont()->setBold(true);

        // Center align the text horizontally and vertically
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $sheet->getDefaultColumnDimension()->setAutoSize(false);
        $sheet->getDefaultColumnDimension()->setWidth(15); // Set a default width for columns
        $sheet->getStyle('A1:N33')->getFont()->setName('Times New Roman');
        $sheet->getStyle('A1:N33')->getFont()->setSize(12);

        $projectIds = Item::whereIn('id', $this->selectedIds)
            ->pluck('project_id')
            ->unique();

            $projects = Project::whereIn('id', $projectIds)->get();
        
        foreach ($projects as $index => $project) {
            // Set values in the Excel sheet for each project
            $sheet->setCellValue('B4', 'Description : ' . ($project->deskripsi ?? 'N/A'));
            $sheet->setCellValue('B6', 'Job No. : ' . ($project->order ?? 'N/A'));
            $sheet->setCellValue('B7', 'Customer : ' . ($project->perusahaan ?? 'N/A'));
            $sheet->setCellValue('M6', ': ' . ($project->quantity ?? ' '));
            $sheet->setCellValue('M7', ': ' . ($project->voc ?? ' '));
            // Adjust the row/column indices as needed for each project
            // For example, if you're starting at row 5, increment the row index for each project
            // $row = 5 + $index; // Adjust row number as needed
            // $sheet->setCellValue('A' . $row, $project->id); // Example to set project ID at column A
        }
        
        $sheet->getStyle('B4')->getAlignment()->setWrapText(true);
        
        $sheet->setCellValue('B8', 'Sample :');
        $sheet->setCellValue('F4', 'Marking No. :');
        $sheet->setCellValue('H5', 'P/N :');
        $sheet->setCellValue('H6', 'S/N :');
        $sheet->setCellValue('H7', 'H/N :');
        $sheet->setCellValue('F8', 'Material :');
        $sheet->setCellValue('I8', 'Hardness :');
        $sheet->setCellValue('L4', 'Draw No.');
        $sheet->setCellValue('L5', 'Date');
        $sheet->setCellValue('L6', 'Quantity');
        $sheet->setCellValue('L7', 'VOC/PO NO');
        $sheet->setCellValue('L8', 'Delivery Date');
        $sheet->setCellValue('M4', ':');
        $sheet->setCellValue('M5', ':');
        
        $sheet->setCellValue('M8', ':');

        $sheet->setCellValue('B10', 'Production & Inspection');
        $sheet->setCellValue('B11', 'No.');
        $sheet->setCellValue('C11', 'Type Of Work');
        $sheet->setCellValue('E11', 'Reference');
        $sheet->setCellValue('G11', 'Rev.');
        $sheet->setCellValue('H11', 'Machine No.');
        $sheet->getStyle('H11')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('I11', 'Qty');
        $sheet->setCellValue('J11', 'Inspection');
        $sheet->setCellValue('J12', 'Acc / Rej');
        $sheet->setCellValue('K11', 'Sign&Name');
        $sheet->setCellValue('L11', 'Date');
        $sheet->setCellValue('M11', 'Record No.(if any)');
        // $sheet->setCellValue('B29', 'QRCode');
        // $sheet->setCellValue('B30', '');
        // $sheet->setCellValue('D29', 'Note :');
        // $sheet->setCellValue('K29', 'Approved By');
        // $sheet->setCellValue('K30', 'Department');
        // $sheet->setCellValue('K31', 'Engineering');
        // $sheet->setCellValue('K32', 'Quality');
        // $sheet->setCellValue('K33', 'Production Control');
        // $sheet->setCellValue('M30', 'Sign');
        // $sheet->setCellValue('N30', 'Date');
            
        },
    ];
}


public function drawings()
{
    // Create the drawing object for the logo
    $drawing = new Drawing();
    $drawing->setName('Logo');
    $drawing->setDescription('Company Logo');
    // Use the correct path to the public folder for the image
    $drawing->setPath(public_path('logo/LOGO_MJM_2.png'));  // Removed leading slash
    $drawing->setHeight(50);
    $drawing->setWidth(150); // Adjust this value as needed
    // $imageDrawing->setHeight(50);
    $drawing->setCoordinates('B1');

    // Return an array of drawings (even if you have only one)
    return [$drawing];
}
}
