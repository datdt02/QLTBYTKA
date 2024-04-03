<?php

namespace App\Exports;

use App\Models\Transfer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportTransferEquipmentList implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents
{

    /**
    * @return \Illuminate\Support\Collection
    */

    private $i = 1;
    protected $department_id;
    protected $status_key;
    protected $startDate;
    protected $endDate;
    protected $key;
    public function __construct($department_id,$status_key,$startDate,$endDate,$key) {
        $this->department_id = $department_id;
        $this->status_key = $status_key;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->key = $key;
    }
    public function collection()
    {
        $department_id = $this->department_id;
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $status_key = $this->status_key;
        $key = $this->key;
        $transfers = Transfer::query();
        $eq_query = function ($query) use ($key) {
            return $query->select('equipments.id','equipments.title')->where('equipments.title','like','%'.$key.'%');
        };
        $depart_query = function ($query) use ($department_id) {
            return $query->select('departments.id','departments.title')->where('departments.id', $department_id);
        };
        if($startDate == '') $transfers= $transfers->whereDate('transfers.created_at', '<=', $endDate);
            else $transfers= $transfers->whereDate('transfers.created_at', '>=', $startDate)->whereDate('transfers.created_at', '<=', $endDate);
        if($key != '') $transfers= $transfers->whereHas('transfer_equipment', $eq_query);
        if($department_id != '') $transfers= $transfers->whereHas('transfer_department', $depart_query);
        if($status_key != '') $transfers= $transfers->where('transfers.status', $status_key);
        $transfers = $transfers->orderBy('transfers.status','desc')->get();
        return $transfers;
    }

    public function headings() :array {
        return [
         "# STT",
         "Khoa - Phòng",
         "Mã VT",
         "Tên VT",
         "ĐVT",
         "Model",
         "S/N",
         "Trạng thái",
         "Nội dung",
         "Đơn giá",
         "Người tạo",
         "Số lượng",
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


    public function map($transfer): array {
        $status = get_statusTransfer();
        return [
            $this->i++,
            isset($transfer->transfer_department) ? $transfer->transfer_department->title : 'NULL',
            isset($transfer->transfer_equipment) && $transfer->transfer_equipment->hash_code != null ? $transfer->transfer_equipment->hash_code : 'NULL',
            isset($transfer->transfer_equipment) ? $transfer->transfer_equipment->title : 'NULL',
            $transfer->title != null ? $transfer->title : 'NULL',
            isset($transfer->transfer_equipment->equipment_unit)? $transfer->transfer_equipment->equipment_unit->title : 'NULL',
            isset($transfer->transfer_equipment) ? $transfer->transfer_equipment->model : 'NULL',
            isset($transfer->transfer_equipment) ? $transfer->transfer_equipment->serial : 'NULL',
            $status[$transfer->status],
            $transfer->content != null ? $transfer->content : 'NULL',
            isset($transfer->transfer_user) ? $transfer->transfer_user->name : 'NULL',
            $transfer->amount != null ? $transfer->amount : '0',
        ];
    }


}
