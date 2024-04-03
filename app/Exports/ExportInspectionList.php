<?php

namespace App\Exports;

use App\Models\Equipment;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\Exportable;

class ExportInspectionList implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{

    use Exportable;

    /**
     * @return Collection
     */

    private $index = 1;
    protected $inspec_time;
    protected $departments_key;
    protected $keyword;
    protected $inspections_key;
    protected $time_inspection;

    public function __construct($inspec_time, $departments_key, $keyword, $inspections_key, $time_inspection)
    {
        $this->inspec_time = $inspec_time;
        $this->departments_key = $departments_key;
        $this->keyword = $keyword;
        $this->inspections_key = $inspections_key;
        $this->time_inspection = $time_inspection;
    }

    public function collection()
    {

        $inspec_time = $this->inspec_time;
        $departments_key = $this->departments_key;
        $keyword = $this->keyword;
        $inspections_key = $this->inspections_key;
        $time_inspection = $this->time_inspection;
        $equipments = Equipment::query();
        if ($keyword != '') {
            $equipments = $equipments->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('code', 'like', '%' . $keyword . '%')
                    ->orWhere('model', 'like', '%' . $keyword . '%')
                    ->orWhere('serial', 'like', '%' . $keyword . '%');
            });
        };
        if ($inspections_key != '') {
            $equipments = $equipments->where('regular_inspection', $inspections_key);
        }

        if ($departments_key != '') {
            $equipments = $equipments->where('department_id', $departments_key);
        }


        $start = date_format(date_create($time_inspection), 'Y-m-d');
        $end = date("Y-m-d", strtotime("+" . "1" . "months", strtotime($start)));

        if ($inspec_time == '') {
            if ($time_inspection != '') {
                $equipments = $equipments->whereBetween('last_inspection', [$start, $end]);
            }
            $equipments = $equipments->whereNotIn('status', ['inactive',
                                                             'liquidated'])->orderBy('last_inspection', 'desc')->get();
        } else {
            if ($time_inspection != '') {
                $equipments = $equipments->whereBetween('next_inspection', [$start, $end]);
            }
            $equipments = $equipments->whereNotIn('status', ['inactive',
                                                             'liquidated'])->orderBy('next_inspection', 'desc')->get();
        }

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
