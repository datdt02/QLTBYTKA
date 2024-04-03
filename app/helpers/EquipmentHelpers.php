<?php

use App\Models\Equipment;
use Carbon\Carbon;

if (!function_exists('updateAllInspectionDate')) {
    function updateAllInspectionDate(Equipment $equipment, $last_radiation_inspection, $periodic_radiation_inspection,
                                               $last_inspection, $regular_inspection, $last_maintenance, $regular_maintenance)
    {
        //type = radiation, regular, maintenance
        updateInspectionDate($equipment, "radiation", $last_radiation_inspection, $periodic_radiation_inspection);
        updateInspectionDate($equipment, "regular", $last_inspection, $regular_inspection);
        updateInspectionDate($equipment, "maintenance", $last_maintenance, $regular_maintenance);
        $equipment->save();
    }
}

if (!function_exists('updateInspectionDate')) {
    function updateInspectionDate(Equipment $equipment, string $type, $last, $periodic, $dateFormat = "Y-m-d")
    {
        //type = radiation, regular, maintenance
        //update radiation_inspection|regular_inspection

        switch ($type) {
            case "radiation":
                $last_date_name = "last_radiation_inspection";
                $periodic_name = "periodic_radiation_inspection";
                $next_date_name = "next_radiation_inspection";
                break;
            case "regular":
                $last_date_name = "last_inspection";
                $periodic_name = "regular_inspection";
                $next_date_name = "next_inspection";
                break;
            case "maintenance":
                $last_date_name = "last_maintenance";
                $periodic_name = "regular_maintenance";
                $next_date_name = "next_maintenance";
                break;
            case "external_quality_assessment":
                $last_date_name = "last_external_quality_assessment";
                $periodic_name = "period_of_external_quality_assessment";
                $next_date_name = "next_external_quality_assessment";
                break;
            case "clinic_environment_inspection":
                $last_date_name = "last_clinic_environment_inspection";
                $periodic_name = "period_of_clinic_environment_inspection";
                $next_date_name = "next_clinic_environment_inspection";
                break;
            case "license_renewal_of_radiation_work":
                $last_date_name = "last_license_renewal_of_radiation_work";
                $periodic_name = "period_of_license_renewal_of_radiation_work";
                $next_date_name = "next_license_renewal_of_radiation_work";
                break;
        }
        if ($last && $periodic) {
            $equipment[$last_date_name] = $last;
            $equipment[$periodic_name] = $periodic;
            $equipment[$next_date_name] = Carbon::createFromFormat($dateFormat, $equipment[$last_date_name])
                ->addMonths($equipment[$periodic_name])->format("Y-m-d");
        }
        $equipment->save();
    }
}