<?php

namespace App\Exports;

use App\Models\Maintenance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportMaintenanceEquipmentList implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents
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
        $maintenances = Maintenance::query();  
        $eq_query = function ($query) use ($key) {
            return $query->select('equipments.id','equipments.title')->where('equipments.title','like','%'.$key.'%');
        };
        $depart_query = function ($query) use ($department_id) {
            return $query->select('id','title')->where('departments.id', $department_id);
        };
        if($department_id != '') $maintenances= $maintenances->whereHas('equipment.equipment_department', $depart_query);
        if($key != '') $maintenances= $maintenances->whereHas('equipment', $eq_query);
        if($startDate == '') $maintenances= $maintenances->whereDate('maintenances.start_date', '<=', $endDate);
            else $maintenances= $maintenances->whereDate('maintenances.start_date', '>=', $startDate)->whereDate('maintenances.start_date', '<=', $endDate);
        $maintenances = $maintenances->orderBy('maintenances.status','desc')->get();
        return $maintenances;
    }
   
    public function headings() :array {
        return [
         "# STT",
         "Khoa - Phòng",
         "Mã VT",
         "Tên VT",
         "Model", 
         "Tên họat động BD",
         "Tần suất thực hiện",
         "Ngày bắt đầu",
         "Nội dung",
         "Người tạo",
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:J1'; 
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
    

    public function map($main): array {
        $frequency = generate_frequency();    
        return [
            $this->i++,
            isset($main->equipment->equipment_department) ? $main->equipment->equipment_department->title : 'NULL',
            isset($main->equipment) && $main->equipment->code != null ? $main->equipment->code : 'NULL',
            isset($main->equipment) ? $main->equipment->title : 'NULL',
            isset($main->equipment) ? $main->equipment->model : 'NULL',
            $main->title != null ? $main->title : 'NULL',
            $frequency[$main->frequency],
            $main->start_date != null ? $main->start_date : 'NULL',
            $main->note != null ? $main->note : 'NULL',
            isset($main->author) ? $main->author->name : 'NULL',       
        ];
    }


}
