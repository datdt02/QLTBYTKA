<?php

namespace App\Exports;

use App\Models\Equipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportDeviceImportList implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents
{

    /**
    * @return \Illuminate\Support\Collection
    */

    private $i = 1;
    protected $departments_id;
    protected $provider_id;
    protected $startDate;
    protected $endDate;
    protected $key;
    public function __construct($departments_id,$provider_id,$startDate,$endDate,$key) {
        $this->departments_id = $departments_id;
        $this->provider_id = $provider_id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->key = $key;
    }
    public function collection()
    {
        $equipments = Equipment::query();
        $departments_id = $this->departments_id;
        $provider_id = $this->provider_id;
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $key = $this->key;
        $equipments_query = function ($query) use ($departments_id) {
            if($departments_id != ''){
                return $query->select('departments.id','departments.title')->where('departments.id',$departments_id);
            }else{
                return $query->select('departments.id','departments.title');
            }
        };
        $provider_query = function ($query) use ($provider_id) {
            if($provider_id != ''){
                return $query->select('providers.id','providers.title')->where('providers.id',$provider_id);
            }else{
                return $query->select('providers.id','providers.title');
            }
        };
        if($startDate == ''){
            $equipments= $equipments->whereDate('warehouse', '<=', $endDate);
        }else{
            $equipments= $equipments->whereDate('warehouse', '>=', $startDate)->whereDate('warehouse', '<=', $endDate);
        }
        if($key != '') $equipments= $equipments->where('equipments.title','like','%'.$key.'%');
        $equipments= $equipments->whereHas('equipment_department', $equipments_query);
        if($provider_id != '')$equipments= $equipments->whereHas('equipment_provider', $provider_query);
        $equipments= $equipments->orderby('department_id','asc')->get();
        return $equipments;
    }

    public function headings() :array {
        return [
         "# STT",
         "Khoa",
"Mã khoa",
         "Mã hoá TB",
         "Tên TB",
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
            $equipment->import_price != null ? convert_currency($equipment->import_price) : '0',
            $equipment->amount != null ? $equipment->amount : '0',
            convert_currency($equipment->amount * $equipment->import_price),
        ];
    }


}
