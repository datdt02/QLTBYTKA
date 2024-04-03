<?php

namespace App\Exports;

use App\Models\Eqsupplie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class EqsuppliesExport implements FromCollection, WithHeadings , WithMapping ,ShouldAutoSize ,WithEvents
{

    /**
    * @return \Illuminate\Support\Collection
    */

    private $i = 1;

    protected $supplie_key;

    protected $provider_key;

    protected $key;

    public function __construct($supplie_key,$provider_key,$key) {

        $this->supplie_key = $supplie_key;

        $this->provider_key = $provider_key;

        $this->key = $key;

    }

    public function collection()
    {
        return Eqsupplie::all();
    }

    public function headings() :array {
        return [
         "# Id",
         "Tên vật tư",
         "Loại vật tư",
         "Số lượng",
         "Đơn vị tính",
         "Giá nhập",
         "Hãng sản xuất",
         "Xuất xứ",
         "Nhà cung cấp",
         "Số serial",
         "Model",
         "Năm sản xuất",
         "Năm sử dụng",
         "Số phiếu",
         "Ngày nhập kho",
         "Hạn sử dụng",
         "Thông số kỹ thuật",
         "Cấu hình kỹ thuật",
         "Dự án",
         "Quy trình sử dụng",
         "Ghi chú",
         "Người nhập",
         "Ngày nhập thông tin",
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:AJ1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }


    public function map($eqsupplies): array {
        return [
            $this->i++,
            isset($eqsupplies->title) ? $eqsupplies->title :'',
            isset($eqsupplies->eqsupplie_supplie->title) ? $eqsupplies->eqsupplie_supplie->title :'',
            $eqsupplies->amount,
            isset($eqsupplies->eqsupplie_unit->title) ? $eqsupplies->eqsupplie_unit->title :'',
            $eqsupplies->import_price,
            $eqsupplies->manufacturer,
            $eqsupplies->origin,
            isset($eqsupplies->eqsupplie_provider->title) ? $eqsupplies->eqsupplie_provider->title :'',
            $eqsupplies->serial,
            $eqsupplies->model,
            $eqsupplies->year_manufacture,
            $eqsupplies->year_use,
            $eqsupplies->votes,
            $eqsupplies->warehouse,
            $eqsupplies->expiry,
            $eqsupplies->specificat,
            $eqsupplies->configurat,
            $eqsupplies->bid_project,
            $eqsupplies->process,
            $eqsupplies->note,
            isset($eqsupplies->eqsupplie_user->name) ? $eqsupplies->eqsupplie_user->name :'',
            $eqsupplies->first_information,
        ];
    }


}
