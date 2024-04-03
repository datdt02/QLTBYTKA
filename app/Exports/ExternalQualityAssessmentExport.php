<?php

namespace App\Exports;

//include $_SERVER['DOCUMENT_ROOT'] . "\..\app\helpers\ExternalQualityAssessmentHelper.php";
if (env("APP_ENV") == "production") {
    include __DIR__ . "/../helpers/ExternalQualityAssessmentHelper.php";
} else {
    include __DIR__ . "\..\helpers\ExternalQualityAssessmentHelper.php";
}

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class ExternalQualityAssessmentExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    private $index = 1;
    private $type_of_inspection;
    private $time_inspection;
    private $department_id;
    private $searchKeyword;
    private $period_of_external_quality_assessment;

    /**
     * @param $type_of_inspection
     * @param $time_inspection
     * @param $department_id
     * @param $searchKeyword
     * @param $period_of_external_quality_assessment
     */
    public function __construct($type_of_inspection, $time_inspection, $department_id, $searchKeyword, $period_of_external_quality_assessment)
    {
        $this->type_of_inspection = $type_of_inspection;
        $this->time_inspection = $time_inspection;
        $this->department_id = $department_id;
        $this->searchKeyword = $searchKeyword;
        $this->period_of_external_quality_assessment = $period_of_external_quality_assessment;
    }


    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $equipments = queryEquipmentExternalQualityAssessment($this->type_of_inspection,
            $this->time_inspection,
            $this->department_id,
            $this->searchKeyword,
            $this->period_of_external_quality_assessment
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
                    ' DANH SÁCH TRANG THIẾT BỊ NGOẠI KIỂM'
                );
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
            "Chu kỳ ngoại kiểm",
            "Ngày ngoại kiểm gần nhất",
            "Ngày ngoại kiểm tiếp theo",
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
            $equipment->period_of_external_quality_assessment . " tháng",
            $equipment->last_external_quality_assessment,
            $equipment->next_external_quality_assessment,
        ];
    }
}
