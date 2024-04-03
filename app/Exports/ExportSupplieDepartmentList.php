<?php

namespace App\Exports;

use App\Models\Eqsupplie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportSupplieDepartmentList implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents
{

    /**
    * @return \Illuminate\Support\Collection
    */

    private $i = 1;
    protected $department_id;
    protected $supplie_id;
    protected $startDate;
    protected $endDate;
    protected $key;
    public function __construct($department_id,$supplie_id,$startDate,$endDate,$key) {
        $this->department_id = $department_id;
        $this->supplie_id = $supplie_id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->key = $key;
    }
    public function collection()
    {
        $supplie_id = $this->supplie_id;
        $department_id = $this->department_id;
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $key = $this->key;
        $eqsupplies = Eqsupplie::query();  
        $supplie_query = function ($query) use ($supplie_id){
            return $query->select('supplies.id','supplies.title')->where('supplies.id',$supplie_id);  
        };
        $depart_query = function ($query) use ($department_id) {
            return $query->select('id','title')->where('departments.id', $department_id);
        };
        if($startDate == ''){
            $eqsupplies= $eqsupplies->whereDate('warehouse', '<=', $endDate);
        }else{
            $eqsupplies= $eqsupplies->whereDate('warehouse', '>=', $startDate)->whereDate('warehouse', '<=', $endDate);
        }
        if($key != '') $eqsupplies= $eqsupplies->where('equipment_supplies.title','like','%'.$key.'%');
        if($supplie_id != '') $eqsupplies= $eqsupplies->whereHas('eqsupplie_supplie', $supplie_query);
        if($department_id != '')$eqsupplies= $eqsupplies->whereHas('supplie_devices.equipment_department', $depart_query);
        $eqsupplies= $eqsupplies->orderby('supplie_id','asc')->get();
        return $eqsupplies;
    }
   
    public function headings() :array {
        return [
         "# STT",
         "Khoa - Phòng",
         "Loại VT",
         "Mã VT",
         "Tên VT",
         "ĐVT", 
         "Model", 
         "S/N",
         "Hãng XS",
         "Nước XS",
         "Đơn giá",
         "Số lượng",
         "Thành tiền",
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:M1'; 
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
            'K' => NumberFormat::FORMAT_NUMBER,
            'L' => NumberFormat::FORMAT_NUMBER,
            'M' => NumberFormat::FORMAT_NUMBER,
        ];
    }
    

    public function map($eqsupp): array {
        $arr = array();
        foreach($eqsupp->supplie_devices as $key_vp => $item){
            $arr[]= $item->equipment_department->title;
        }
        return [
            $this->i++,
            implode(", ",$arr),
            isset($eqsupp->eqsupplie_supplie) ? $eqsupp->eqsupplie_supplie->title : 'NULL',
            $eqsupp->code != null ? $eqsupp->code : 'NULL',
            $eqsupp->title != null ? $eqsupp->title : 'NULL',
            isset($eqsupp->eqsupplie_unit) ? $eqsupp->eqsupplie_unit->title : 'NULL',
            $eqsupp->model != null ? $eqsupp->model : 'NULL',
            $eqsupp->serial != null ? $eqsupp->serial : 'NULL',
            $eqsupp->manufacturer != null ? $eqsupp->manufacturer : 'NULL',
            $eqsupp->origin != null ? $eqsupp->origin : 'NULL',
            $eqsupp->import_price != null ? convert_currency($eqsupp->import_price) : '0',            
            $eqsupp->amount != null ? $eqsupp->amount : '0',
            convert_currency($eqsupp->amount * $eqsupp->import_price),            
        ];
    }


}
