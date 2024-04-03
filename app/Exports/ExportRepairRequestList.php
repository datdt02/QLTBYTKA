<?php

namespace App\Exports;

use App\Models\ScheduleRepair;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ExportRepairRequestList implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents, WithCustomStartCell
{

    /**
    * @return \Illuminate\Support\Collection
    */

    private $i = 1;
    protected $department_id;
    protected $startDate;
    protected $endDate;
    protected $key;
    public function __construct($department_id,$startDate,$endDate,$key) {
        $this->department_id = $department_id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->key = $key;
    }
    public function collection()
    {
        $department_id = $this->department_id;
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $key = $this->key;
        $repairs = ScheduleRepair::query();
        $depart_query = function ($query) use ($department_id) {
            return $query->select('departments.id','departments.title')->where('departments.id',$department_id);
        };
        $equipments_query = function ($query) use ($key) {
            return $query->select('equipments.id','equipments.title')->where('equipments.title','like','%'.$key.'%');
        };
        if($startDate == ''){
            $repairs = $repairs->whereDate('schedule_repairs.planning_date', '<=', $endDate);
        }else{
            $repairs = $repairs->whereDate('schedule_repairs.planning_date', '>=', $startDate)->whereDate('schedule_repairs.planning_date', '<=', $endDate);
        }
        if($key != '') $repairs= $repairs->whereHas('equipment', $equipments_query);
        if($department_id != '')$repairs= $repairs->whereHas('equipment.equipment_department',$depart_query);
        $repairs = $repairs->orderby('planning_date', 'asc')->get();
        return $repairs;
    }

    public function startCell(): string
    {
        return 'A8';
    }
    public function headings() :array {
        return [
         "#STT",
         "Khoa",
"Mã khoa",
         "Mã hoá TB",
         "Tên TB",
         "ĐVT",
         "Model",
         "S/N",
         "Ngày tạo",
         "Lý do hỏng",
         "Tình trạng sửa chữa",
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                setExcelHeaderAndFooter($event,
                $this->collection()->count(),
                'THỐNG KÊ YÊU CẦU SỬA CHỮA THIẾT BỊ',
                'A', 'C', 'D', 'E');
                $cellRange = 'A8:K8';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }


    public function map($repair): array
    {
        $status_acce = acceptanceRepair();
        return [
            $this->i++,
            isset($repair->equipment->equipment_department) ? $repair->equipment->equipment_department->title : 'NULL',
            isset($repair->equipment) ? $repair->equipment->hash_code : 'NULL',
            isset($repair->equipment) ? $repair->equipment->title : 'NULL',
            isset($repair->equipment->equipment_unit) ? $repair->equipment->equipment_unit->title : 'NULL',
            isset($repair->equipment) ? $repair->equipment->model : 'NULL',
            isset($repair->equipment) ? $repair->equipment->serial : 'NULL',
            $repair->planning_date,
            isset($repair->equipment) ? $repair->equipment->reason : 'NULL',
            $status_acce[$repair->acceptance],
        ];
    }


}
