<?php

namespace App\Exports;

use App\Models\Equipment;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EquipmentStatisticsByGroupExport implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents
{

    /**
    * @return \Illuminate\Support\Collection
    */
    private $i = 1;
    protected $cate_id;
    protected $key;
    public function __construct($cate_id, $key) {
        $this->cate_id = $cate_id;
        $this->key = $key;
    }
    public function collection()
    {
        $cate_id = $this->cate_id;
        $equipments_query = function ($query) use ($cate_id) {
            if($cate_id != ''){
                return $query->select('equipment_cates.id','equipment_cates.title')
                            ->where('equipment_cates.id',$cate_id);
            }else{
                return $query->select('equipment_cates.id','equipment_cates.title');
            }
        };
        $equipments = Equipment::query();
        if($this->key !='') $equipments= $equipments->where('equipments.title','like','%'.$this->key.'%');
        $equipments = $equipments->with(['equipment_cates'=>$equipments_query])
                            ->has('equipment_cates')
                            ->whereHas('equipment_cates', $equipments_query)
                            ->orderby('equipments.cate_id','asc')->get();
        return $equipments;
    }

    public function headings() :array {
        return [
         "# STT",
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
         "Trạng thái",
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
