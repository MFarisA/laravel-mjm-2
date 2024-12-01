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
                'rev' => $useritem->revision ?? 'N/A',
                'machine_no' => $useritem->machine_no ?? 'N/A',
                'qty' => $useritem->qty ?? 0,
                'inspection' => $useritem->inspection ?? ' ',
                'operator_name' => $useritem->operator_name ?? ' ',
                'date' => $useritem->date ?? ' ',
                'record_no' => $useritem->record_no ?? ' ',
                // Add item data as additional columns
                'item_name' => $itemData['item_name'],
                'item_description' => $itemData['item_description'],
                'quantity' => $itemData['quantity'],
                'status' => $itemData['status'],
                'voc'=> $itemData['voc'],
                'project_id' => $itemData['project_id'],
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

                $mergeRanges = [
                    // bagian atas
                    'B4:E5', 'F4:K4', 'F8:H8', 'I8:K8',
                    'B10:N10', 'B11:B12',
                    'C11:D12', 'E11:F12', 'G11:G12',
                    'H11:H12', 'I11:I12', 'K11:K12', 'L11:L12', 'M11:N12', 
                     
                    // tengah
                    'E13:F13', 'E14:F14', 'E15:F15',
                    'E16:F16', 'E17:F17', 'E18:F18', 'E19:F19', 'E20:F20', 'E21:F21', 'E22:F22',
                    'E23:F23', 'E24:F24', 'E25:F25', 'E26:F26', 'E27:F27', 'E28:F28',
                    'M13:N13', 'M14:N14',
                    'M15:N15', 'M16:N16', 'M17:N17', 'M18:N18', 'M19:N19', 'M20:N20', 'M21:N21',
                    'M22:N22', 'M23:N23', 'M24:N24', 'M25:N25', 'M26:N26', 'M27:N27', 'M28:N28',

                    'C13:D13', 'C14:D14', 'C15:D15', 'C16:D16', 'C17:D17', 'C18:D18',
                    'C19:D19', 'C20:D20', 'C21:D21', 'C22:D22', 'C23:D23', 'C24:D24', 'C25:D25', 
                    'C26:D26', 'C27:D27', 'C28:D28',
                    // bawah
                    'B30:C33','K29:N29', 'K30:L30',
                ];

                foreach ($mergeRanges as $range) {
                    $event->sheet->mergeCells($range);
                }

                // Define border style
                $borderStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '7F7F7F'],
                        ],
                    ],
                ];

                // Apply borders to each range
                $borderRanges = [
                    'B4:E5', 'B6:E6', 'B7:E7', 'B8:E8', 'F4:K4', 'F5:K5', 'F6:K6', 'F7:K7', 'F8:K8',
                    'L4:N4', 'L5:N5', 'L6:N6', 'L7:N7', 'L8:N8', 'B10:N10',
                    'B11:B12', 'C11:D12', 'E11:F12', 'G11:G12', 'H11:H12', 'I11:I12', 'K11:K12',
                    'L11:L12', 'M11:N12', 
                    
                    'B13:B28', 'C13:D28', 'E13:F28', 'G13:G28', 'H13:H28',
                    'I13:I28', 'J13:J28', 'K13:K28', 'L13:L28', 'M13:N28', 
                    
                    'B29:N33', 'K30:L30',
                    'M30', 'N30', 'K31:L33', 'M31:M33', 'N31:N33',
                ];

                $headerCells = [
                    'B2', 'B4', 'B10', 'B11', 'C11', 'E11', 'G11', 'H11', 'I11', 'J11', 'J12', 'K11', 
                    'L11', 'M11', 'K29', 'K30', 'M30', 'N30'
                ];
                
                foreach ($borderRanges as $range) {
                    $event->sheet->getStyle($range)->applyFromArray($borderStyle);
                }

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
                $sheet->setCellValue('G' . $currentRow, $data['rev']);
                $sheet->setCellValue('H' . $currentRow, $data['machine_no']);
                $sheet->setCellValue('I' . $currentRow, $data['qty']);
                $sheet->setCellValue('J' . $currentRow, $data['inspection']);
                $sheet->setCellValue('K' . $currentRow, $data['operator_name']);  // Menyesuaikan kolom untuk nama operator
                $sheet->setCellValue('L' . $currentRow, $data['date']);
                $sheet->setCellValue('M' . $currentRow, $data['record_no']);
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
            $sheet->setCellValue('B29', 'QRCode');
            $sheet->setCellValue('B30', '');
            $sheet->setCellValue('D29', 'Note :');
            $sheet->setCellValue('K29', 'Approved By');
            $sheet->setCellValue('K30', 'Department');
            $sheet->setCellValue('K31', 'Engineering');
            $sheet->setCellValue('K32', 'Quality');
            $sheet->setCellValue('K33', 'Production Control');
            $sheet->setCellValue('M30', 'Sign');
            $sheet->setCellValue('N30', 'Date');
                
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
