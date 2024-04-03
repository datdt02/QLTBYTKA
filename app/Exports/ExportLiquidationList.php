<?php

namespace App\Exports;

use App\Models\Equipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportLiquidationList implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents
{

    /**
    * @return \Illuminate\Support\Collection
    */

    private $i = 1;
    protected $departments_id;
    protected $startDate;
    protected $endDate;
    protected $key;
    public function __construct($departments_id,$startDate,$endDate,$key) {
        $this->departments_id = $departments_id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->key = $key;
    }
    public function collection()
    {
        $departments_id = $this->departments_id;
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $key = $this->key;
        $equipments = Equipment::query();
        $equipments_query = function ($query) use ($departments_id) {
            return $query->select('departments.id','departments.title')->where('departments.id',$departments_id);
        };
        $liqui_query = function ($query) use ($startDate, $endDate) {
            if($startDate == '') return $query->whereDate('liquidations.created_at', '<=', $endDate);
            else return $query->whereDate('liquidations.created_at', '>=', $startDate)->whereDate('liquidations.created_at', '<=', $endDate);
        };
        if($key != '') $equipments= $equipments->where('equipments.title','like','%'.$key.'%');
        if($departments_id != '')$equipments= $equipments->whereHas('equipment_department', $equipments_query);
        $equipments = $equipments->withCount('liquidations')->whereHas('liquidations', $liqui_query)->get();
        return $equipments;
    }

    public function headings() :array {
        return [
         "# STT",
         "Khoa - Phòng",
         "Mã hoá TB",
         "Tên TB",
         "ĐVT",
         "Model",
         "S/N",
         "Hãng SX",
         "Nước SX",
         "Số lượng",
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:K1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }


    public function map($equipment): array {
        return [
            $this->i++,
            isset($equipment->equipment_department) ? $equipment->equipment_department->title : 'NULL',
            $equipment->hash_code != null ? $equipment->hash_code : 'NULL',
            $equipment->title != null ? $equipment->title : 'NULL',
            isset($equipment->equipment_unit) ? $equipment->equipment_unit->title : 'NULL',
            $equipment->model != null ? $equipment->model : 'NULL',
            $equipment->serial != null ? $equipment->serial : 'NULL',
            $equipment->manufacturer != null ? $equipment->manufacturer : 'NULL',
            $equipment->origin != null ? $equipment->origin : 'NULL',
            isset($equipment->liquidations_count) ? $equipment->liquidations_count : '0',
        ];
    }


}
