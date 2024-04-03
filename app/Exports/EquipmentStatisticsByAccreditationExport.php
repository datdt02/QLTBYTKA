<?php

namespace App\Exports;

use App\Models\Equipment;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class EquipmentStatisticsByAccreditationExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{

    /**
     * @return \Illuminate\Support\Collection
     */

    private $index = 1;
    protected $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function collection()
    {
        $current_month = Carbon::now()->toDateString();
        $equipments = Equipment::query();
        if ($this->key != '') $equipments = $equipments->where('equipments.title', 'like', '%' . $this->key . '%');
        $equipments =
            $equipments->whereRaw('TIMESTAMPDIFF(MONTH, last_inspection, "' . $current_month . '")%regular_inspection = 0')->where(
                    'equipments.last_inspection',
                    '!=',
                    null
                )->orderby('equipments.last_inspection', 'asc')->get();
        return $equipments;
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
            "Chu kỳ kiểm định",
            "Ngày kiểm định gần nhất",
            "Ngày kiểm định tiếp theo",
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                setExcelHeaderAndFooter(
                    $event,
                    $this->collection()->count(),
                    " DANH SÁCH TRANG THIẾT BỊ KIỂM ĐỊNH"
                );
                $cellRange = 'A6:O6';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
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
            $equipment->regular_inspection . " tháng",
            $equipment->last_inspection,
            $equipment->next_inspection,
        ];
    }


    public function startCell(): string
    {
        return 'A6';
    }
}
