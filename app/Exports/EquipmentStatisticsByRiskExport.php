<?php

namespace App\Exports;

use App\Models\Equipment;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EquipmentStatisticsByRiskExport implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents
{

    /**
    * @return \Illuminate\Support\Collection
    */

    private $i = 1;
    protected $risk;
    protected $key;
    public function __construct($risk, $key) {
        $this->risk = $risk;
        $this->key = $key;
    }
    public function collection()
    {

        $equipments = Equipment::query();
        if($this->key !='') $equipments= $equipments->where('equipments.title','like','%'.$this->key.'%');
        if($this->risk != '') $equipments= $equipments->where('equipments.risk','like','%'.$this->risk .'%');
        $equipments = $equipments->where('equipments.risk','!=',null)->orderby('equipments.risk','asc')->get();
        return $equipments;
    }

    public function headings() :array {
        return [
         "# STT",
         "Mức độ rủi ro",
         "Nhóm TB",
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
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:O1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }


    public function map($equipment): array {
        $statusEquipments = get_statusEquipments();
        return [
            $this->i++,
            $equipment->risk ? $equipment->risk : 'NULL',
            isset($equipment->equipment_cates) ? $equipment->equipment_cates->title : 'NULL',
            $equipment->hash_code != null ? $equipment->hash_code : 'NULL',
            $equipment->title != null ? $equipment->title : 'NULL',
            isset($equipment->equipment_unit) ? $equipment->equipment_unit->title : 'NULL',
            $equipment->model != null ? $equipment->model : 'NULL',
            $equipment->serial != null ? $equipment->serial : 'NULL',
            $equipment->manufacturer != null ? $equipment->manufacturer : 'NULL',
            $equipment->origin != null ? $equipment->origin : 'NULL',
            $equipment->year_manufacture != null ? $equipment->year_manufacture : 'NULL',
            $equipment->year_use  != null ? $equipment->year_use : 'NULL',
            isset($statusEquipments[$equipment->status]) ? $statusEquipments[$equipment->status] :'NULL',
            $equipment->import_price != null ? convert_currency($equipment->import_price) : '0',
            $equipment->amount != null ? $equipment->amount : 'NULL',
            convert_currency($equipment->amount * $equipment->import_price),
        ];
    }


}
