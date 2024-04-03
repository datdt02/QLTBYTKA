<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ExportEquipmentInventory implements FromCollection, ShouldAutoSize, WithMapping, WithEvents, WithCustomStartCell
{

    /**
     * @return \Illuminate\Support\Collection
     */

    private $i = 1;
    protected $depart_id;
    protected $total_import_price;
    protected $total_present_value;
    protected $collection_count = 0;
    public function __construct($depart_id)
    {
        $this->depart_id = $depart_id;
        $this->total_import_price = 0;
        $this->total_present_value = 0;
    }
    public function collection()
    {
        $depart_id = $this->depart_id;
        $department = Department::findOrFail($depart_id);
        $equipments = $department->department_equipment;
        foreach ($equipments as $equipment) {
            $this->total_import_price += $equipment->import_price == null ? 0 : $equipment->import_price * $equipment->amount;
            $this->total_present_value += $equipment->amount * ($equipment->present_value == null ?
                $equipment->import_price : $equipment->import_price * $equipment->present_value / 100);
        }
        $this->collection_count = $equipments->count();
        return $equipments;
    }



    public function startCell(): string
    {
        return 'A14';
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                //A1 => A3
                $event->sheet->setCellValue('A1', 'Đơn vị:.....................................');
                $event->sheet->setCellValue('A2', 'Bộ phận:....................................');
                $event->sheet->setCellValue('A3', 'Mã đơn vị SDNS:.............................');
                $event->sheet->getStyle('A1:A3')->applyFromArray([
                    'font' => [
                        'size' => 11,
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        //'wrapText' => true,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    // 'borders' => [
                    //     'allBorders' => [
                    //         'borderStyle' => Border::BORDER_THIN,
                    //     ],
                    //],
                ]);
                //J1 => J3
                $event->sheet->setCellValue('J1', 'Mẫu số C53-HD');
                $event->sheet->setCellValue('J2', '(Ban hành kèm theo QĐ số 19/2006/QĐ-BTC)');
                $event->sheet->setCellValue('J3', 'Ngày 30/3/2006 của Bộ trưởng Bộ Tài chính)');
                $event->sheet->getStyle('J1:J3')->applyFromArray([
                    'font' => [
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $event->sheet->getStyle('J1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);
                //A5 => N5
                $event->sheet->mergeCells('A5:N5')->setCellValue('A5', 'BIÊN BẢN KIỂM KÊ TSCĐ');
                $event->sheet->getStyle('A5')->applyFromArray([
                    'font' => [
                        'size' => 12,
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                //L6
                $event->sheet->setCellValue('L6', 'Số: ............');
                $event->sheet->getStyle('L6')->applyFromArray([
                    'font' => [
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                //B7->B11
                $event->sheet->setCellValue('B7', 'Ban kiểm kê gồm:');
                $event->sheet->setCellValue('B8', '\'- Ông/Bà: ......................... Chức vụ .................. Đại diện ........................... Trưởng ban');
                $event->sheet->setCellValue('B9', '\'- Ông/Bà: ......................... Chức vụ .................. Đại diện ........................... Ủy viên');
                $event->sheet->setCellValue('B10', '\'- Ông/Bà: ......................... Chức vụ .................. Đại diện ........................... Ủy viên');
                $event->sheet->setCellValue('B11', 'Đã kiểm kê TSCĐ, kết quả như sau:');
                $event->sheet->getStyle('B7:B11')->applyFromArray([
                    'font' => [
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                //A12:A13
                $event->sheet->mergeCells('A12:A13')->setCellValue("A12", "STT");
                $event->sheet->mergeCells('B12:B13')->setCellValue("B12", "Tên tài sản cố định");
                $event->sheet->mergeCells('C12:C13')->setCellValue("C12", "Mã số TSCĐ");
                $event->sheet->mergeCells('D12:D13')->setCellValue("D12", "Nơi sử dụng");

                $event->sheet->mergeCells('E12:G12')->setCellValue("E12", "Theo sổ kế toán");
                $event->sheet->setCellValue("E13", "Số lượng");
                $event->sheet->setCellValue("F13", "Nguyên giá");
                $event->sheet->setCellValue("G13", "Giá trị còn lại");

                $event->sheet->mergeCells('H12:J12')->setCellValue("H12", "Theo sổ kiểm kê");
                $event->sheet->setCellValue("H13", "Số lượng");
                $event->sheet->setCellValue("I13", "Nguyên giá");
                $event->sheet->setCellValue("J13", "Giá trị còn lại");

                $event->sheet->mergeCells('K12:M12')->setCellValue("K12", "Chênh lệch");
                $event->sheet->setCellValue("K13", "Số lượng");
                $event->sheet->setCellValue("L13", "Nguyên giá");
                $event->sheet->setCellValue("M13", "Giá trị còn lại");

                $event->sheet->mergeCells('N12:N13')->setCellValue("N12", "Ghi chú");

                $event->sheet->getStyle('A12:N' . (13 + $this->collection_count + 1))->applyFromArray([
                    'font' => [
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                $event->sheet->setCellValue("B" . (13 + $this->collection_count + 1), "Cộng");
                $event->sheet->setCellValue("F" . (13 + $this->collection_count + 1), convert_currency($this->total_import_price));
                $event->sheet->setCellValue("G" . (13 + $this->collection_count + 1), convert_currency($this->total_present_value));

                $event->sheet->setCellValue('A'. (13 + $this->collection_count + 3), '                      Thủ trưởng đơn vị                                             Kế toán trưởng                                              Trưởng ban kiểm kê');
                $event->sheet->getStyle('A'. (13 + $this->collection_count + 3))->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                ]);

                $event->sheet->setCellValue('A'. (13 + $this->collection_count + 4), '                (Ý kiến giải quyết số chênh lệch)                                   (Ký, họ tên)                                                          (Ký, họ tên)      ');
                $event->sheet->setCellValue('A'. (13 + $this->collection_count + 5), '                   (Ký, họ tên, đóng dấu)         ');

            },
        ];
    }

    public function map($equipment): array
    {
        $inventory = $equipment->inventories->sortByDesc('date')->first();
        return [
            $this->i++,
            $equipment->title,
            $equipment->hash_code,
            isset($equipment->equipment_department) ? $equipment->equipment_department->title : '-',
            $equipment->amount,
            convert_currency($equipment->import_price),
            convert_currency($equipment->present_value == null ? $equipment->import_price : $equipment->import_price * $equipment->present_value  / 100),
            '',
            '',
            '',
            '',
            '',
            '',
            isset($inventory) && $inventory->note != '' ? $inventory->note : '-',

            // isset($equipment->equipment_department) ? $equipment->equipment_department->title : '-',
            // $equipment->hash_code,
            // $equipment->title,
            // isset($inventory) && $inventory->date != '' ? $inventory->date : '-' ,
            // isset($inventory) && $inventory->note != '' ? $inventory->note : '-' ,
            // isset($inventory) && $inventory->date != '' ? 'Đã kiểm' : 'Chưa kiểm' ,
            // isset($equipment->equipment_department) && $equipment->equipment_department->browser != '' ? 'Đã duyệt' : 'Chưa duyệt',
        ];
    }
}
