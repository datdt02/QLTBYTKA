<?php

namespace App\Exports;


//include $_SERVER['DOCUMENT_ROOT'] . "\..\app\helpers\RadiationInspectionHelper.php";
if (env("APP_ENV") == "production") {
    include __DIR__ . "/../helpers/RadiationInspectionHelper.php";
} else {
    include __DIR__ . "\..\helpers\RadiationInspectionHelper.php";
}

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class RadiationInspectionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    private $index = 1;
    private $type_of_inspection;
    private $time_inspection;
    private $department_id;
    private $searchKeyword;
    private $periodic_radiation_inspection;

    /**
     * @param $type_of_inspection
     * @param $time_inspection
     * @param $department_id
     * @param $searchKeyword
     * @param $periodic_radiation_inspection
     */
    public function __construct($type_of_inspection, $time_inspection, $department_id, $searchKeyword, $periodic_radiation_inspection)
    {
        $this->type_of_inspection = $type_of_inspection;
        $this->time_inspection = $time_inspection;
        $this->department_id = $department_id;
        $this->searchKeyword = $searchKeyword;
        $this->periodic_radiation_inspection = $periodic_radiation_inspection;
    }


    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $equipments = queryEquipmentRadiationInspection($this->type_of_inspection,
            $this->time_inspection,
            $this->department_id,
            $this->searchKeyword,
            $this->periodic_radiation_inspection
        );
        return $equipments->get();
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                setExcelHeaderAndFooter($event,
                    $this->collection()->count(),
                    'DANH SÁCH TRANG THIẾT BỊ KIỂM XẠ');
                $cellRange = 'A6:O6';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }

    public function headings(): array
    {
        return [
            "# STT",
            "Khoa",
            "Mã khoa",
            "Tên TB",
            "Model",
            "Serial",
            "Mã hoá TB",
            "Năm SX",
            "Năm SD",
            "Hãng SX",
            "Nước SX",
            "Số lượng",
            "Tình trạng",
            "Chu kỳ kiểm xạ",
            "Ngày kiểm xạ gần nhất",
            "Ngày kiểm xạ tiếp theo",
        ];
    }

    public function map($equipment): array
    {
        $statusEquipments = get_statusEquipments();
        return [
            $this->index++,
            $equipment->equipment_department->title,
            $equipment->equipment_department->code,
            $equipment->title,
            $equipment->model,
            $equipment->serial,
            $equipment->hash_code,
            $equipment->year_manufacture,
            $equipment->year_use,
            $equipment->manufacturer,
            $equipment->origin,
            $equipment->amount,
            $statusEquipments[$equipment->status],
            $equipment->periodic_radiation_inspection . " tháng",
            $equipment->last_radiation_inspection,
            $equipment->next_radiation_inspection,
        ];
    }
}
