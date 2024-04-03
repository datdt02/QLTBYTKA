<?php

namespace App\Exports;

use App\Models\Equipment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class EquipmentStatisticsByJvContractExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    private $index = 1;
    protected $current_date;
    protected $searchKeyword;
    protected $departmentId;

    /**
     * @param $current_date
     * @param $searchKeyword
     * @param string $departmentId
     */
    public function __construct($current_date, $searchKeyword, string $departmentId = "")
    {
        $this->current_date = $current_date;
        $this->searchKeyword = $searchKeyword;
        $this->departmentId = $departmentId;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $equipments = Equipment::with("equipment_department", "equipment_unit");
        return $equipments->title($this->searchKeyword)->department($this->departmentId)->jvContract($this->current_date)->get();
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
                    'THỐNG KÊ TRANG THIẾT BỊ THEO HỢP ĐỒNG THUÊ MÁY, LIÊN DOANH LIÊN LIÊN KẾT');
                $cellRange = 'A6:Q6';
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
            "Số lượng",
            "Tình trạng",
            "Thời điểm bắt đầu hợp đồng",
            "Ngày bàn giao",
            "Thời điểm kết thúc hợp đồng",
            "Ngày bàn giao máy về viện",
            "Ngày thanh lý hợp đồng",
        ];
    }

    public function map($equipment): array
    {
        $statusEquipments = get_statusEquipments();
        return [
            $this->index++,
            isset($equipment->equipment_department) ? $equipment->equipment_department->title : 'NULL',
            $equipment->title != null ? $equipment->title : 'NULL',
            $equipment->model != null ? $equipment->model : 'NULL',
            $equipment->serial != null ? $equipment->serial : 'NULL',
            $equipment->hash_code != null ? $equipment->hash_code : 'NULL',
            $equipment->manufacturer,
            $equipment->origin,
            $equipment->year_manufacture,
            $equipment->year_use,
            $statusEquipments[$equipment->status] ?? 'NULL',
            "-",
            $equipment->date_delivery,
            $equipment->jv_contract_termination_date,
            "-",
            "-"
        ];
    }
}
