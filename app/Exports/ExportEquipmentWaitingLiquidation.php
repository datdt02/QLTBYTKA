<?php

namespace App\Exports;

use App\Models\Liquidation;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportEquipmentWaitingLiquidation implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents
{

    /**
    * @return \Illuminate\Support\Collection
    */

    private $i = 1;

    public function collection()
    {
        return Liquidation::where('status','waiting')->orderBy('created_at','desc')->get();
    }

    public function headings() :array {
        return [
         "# Id",
         "Tên thiết bị",
         "Người tạo phiếu",
         "Ngày tạo phiếu",
         "Số lượng thanh lý",
         "Lý do thanh lý", 
         "Trạng thái",
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
    

    public function map($liquis): array {

        $status = getStatusLiquidation();

        return [
            $this->i++,
            isset($liquis->equipment) ? $liquis->equipment->title :'',
            isset($liquis->user) ? $liquis->equipment->name :'',
            $liquis->created_at->format('Y-m-d'),
            $liquis->amount,
            $liquis->reason,
            isset($status[$liquis->status]) ? $status[$liquis->status] :'',
        ];
    }


}
