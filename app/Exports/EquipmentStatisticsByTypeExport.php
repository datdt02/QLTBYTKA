<?php

namespace App\Exports;

use App\Models\Equipment;

use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class EquipmentStatisticsByTypeExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{

    /**
     * @return \Illuminate\Support\Collection
     */
    private $i = 1;
    protected $device_id;
    protected $key;
    public function __construct($device_id, $key)
    {
        $this->device_id = $device_id;
        $this->key = $key;
    }
    public function collection()
    {
        $device_id = $this->device_id;
        $equipments_query = function ($query) use ($device_id) {
            if ($device_id != '') {
                return $query->select('devices.id', 'devices.title')
                    ->where('devices.id', $device_id);
            } else {
                return $query->select('devices.id', 'devices.title');
            }
        };
        $equipments = Equipment::query();
        if ($this->key != '') $equipments = $equipments->where('equipments.title', 'like', '%' . $this->key . '%');
        $equipments = $equipments->with(['equipment_device' => $equipments_query])
            ->has('equipment_device')
            ->whereHas('equipment_device', $equipments_query)
            ->orderby('equipments.devices_id', 'asc')->get();
        return $equipments;
    }
    public function startCell(): string
    {
        return 'A8';
    }

    public function headings(): array
    {
        return [
            "# STT",
            "Loại TB",
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
            "Số lượng",
            "Đơn giá",
            "Thành tiền",
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                setExcelHeaderAndFooter($event,
                $this->collection()->count(),
                'THỐNG KÊ THIẾT BỊ THEO LOẠI',
                'A', 'C', 'D', 'E');
                //Bold and set size 12
                $cellRange = 'A8:O8';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
    public function map($equipment): array
    {
        $statusEquipments = get_statusEquipments();
        return [
            $this->i++,
            isset($equipment->equipment_device) ? $equipment->equipment_device->title : 'NULL',
            $equipment->hash_code != null ? $equipment->hash_code : 'NULL',
            $equipment->title != null ? $equipment->title : 'NULL',
            isset($equipment->equipment_unit) ? $equipment->equipment_unit->title : 'NULL',
            $equipment->model != null ? $equipment->model : 'NULL',
            $equipment->serial != null ? $equipment->serial : 'NULL',
            $equipment->manufacturer != null ? $equipment->manufacturer : 'NULL',
            $equipment->origin != null ? $equipment->origin : 'NULL',
            $equipment->year_manufacture != null ? $equipment->year_manufacture : 'NULL',
            $equipment->year_use  != null ? $equipment->year_use : 'NULL',
            isset($statusEquipments[$equipment->status]) ? $statusEquipments[$equipment->status] : 'NULL',
            $equipment->import_price != null ? convert_currency($equipment->import_price) : '0',
            $equipment->amount != null ? $equipment->amount : 'NULL',
            convert_currency($equipment->amount * $equipment->import_price),
        ];
    }
}
