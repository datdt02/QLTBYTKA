<?php

namespace App\Imports;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class EquipmentsImport implements ToCollection, WithStartRow, WithValidation
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 4;
    }


    public function rules(): array
    {

        return [
            '2' => ['required', Rule::unique('equipments', 'serial')],
        ];

    }

    public function customValidationMessages(): array
    {
        return [
            '2.unique' => 'Trường serial đã tồn tại !',
            '2.required' => 'Vui lòng nhập trường serial !',
        ];
    }


    public function collection(Collection $rows)
    {
        $projects = getProject();
        $providers = getProvider();
        $devices = getDevice();
        foreach ($rows as $key => $row) {
            if ($rows[$key][0] != null && $rows[$key][2] != null) {
                $department_id = request('department_id');
                $row['department_id'] = $department_id;
                $status = request('status');
                $row["status"] = $status;
                $index = -1;
                Equipment::create([
                    'title' => $row[++$index],
                    'model' => $row[++$index],
                    'serial' => $row[++$index],
                    "hash_code" => $row[++$index],
                    'manufacturer' => $row[++$index],
                    'origin' => $row[++$index],
                    'year_manufacture' => $row[++$index],
                    'year_use' => $row[++$index],
                    'warehouse' => $row[++$index],
                    'date_delivery' => $row[++$index],
                    'amount' => $row[++$index],
                    'department_id' => $row['department_id'],
                    'present_value' => $row[$index = $index + 2],
                    'bid_project_id' => $row[++$index],
                    'note' => $row[++$index],
                    'status' => $row["status"],
                    'first_value' => $row[$index = $index + 2],
                    'depreciat' => $row[++$index],
                    'specificat' => $row[++$index],
                    'configurat' => $row[++$index],
                    'regular_inspection' => $row[++$index],
                    'last_inspection' => $row[++$index],
                    'import_price' => $row[++$index],
                    'provider_id' => $row[++$index],
                    'devices_id' => $row[++$index],
                    "last_radiation_inspection" => $row[++$index],
                    "periodic_radiation_inspection" => $row[++$index],
                    "jv_contract_termination_date" => $row[++$index],
                    "last_external_quality_assessment" => $row[++$index],
                    "period_of_external_quality_assessment" => $row[++$index],
                    "last_clinic_environment_inspection" => $row[++$index],
                    "period_of_clinic_environment_inspection" => $row[++$index],
                    "last_license_renewal_of_radiation_work" => $row[++$index],
                    "period_of_license_renewal_of_radiation_work" => $row[++$index],
                    'first_information' => Carbon::now()->format('Y-m-d'),
                ]);

            }
        }
    }
}
