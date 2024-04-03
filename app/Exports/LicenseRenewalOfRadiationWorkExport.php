<?php

namespace App\Exports;

//include $_SERVER['DOCUMENT_ROOT'] . "\..\app\helpers\LicenseRenewalOfRadiationWorkHelper.php";

if(env("APP_ENV") == "production"){
    include __DIR__ . "/../helpers/LicenseRenewalOfRadiationWorkHelper.php";
}
else{
    include __DIR__ . "\..\helpers\LicenseRenewalOfRadiationWorkHelper.php";
}

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class LicenseRenewalOfRadiationWorkExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    private $index = 1;
    private $type_of_inspection;
    private $time_inspection;
    private $department_id;
    private $searchKeyword;
    private $period_of_license_renewal_of_radiation_work;

    /**
     * @param $type_of_inspection
     * @param $time_inspection
     * @param $department_id
     * @param $searchKeyword
     * @param $period_of_license_renewal_of_radiation_work
     */
    public function __construct($type_of_inspection, $time_inspection, $department_id, $searchKeyword, $period_of_license_renewal_of_radiation_work)
    {
        $this->type_of_inspection = $type_of_inspection;
        $this->time_inspection = $time_inspection;
        $this->department_id = $department_id;
        $this->searchKeyword = $searchKeyword;
        $this->period_of_license_renewal_of_radiation_work = $period_of_license_renewal_of_radiation_work;
    }


    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $equipments = queryEquipmentLicenseRenewalOfRadiationWork($this->type_of_inspection,
            $this->time_inspection,
            $this->department_id,
            $this->searchKeyword,
            $this->period_of_license_renewal_of_radiation_work
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
                    'DANH SÁCH TRANG THIẾT BỊ GIA HẠN GIẤY PHÉP TIẾN HÀNH CÔNG VIỆC BỨC XẠ');
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
            "Hãng SX",
            "Nước SX",
            "Năm SX",
            "Năm SD",
            "Tình trạng",
            "Chu kỳ Gia hạn giấy phép tiến hành CV bức xạ",
            "Ngày Gia hạn giấy phép tiến hành CV bức xạ gần nhất",
            "Ngày Gia hạn giấy phép tiến hành CV bức xạ tiếp theo",
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
            $statusEquipments[$equipment->status],
            $equipment->period_of_license_renewal_of_radiation_work . " tháng",
            $equipment->last_license_renewal_of_radiation_work,
            $equipment->next_license_renewal_of_radiation_work,
        ];
    }
}
