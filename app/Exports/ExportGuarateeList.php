<?php

namespace App\Exports;

use App\Models\Equipment;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportGuarateeList implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents
{

    /**
    * @return \Illuminate\Support\Collection
    */

    private $i = 1;
    protected $time_guarantee;
    protected $departments_key;
    protected $status;
    protected $key;
    protected $cate_key;
    protected $device_key;

    public function __construct($time_guarantee,$departments_key,$status,$key, $cate_key,$device_key) {
        $this->time_guarantee = $time_guarantee;
        $this->departments_key = $departments_key;
        $this->status = $status;
        $this->cate_key = $cate_key;
        $this->device_key = $device_key;
        $this->key = $key;
    }
    public function collection(){

        $time_guarantee = $this->time_guarantee;
        $departments_key = $this->departments_key;
        $status = $this->status;
        $cate_key = $this->cate_key;
        $device_key = $this->device_key;
        $key = $this->key;
        $equipments = Equipment::query();
        if($key != ''){
            $equipments = $equipments->where(function ($query) use ($key) {
            $query->where('title','like','%'.$key.'%')
                ->orWhere('code','like','%'.$key.'%')
                ->orWhere('model','like','%'.$key.'%')
                ->orWhere('serial','like','%'.$key.'%');
            });
        };
        if($status != ''){
            $equipments = $equipments->where('cate_key',$status);
        }

        if($cate_key != ''){
            $equipments = $equipments->where('cate_id',$cate_key);
        }
        if($device_key != ''){
            $equipments = $equipments->where('devices_id',$device_key);
        }
        if($departments_key != ''){
            $equipments = $equipments->where('department_id',$departments_key);
        }


         $start = date_format(date_create($time_guarantee), 'Y-m-d');
         $end =  date("Y-m-d", strtotime("+"."1"."months", strtotime($start)));


        if($time_guarantee != ''){
            $equipments = $equipments->whereBetween('warranty_date',[$start , $end]);
        }
        $equipments = $equipments->whereNotIn('status',['inactive','liquidated'])->orderBy('created_at', 'desc')->latest()->paginate(15);

        return $equipments;
    }

    public function headings() :array {
        return [
            "# STT",
            "Ngày hết hạn bảo hành",
            "Tên TB",
            "Mã hoá TB",
            "Model",
            "S/N",
            "Năm SX",
            "Năm SD",
            "Tình trạng",
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:I1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }


    public function map($equipment): array {
        $statusEquipments = get_statusEquipments();
        return [
            $this->i++,
            $equipment->title != null ? $equipment->title : 'NULL',
            $equipment->warranty_date != null ? date('d-m-Y', strtotime($equipment->warranty_date)): '',
            $equipment->hash_code != null ? $equipment->hash_code : 'NULL',
            $equipment->model != null ? $equipment->model : 'NULL',
            $equipment->serial != null ? $equipment->serial : 'NULL',
            $equipment->year_manufacture != null ? $equipment->year_manufacture : 'NULL',
            $equipment->year_use  != null ? $equipment->year_use : 'NULL',
            isset($statusEquipments[$equipment->status]) ? $statusEquipments[$equipment->status] :''
        ];
    }


}
