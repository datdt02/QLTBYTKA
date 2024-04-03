<?php

namespace App\Exports;

use App\Models\Equipment;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class EquipmentStatisticsByProExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{

    /**
     * @return \Illuminate\Support\Collection
     */

    private $i = 1;
    protected $pro;
    protected $key;
    public function __construct($pro, $key)
    {
        $this->pro = $pro;
        $this->key = $key;
    }
    public function collection()
    {
        $equipments = Equipment::query();
        if ($this->key != '') $equipments = $equipments->where('equipments.title', 'like', '%' . $this->key . '%');
        if ($this->pro != '') $equipments = $equipments->where('equipments.bid_project_id', $this->pro);
        $equipments = $equipments->where('equipments.bid_project_id', '!=', null)->orderby('equipments.bid_project_id', 'asc')->get();
        return $equipments;
    }

    public function headings(): array
    {
        return [
            "# STT",
            "Dự án",
            "Khoa - Phòng",
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
            "Số lượng",
        ];
    }
    public function startCell(): string
    {
        return 'A8';
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                setExcelHeaderAndFooter(
                    $event,
                    $this->collection()->count(),
                    'THỐNG KÊ THIẾT BỊ THEO DỰ ÁN',
                    'A',
                    'C',
                    'D',
                    'E',
                    8
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
            isset($equipment->project) ? $equipment->project->title : 'NULL',
            isset($equipment->department) ? $equipment->department->title : 'NULL',
            $equipment->hash_code != null ? $equipment->hash_code : 'NULL',
            $equipment->title != null ? $equipment->title : 'NULL',
            isset($equipment->equipment_unit) ? $equipment->equipment_unit->title : 'NULL',
            $equipment->model != null ? $equipment->model : 'NULL',
            $equipment->serial != null ? $equipment->serial : 'NULL',
            $equipment->manufacturer != null ? $equipment->manufacturer : 'NULL',
            $equipment->origin != null ? $equipment->origin : 'NULL',
            $equipment->year_manufacture != null ? $equipment->year_manufacture : 'NULL',
            $equipment->year_use  != null ? $equipment->year_use : 'NULL',
            isset($statusEquipments[$equipment->status]) ? $statusEquipments[$equipment->status] : 'NULL',
            $equipment->amount != null ? $equipment->amount : 'NULL',
        ];
    }
}
