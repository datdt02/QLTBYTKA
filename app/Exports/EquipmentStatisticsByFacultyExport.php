<?php

namespace App\Exports;

use App\Models\Equipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class EquipmentStatisticsByFacultyExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $i = 1;
    protected $department_id;
    protected $status_id;
    protected $key;

    public function __construct($department_id, $status_id, $key)
    {
        $this->department_id = $department_id;
        $this->status_id = $status_id;
        $this->key = $key;
    }

    public function collection()
    {
        $department_id = $this->department_id;
        $equipments_query = function ($query) use ($department_id) {
            if ($department_id != '') {
                return $query->select('departments.id', 'departments.title')->where('departments.id', $department_id);
            }
            else {
                return $query->select('departments.id', 'departments.title');
            }
        };
        $equipments = Equipment::query();
        if ($this->key != '') $equipments = $equipments->where('equipments.title', 'like', '%' . $this->key . '%');
        if ($this->status_id != '') $equipments = $equipments->where('equipments.status', 'like', '%' . $this->status_id . '%');
        $equipments =
            $equipments->with(['equipment_department' => $equipments_query])->has('equipment_department')->whereHas(
                    'equipment_department',
                    $equipments_query
                )->orderby('equipments.department_id', 'asc')->get();
        return $equipments;
    }

    public function startCell(): string
    {
        return 'A8';
    }

    public function headings(): array
    {
        return [
            "# STT",
            "Khoa",
            "Mã khoa",
            "Mã hoá TB",
            "Tên TB",
            "DVT",
            "Model",
            "S/N",
            "Hãng XS",
            "Nước XS",
            "Năm SX",
            "Năm SD",
            "Tình trạng",
            "Đơn giá",
            "Số lượng",
            "Thành tiền",
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                setExcelHeaderAndFooter(
                    $event,
                    $this->collection()->count(),
                    'THỐNG KÊ THIẾT BỊ THEO KHOA PHÒNG',
                    'A',
                    'C',
                    'D',
                    'E'
                );
                $cellRange = 'A8:P8';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }

    public function map($equipment): array
    {
        $statusEquipments = get_statusEquipments();
        return [
            $this->i++,
            isset($equipment->equipment_department) ? $equipment->equipment_department->title : 'NULL',
            isset($equipment->equipment_department) ? $equipment->equipment_department->code : 'NULL',
            $equipment->hash_code != null ? $equipment->hash_code : 'NULL',
            $equipment->title != null ? $equipment->title : 'NULL',
            isset($equipment->equipment_unit) ? $equipment->equipment_unit->title : 'NULL',
            $equipment->model != null ? $equipment->model : 'NULL',
            $equipment->serial != null ? $equipment->serial : 'NULL',
            $equipment->manufacturer != null ? $equipment->manufacturer : 'NULL',
            $equipment->origin != null ? $equipment->origin : 'NULL',
            $equipment->year_manufacture != null ? $equipment->year_manufacture : 'NULL',
            $equipment->year_use != null ? $equipment->year_use : 'NULL',
            isset($statusEquipments[$equipment->status]) ? $statusEquipments[$equipment->status] : 'NULL',
            $equipment->import_price != null ? convert_currency($equipment->import_price) : '0',
            $equipment->amount != null ? $equipment->amount : '0',
            convert_currency($equipment->amount * $equipment->import_price),
        ];
    }
}
