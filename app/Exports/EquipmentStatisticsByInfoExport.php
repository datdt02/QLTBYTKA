<?php
namespace App\Exports;
use Illuminate\Support\Facades\Auth;
use App\Models\Equipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class EquipmentStatisticsByInfoExport implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $i = 1;
    protected $cate_id;
    protected $status_id;
    protected $department_id;
    protected $device_id;
    protected $key;
    public function __construct($cate_id,$status_id,$department_id,$device_id,$key) {
        $this->cate_id = $cate_id;
        $this->status_id = $status_id;
        $this->department_id = $department_id;
        $this->device_id = $device_id;
        $this->key = $key;
    }
    public function collection()
    {
        $cate_id = $this->cate_id;
        $department_id = $this->department_id;
        $device_id = $this->device_id;
        $key = $this->key;
        $cate_query = function ($query) use ($cate_id) {
            if($cate_id != ''){
                return $query->select('equipment_cates.id','equipment_cates.title')->where('equipment_cates.id',$cate_id);
            }else{
                return $query->select('equipment_cates.id','equipment_cates.title');
            }
        };
        $equipments_query = function ($query) use ($department_id) {
            if($department_id != ''){
                return $query->select('departments.id','departments.title')->where('departments.id',$department_id);
            }else{
                return $query->select('departments.id','departments.title');
            }
        };
        $device_query = function ($query) use ($device_id) {
            if($device_id != ''){
                return $query->select('devices.id','devices.title')->where('devices.id',$device_id);
            }else{
                return $query->select('devices.id','devices.title');
            }
        };
        $equipments = Equipment::query();
        if($key != ''){
            $equipments = $equipments->where(function ($query) use ($key) {
            $query->where('title','like','%'.$key.'%')
                ->orWhere('code','like','%'.$key.'%')
                ->orWhere('model','like','%'.$key.'%')
                ->orWhere('serial','like','%'.$key.'%')
                ->orWhere('manufacturer','like','%'.$key.'%')
                ->orWhere('origin','like','%'.$key.'%')
                ->orWhere('year_manufacture','like','%'.$key.'%')
                ->orWhere('year_use','like','%'.$key.'%');
            });
        }
        if($this->status_id != '') $equipments= $equipments->where('equipments.status','like','%'.$this->status_id.'%');
        if($cate_id != '') $equipments= $equipments->whereHas('equipment_cates', $cate_query);
        if($department_id != '') $equipments= $equipments->whereHas('equipment_department', $equipments_query);
        if($device_id != '') $equipments= $equipments->whereHas('equipment_device', $device_query);
        $equipments= $equipments->orderby('created_at','desc')->get();
        return $equipments;
    }
    public function headings() :array {
        return [
         "# STT",
         "Khoa",
"Mã khoa",
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
    public function map($equipment): array {
        $statusEquipments = get_statusEquipments();
        $user=Auth::user();
        if($user->can('statistical.show_all') && $user->can('statistical.info')){
            return [
                $this->i++,
                isset($equipment->equipment_department) ? $equipment->equipment_department->title : 'NULL',
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
                $equipment->amount != null ? $equipment->amount : '0',
                $equipment->import_price != null ? convert_currency($equipment->import_price) : '0',
                convert_currency($equipment->amount * $equipment->import_price),
            ];
        }else{
            return [
                $this->i++,
                isset($equipment->equipment_department) ? $equipment->equipment_department->title : 'NULL',
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
                $equipment->amount != null ? $equipment->amount : '0',
            ];
        }
    }
}
