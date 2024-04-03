<?php

namespace App\Exports;

use App\Models\Equipment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class EquipmentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{

    /**
     * @return \Illuminate\Support\Collection
     */

    private $i = 1;
    protected $departments_id;
    protected $device_id;
    protected $cate_id;
    protected $status_id;
    protected $key;

    public function __construct($departments_id, $key, $cate_id, $device_id, $status_id)
    {
        $this->departments_id = $departments_id;
        $this->cate_id = $cate_id;
        $this->device_id = $device_id;
        $this->status_id = $status_id;
        $this->key = $key;
    }

    public function collection()
    {
        $departments_id = $this->departments_id;
        $cate_id = $this->cate_id;
        $device_id = $this->device_id;
        $status_id = $this->status_id;
        $key = $this->key;

        $departments_query = function ($query) use ($departments_id) {
            if ($departments_id != '') {
                return $query->select('departments.id', 'departments.title')->where('departments.id', $departments_id);
            } else {
                return $query->select('departments.id', 'departments.title');
            }
        };

        $cate_query = function ($query) use ($cate_id) {
            if ($cate_id != '') {
                return $query->select('equipment_cates.id', 'equipment_cates.title')->where('equipment_cates.id', $cate_id);
            } else {
                return $query->select('equipment_cates.id', 'equipment_cates.title');
            }
        };

        $device_query = function ($query) use ($device_id) {
            if ($device_id != '') {
                return $query->select('devices.id', 'devices.title')->where('devices.id', $device_id);
            } else {
                return $query->select('devices.id', 'devices.title');
            }
        };

        $equipments = Equipment::query();

        if ($key != '') {
            $equipments = $equipments->where(function ($query) use ($key) {
                $query->where('title', 'like', '%' . $key . '%')
                    ->orWhere('code', 'like', '%' . $key . '%')
                    ->orWhere('model', 'like', '%' . $key . '%')
                    ->orWhere('serial', 'like', '%' . $key . '%')
                    ->orWhere('manufacturer', 'like', '%' . $key . '%')
                    ->orWhere('origin', 'like', '%' . $key . '%')
                    ->orWhere('year_manufacture', 'like', '%' . $key . '%')
                    ->orWhere('year_use', 'like', '%' . $key . '%');
            });
        }

        if ($status_id != '') $equipments = $equipments->where('equipments.status', 'like', '%' . $status_id . '%');
        if ($departments_id != '') $equipments = $equipments->whereHas('equipment_department', $departments_query);
        if ($cate_id != '') $equipments = $equipments->whereHas('equipment_cates', $cate_query);
        if ($device_id != '') $equipments = $equipments->whereHas('equipment_device', $device_query);
        $equipments = $equipments->orderby('created_at', 'desc')->get();
        return $equipments;
    }


    public function headings(): array
    {
        return [
            "# STT",
            "Tên thiết bị",
            "Mã hoá TB",
            "Model",
            "Số serial",
            "Mã hóa TB",
            "Hãng sản xuất",
            "Xuất xứ",
            "Năm sản xuất",
            "Năm sử dụng",
            "Ngày nhập kho",
            "Ngày bàn giao",
            "Số lượng",
            "Khoa - Phòng Ban",
            "Mã khoa",
            "Giá trị hiện tại",
            "Dự án",
            "Ghi chú",
            "Tình trạng sử dụng",
            "Giá trị ban đầu",
            "Khấu hao hằng năm",
            "Thông số kỹ thuật",
            "Cấu hình kỹ thuật",
            "Kiểm định định kỳ",
            "Giá nhập",
            "Đơn vị tính",
            "Nhóm thiết bị",
            "Loại thiết bị",
            "Nhà cung cấp",
            "Đơn vị sửa chữa",
            "Mức độ rủi ro",
            "Đơn vị bảo trì",
            "Ngày kiểm định gần nhất",
            "Quy trình sử dụng",
            "CB phòng VT phụ trách",
            "CB sử dụng",
            "CB khoa phòng phụ trách",
            "CB được đào tạo",
            "Ngày hết hạn bảo hành",
            "Ngày nhập thông tin",
            "Người nhập",
            "Kiểm xạ lần cuối",
            "Chu kỳ kiểm xạ",
            "Ngoại kiểm lần cuối",
            "Chu kỳ Ngoại kiểm",
            "Kiểm định môi trường phòng  lần cuối",
            "chu kỳ Kiểm định môi trường phòng ",
            "Gia hạn giây phép tiến hành CV bức xạ lần cuối",
            "Chu kỳ Gia hạn giây phép tiến hành CV bức xạ",
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:AM1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }


    public function map($equipments): array
    {

        $statusEquipments = get_statusEquipments();
        $get_statusRisk = get_statusRisk();

        $user_use = '';
        $user_training = '';

        foreach ($equipments->equipment_user_use as $number_user_use => $equipment_user_use) {
            if ($number_user_use != 0) $user_use .= ', ' . $equipment_user_use->name;
            else $user_use .= $equipment_user_use->name;
        }

        foreach ($equipments->equipment_user_training as $number_user_training => $equipment_user_training) {
            if ($number_user_training != 0) $user_training .= ', ' . $equipment_user_training->name;
            else $user_training .= $equipment_user_training->name;
        }


        return [
            $this->i++,
            isset($equipments->title) ? $equipments->title : 'NULL',
            $equipments->hash_code,
            $equipments->model,
            $equipments->serial,
            $equipments->hash_code,
            $equipments->manufacturer,
            $equipments->origin,
            $equipments->year_manufacture,
            $equipments->year_use,
            $equipments->warehouse,
            $equipments->date_delivery,
            $equipments->amount,
            isset($equipments->equipment_department->title) ? $equipments->equipment_department->title : 'NULL',
            $equipments->equipment_department->code,
            $equipments->present_value,
            isset($equipments->project->title) ? $equipments->project->title : 'NULL',
            $equipments->note,
            isset($statusEquipments[$equipments->status]) ? $statusEquipments[$equipments->status] : 'NULL',
            $equipments->first_value,
            $equipments->depreciat,
            $equipments->specificat,
            $equipments->configurat,
            $equipments->regular_inspection,
            $equipments->import_price,
            isset($equipments->equipment_unit->title) ? $equipments->equipment_unit->title : 'NULL',
            isset($equipments->equipment_cates->title) ? $equipments->equipment_cates->title : 'NULL',
            isset($equipments->equipment_device->title) ? $equipments->equipment_device->title : 'NULL',
            isset($equipments->equipment_provider->title) ? $equipments->equipment_provider->title : 'NULL',
            isset($equipments->equipment_repair->title) ? $equipments->equipment_repair->title : 'NULL',
            isset($get_statusRisk[$equipments->risk]) ? $get_statusRisk[$equipments->risk] : 'NULL',
            isset($equipments->equipment_maintenance->title) ? $equipments->equipment_maintenance->title : 'NULL',
            $equipments->last_inspection,
            $equipments->process,
            isset($equipments->equipment_user_charge->name) ? $equipments->equipment_user_charge->name : 'NULL',
            $user_use,
            isset($equipments->equipment_user_department_charge->name) ? $equipments->equipment_user_department_charge->name : 'NULL',
            $user_training,
            $equipments->warranty_date,
            $equipments->first_information,
            isset($equipments->equipment_user->name) ? $equipments->equipment_user->name : 'NULL',
            $equipments->last_radiation_inspection,
            $equipments->periodic_radiation_inspection,
            $equipments->last_external_quality_assessment,
            $equipments->period_of_external_quality_assessment,
            $equipments->last_clinic_environment_inspection,
            $equipments->period_of_clinic_environment_inspection,
            $equipments->last_license_renewal_of_radiation_work,
            $equipments->period_of_license_renewal_of_radiation_work,
        ];
    }


}
