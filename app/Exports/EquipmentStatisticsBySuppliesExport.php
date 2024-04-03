<?php

namespace App\Exports;

use App\Models\Eqsupplie;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EquipmentStatisticsBySuppliesExport implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents
{

    /**
    * @return \Illuminate\Support\Collection
    */

    private $i = 1;
    protected $department_id;
    protected $supplie_id;
    protected $key;
    public function __construct($department_id, $supplie_id, $key) {
        $this->department_id = $department_id;
        $this->supplie_id = $supplie_id;
        $this->key = $key;
    }
    public function collection()
    {
        $department_id = $this->department_id;
        $supplie_id = $this->supplie_id;
        $eqsupplies = Eqsupplie::query();  
        $supplie_query = function ($query) use ($supplie_id){
            if($supplie_id != ''){
                return $query->select('supplies.id','supplies.title')
                            ->where('supplies.id',$supplie_id);
            }else{
                return $query->select('supplies.id','supplies.title');
            }
        };
        $depart_query = function ($query) use ($department_id) {
            return $query->select('id','title')->where('departments.id', $department_id);
        }; 
        if($department_id != '') $eqsupplies= $eqsupplies->whereHas('supplie_devices.equipment_department', $depart_query);
        if($this->key != '') $eqsupplies= $eqsupplies->where('equipment_supplies.title','like','%'.$key.'%');
        $eqsupplies = $eqsupplies->with(['eqsupplie_supplie'=>$supplie_query])
                                ->whereHas('eqsupplie_supplie', $supplie_query)
                                ->orderby('equipment_supplies.supplie_id','asc')->get();
        return $eqsupplies;                        
        
    }
   
    public function headings() :array {
        return [
         "# STT",
         "Khoa - Phòng",
         "Nhóm VT",
         "Nhóm CC",
         "Mã VT",
         "Tên VT",
         "DVT", 
         "Model", 
         "S/N",
         "Hãng XS",
         "Nước XS",
         "Năm SX",
         "Năm SD",
         "Số lượng",
         "Đơn giá",
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
    

    public function map($eqsupp): array {
        $user=Auth::user();
        $arr = array();
        foreach($eqsupp->supplie_devices as $key_vp => $item){
            $arr[]= $item->equipment_department->title;
        }
        if($user->can('statistical.show_all') && $user->can('statistical.supplies')){
            return [
                $this->i++,
                implode(", ",$arr),
                isset($eqsupp->eqsupplie_supplie) ? $eqsupp->eqsupplie_supplie->title : 'NULL',
                isset($eqsupp->eqsupplie_provider) ? $eqsupp->eqsupplie_provider->title : 'NULL',
                $eqsupp->code != null ? $eqsupp->code : 'NULL',
                $eqsupp->title != null ? $eqsupp->title : 'NULL',
                isset($eqsupp->eqsupplie_unit) ? $eqsupp->eqsupplie_unit->title : 'NULL',
                $eqsupp->model != null ? $eqsupp->model : 'NULL',
                $eqsupp->serial != null ? $eqsupp->serial : 'NULL',
                $eqsupp->manufacturer != null ? $eqsupp->manufacturer : 'NULL',
                $eqsupp->origin != null ? $eqsupp->origin : 'NULL',
                $eqsupp->year_manufacture != null ? $eqsupp->year_manufacture : 'NULL',
                $eqsupp->year_use  != null ? $eqsupp->year_use : 'NULL',
                $eqsupp->amount != null ? $eqsupp->amount : 'NULL',
                $eqsupp->import_price != null ? convert_currency($eqsupp->import_price) : '0',            
                convert_currency($eqsupp->amount * $eqsupp->import_price),            
            ];
        }else{
            return [
                $this->i++,
                implode(", ",$arr),
                isset($eqsupp->eqsupplie_supplie) ? $eqsupp->eqsupplie_supplie->title : 'NULL',
                isset($eqsupp->eqsupplie_provider) ? $eqsupp->eqsupplie_provider->title : 'NULL',
                $eqsupp->code != null ? $eqsupp->code : 'NULL',
                $eqsupp->title != null ? $eqsupp->title : 'NULL',
                isset($eqsupp->eqsupplie_unit) ? $eqsupp->eqsupplie_unit->title : 'NULL',
                $eqsupp->model != null ? $eqsupp->model : 'NULL',
                $eqsupp->serial != null ? $eqsupp->serial : 'NULL',
                $eqsupp->manufacturer != null ? $eqsupp->manufacturer : 'NULL',
                $eqsupp->origin != null ? $eqsupp->origin : 'NULL',
                $eqsupp->year_manufacture != null ? $eqsupp->year_manufacture : 'NULL',
                $eqsupp->year_use  != null ? $eqsupp->year_use : 'NULL',
                $eqsupp->amount != null ? $eqsupp->amount : 'NULL',           
            ];
        }
    }


}
