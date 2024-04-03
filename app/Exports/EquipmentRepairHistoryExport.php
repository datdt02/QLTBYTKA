<?php

namespace App\Exports;

use App\Models\Equipment;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class EquipmentRepairHistoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    private $i = 1;
    protected $equipment_id;
    protected $totalCost = 0;
    public function __construct($equipment_id)
    {
        $this->equipment_id = $equipment_id;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $equipment = Equipment::find($this->equipment_id);
        //dd($equipment->repairHistory);
        $repairHistory = $equipment->repairHistory;
        foreach ($repairHistory as $repair) {
            $this->totalCost += ($repair->actual_costs != NULL ? $repair->actual_costs : 0);
        }
        return $repairHistory;
    }
    public function startCell(): string
    {
        return 'A12';
    }
    public function headings(): array
    {
        return [
            '# STT',
            'Mã sửa chữa',
            'Ngày báo hỏng',
            'Ngày lập kế hoạch',
            'Ngày sửa',
            'Ngày sửa xong',
            'Tình trạng',
            'Chi phí',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                setExcelHeaderAndFooter(
                    $event,
                    $this->collection()->count(),
                    'DANH SÁCH LỊCH SỬ SỬA CHỮA THIẾT BỊ',
                    'A',
                    'C',
                    'D',
                    'E',
                    12
                );
                //equipment properties on row
                $horizontalCoordinate = 'A'; // from column A to G, H, I,..
                $arrayOfEquipmentProperties = array(
                    'Tên',
                    'Model',
                    'Serial',
                    'Khoa',
                    'Ngày nhập',
                    'Ngày hết hạn bảo hành',
                    'Ngày kiểm định đầu tiên',
                    'Tình trạng sửa chữa',
                );

                $acceptance = acceptanceRepair();
                $equipment = Equipment::find($this->equipment_id);
                $data['Tên'] = $equipment->title;
                $data['Khoa'] = isset($equipment->equipment_department) ? $equipment->equipment_department->title : '';
                $data['Ngày kiểm định đầu tiên'] = $equipment->last_inspection;
                $data['Model'] = $equipment->model;
                $data['Serial'] = $equipment->serial;
                $data['Ngày nhập'] = $equipment->warehouse;
                $data['Ngày hết hạn bảo hành'] = $equipment->warranty_date;
                $data['Tình trạng sửa chữa'] = $acceptance[$equipment->schedule_repairs->sortByDesc('planning_date')->first()->acceptance];

                foreach ($arrayOfEquipmentProperties as $property) {
                    //properties name on row 9, start from cell A9
                    $event->sheet->getCell($horizontalCoordinate . '9')->setValue($property);
                    //properties value on row 10, start from cell A10
                    $event->sheet->getCell($horizontalCoordinate . '10')->setValue($data[$property]);
                    //Ex: $property = 'Tên' => $data[$property] = $data['Tên'] = $equipment->title
                    ++$horizontalCoordinate; //$horizontalCoordinate++ : ++'A' = B
                }
                $event->sheet->getStyle('9')->applyFromArray([
                    'font' => [
                        'size' => 14,
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'wrapText' => true,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
                //
                $event->sheet->getCell('H' . ($this->collection()->count() + 12 + 1))->setValue('Tổng chi phí: '.$this->totalCost);
                //Bold and set size 12
                $cellRange = 'A12:O12';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
    public function map($repair): array
    {
        $acceptance = acceptanceRepair();
        return [
            $this->i++,
            $repair->code != NULL ? $repair->code : 'NULL',
            $repair->date_failure != NULL ? $repair->date_failure : 'NULL',
            $repair->planning_date != NULL ? $repair->planning_date : 'NULL',
            $repair->repair_date != NULL ? $repair->repair_date : 'NULL',
            $repair->completed_repair != NULL ? $repair->completed_repair : 'NULL',
            $acceptance[$repair->acceptance] != NULL ? $acceptance[$repair->acceptance] : 'NULL',
            $repair->actual_costs != NULL ? convert_currency($repair->actual_costs) : '0',
        ];
    }
}
