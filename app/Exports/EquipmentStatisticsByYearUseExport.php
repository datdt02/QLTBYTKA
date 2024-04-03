<?php

namespace App\Exports;

use App\Models\Equipment;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EquipmentStatisticsByYearUseExport implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents
{

    /**
    * @return \Illuminate\Support\Collection
    */

    private $i = 1;
    protected $year;
    protected $use_manu;
    protected $key;
    public function __construct($year, $use_manu, $key) {
        $this->year = $year;
        $this->use_manu = $use_manu;
        $this->key = $key;
    }
    public function collection(){

        $year = $this->year;
        $use_manu = $this->use_manu;
        $key = $this->key;
        $equipments = Equipment::query();
        if($key !='') $equipments= $equipments->where('equipments.title','like','%'.$key.'%');
        if($year !=''){
            if($use_manu != '') $equipments= $equipments->where('equipments.year_use', $use_manu);
            $equipments = $equipments->where('equipments.year_use','!=', null)->orderby('equipments.year_use','asc')->get();
        }else{
            if($use_manu != '') $equipments= $equipments->where('equipments.year_manufacture', $use_manu);
            $equipments = $equipments->where('equipments.year_manufacture','!=', null)->orderby('equipments.year_manufacture','asc')->get();
        }
        return $equipments;
    }

    public function headings() :array {
        return [
         "# STT",
         "Năm",
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
                $cellRange = 'A1:P1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }


    public function map($equipment): array {
        $statusEquipments = get_statusEquipments();
        if($this->year != ''){
            return [
                $this->i++,
                $equipment->year_use ? $equipment->year_use : 'NULL',
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
        }else{
            return [
                $this->i++,
                $equipment->year_manufacture ? $equipment->year_manufacture : 'NULL',
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


}
