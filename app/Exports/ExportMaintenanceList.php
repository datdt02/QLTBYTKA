<?php

namespace App\Exports;

use App\Models\Equipment;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportMaintenanceList implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents
{

    /**
    * @return \Illuminate\Support\Collection
    */

    private $i = 1;
    protected $departments_key;
    protected $keyword;
    protected $status;
    protected $time_nextMainte;
    protected $cates_key;
    protected $devices_key;


    public function __construct($keyword,$departments_key,$status, $cates_key,$devices_key, $time_nextMainte) {
        $this->time_nextMainte = $time_nextMainte;
        $this->departments_key = $departments_key;
        $this->keyword = $keyword;
        $this->status = $status;
        $this->cates_key = $cates_key;
        $this->devices_key = $devices_key;
    }
    public function collection(){

        $time_nextMainte = $this->time_nextMainte;
        $departments_key = $this->departments_key;
        $keyword = $this->keyword;
        $status = $this->status;
        $cates_key = $this->cates_key;
        $devices_key = $this->devices_key;
        $equipments = Equipment::query();
        if($keyword != ''){
            $equipments = $equipments->where(function ($query) use ($keyword) {
            $query->where('title','like','%'.$keyword.'%')
                ->orWhere('code','like','%'.$keyword.'%')
                ->orWhere('model','like','%'.$keyword.'%')
                ->orWhere('serial','like','%'.$keyword.'%');
            });
        };
        if($status != '') {
            $equipments = $equipments->where('status',$status);
        }

        if($cates_key != '') {
            $equipments = $equipments->where('cate_id',$cates_key);
        }
        if($devices_key != ''){
            $equipments = $equipments->where('devices_id',$cates_key);
        }

        if($departments_key != ''){
            $equipments = $equipments->where('department_id',$departments_key);
        }


         $start = date_format(date_create($time_nextMainte), 'Y-m-d');
         $end =  date("Y-m-d", strtotime("+"."1"."months", strtotime($start)));



        if($time_nextMainte != ''){
            $equipments = $equipments->whereBetween('next_maintenance',[$start , $end]);
        }
        $equipments = $equipments->whereNotIn('status',['inactive','liquidated'])->orderBy('next_maintenance', 'desc')->get();


        return $equipments;
    }

    public function headings() :array {
        return [
            "# STT",
            "Bảo dưỡng điểm kỳ",
            "Thời gian bảo dưỡng gần nhất",
            "Thời gian bảo dưỡng tiếp theo",
            "Tên TB",
            "Mã hoá TB",
            "Model",
            "S/N",
            "Hãng XS",
            "Nước XS",
            "Năm SX",
            "Năm SD",
            "Tình trạng",
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


    public function map($equipment): array {
        $statusEquipments = get_statusEquipments();
        return [
            $this->i++,
            $equipment->regular_maintenance.' tháng',
            $equipment->last_maintenance != null ? date('d-m-Y', strtotime($equipment->last_maintenance)): '',
            $equipment->next_maintenance != null ? date('d-m-Y', strtotime($equipment->next_maintenance)): '',
            $equipment->title != null ? $equipment->title : 'NULL',
            $equipment->hash_code != null ? $equipment->hash_code : 'NULL',
            $equipment->model != null ? $equipment->model : 'NULL',
            $equipment->serial != null ? $equipment->serial : 'NULL',
            $equipment->manufacturer != null ? $equipment->manufacturer : 'NULL',
            $equipment->origin != null ? $equipment->origin : 'NULL',
            $equipment->year_manufacture != null ? $equipment->year_manufacture : 'NULL',
            $equipment->year_use  != null ? $equipment->year_use : 'NULL',
            isset($statusEquipments[$equipment->status]) ? $statusEquipments[$equipment->status] :''
        ];
    }


}
