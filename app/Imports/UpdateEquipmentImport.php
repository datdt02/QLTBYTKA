<?php

namespace App\Imports;

use App\Models\Equipment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UpdateEquipmentImport implements ToCollection, WithStartRow
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $equipment = Equipment::where("serial", $row[2]);
            if ($equipment->get()->count() == 1) {
                $index = -1;
                $equipment = $equipment->first();
                $equipment->update(
                    [//0
                     'title' => isBlank($row[++$index]) ? $equipment->title : $row[$index],
                     'model' => isBlank($row[++$index]) ? $equipment->model : $row[$index],
                     'serial' => isBlank($row[++$index]) ? $equipment->serial : $row[$index],
                     "hash_code" => isBlank($row[++$index]) ? $equipment->hash_code : $row[$index],
                     'manufacturer' => isBlank($row[++$index]) ? $equipment->manufacturer : $row[$index],
                     //5
                     'origin' => isBlank($row[++$index]) ? $equipment->origin : $row[$index],
                     'year_manufacture' => isBlank($row[++$index]) ? $equipment->year_manufacture : $row[$index],
                     'year_use' => isBlank($row[++$index]) ? $equipment->year_use : $row[$index],
                     'warehouse' => isBlank($row[++$index]) ? $equipment->warehouse : createDateStringFromFormat(
                         $row[$index],
                         "d/m/Y",
                         "Y-m-d"
                     ),
                     'date_delivery' => isBlank($row[++$index]) ? $equipment->date_delivery : createDateStringFromFormat(
                         $row[$index],
                         "d/m/Y",
                         "Y-m-d"
                     ),
                     //10
                     'amount' => isBlank($row[++$index]) ? $equipment->amount : $row[$index],
                     'department_id' => $equipment->department_id,
                     'present_value' => isBlank($row[$index = $index + 2]) ? $equipment->present_value : $row[$index],
                     'bid_project_id' => $equipment->bid_project_id,
                     'note' => isBlank($index = $index + 2) ? $equipment->note : $row[$index],
                     //15
                     'status' => $equipment->status,
                     'first_value' => isBlank($row[$index = $index + 2]) ? $equipment->first_value : $row[$index],
                     'depreciat' => isBlank($row[++$index]) ? $equipment->depreciat : $row[$index],
                     'specificat' => isBlank($row[++$index]) ? $equipment->specificat : $row[$index],
                     'configurat' => isBlank($row[++$index]) ? $equipment->configurat : $row[$index],
                     //20
                     'regular_inspection' => isBlank($row[++$index]) ? $equipment->regular_inspection : $row[$index],
                     'last_inspection' => isBlank($row[++$index]) ? $equipment->last_inspection : createDateStringFromFormat(
                         $row[$index],
                         "d/m/Y",
                         "Y-m-d"
                     ),
                     'regular_maintenance' => isBlank($row[++$index]) ? $equipment->regular_maintenance : $row[$index],
                     //23
                     'import_price' => isBlank($row[++$index]) ? $equipment->import_price : $row[$index],
                     'provider_id' => $equipment->provider_id,
                     'devices_id' => $equipment->process,
                     //                    "last_radiation_inspection" => dd($index),
                     //30
                     "last_radiation_inspection" => isBlank($row[$index = $index + 7]) ? $equipment->last_radiation_inspection :
                         createDateStringFromFormat(
                             $row[$index],
                             "d/m/Y",
                             "Y-m-d"
                         ),
                     "periodic_radiation_inspection" => isBlank($row[++$index]) ? $equipment->periodic_radiation_inspection : $row[$index],
                     "jv_contract_termination_date" => isBlank($row[++$index]) ? $equipment->jv_contract_termination_date :
                         createDateStringFromFormat(
                             $row[$index],
                             "d/m/Y",
                             "Y-m-d"
                         ),
                     "last_external_quality_assessment" => isBlank($row[++$index]) ? $equipment->last_external_quality_assessment :
                         createDateStringFromFormat(
                             $row[$index],
                             "d/m/Y",
                             "Y-m-d"
                         ),
                     "period_of_external_quality_assessment" => isBlank($row[++$index]) ? $equipment->period_of_external_quality_assessment :
                         $row[$index],
                     //35
                     "last_clinic_environment_inspection" => isBlank($row[++$index]) ? $equipment->last_clinic_environment_inspection :
                         createDateStringFromFormat(
                             $row[$index],
                             "d/m/Y",
                             "Y-m-d"
                         ),
                     "period_of_clinic_environment_inspection" => isBlank($row[++$index]) ? $equipment->period_of_clinic_environment_inspection :
                         $row[$index],
                     "last_license_renewal_of_radiation_work" => isBlank($row[++$index]) ? $equipment->last_license_renewal_of_radiation_work :
                         createDateStringFromFormat(
                             $row[$index],
                             "d/m/Y",
                             "Y-m-d"
                         ),
                     "period_of_license_renewal_of_radiation_work" => isBlank($row[++$index]) ?
                         $equipment->period_of_license_renewal_of_radiation_work : $row[$index],
                    ]
                );
            }
        }

    }


    function startRow(): int
    {
        return 4;
    }

}
