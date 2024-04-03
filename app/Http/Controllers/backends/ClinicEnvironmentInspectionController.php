<?php

namespace App\Http\Controllers\backends;

//include $_SERVER['DOCUMENT_ROOT'] . "\..\app\helpers\ClinicEnvironmentInspectionHelper.php";

if(env("APP_ENV") == "production"){
    include __DIR__ . "/../../../helpers/ClinicEnvironmentInspectionHelper.php";
}
else{
    include __DIR__ . "\..\..\..\helpers\ClinicEnvironmentInspectionHelper.php";
}

use App\Exports\ClinicEnvironmentInspectionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClinicEnvironmentInspectionRequest;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\ClinicEnvironmentInspection;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\RedirectResponse;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ClinicEnvironmentInspectionController extends Controller
{
    public function index()
    {
        checkAuthUserPermission("clinic_environment_inspection.read");

        //data_links
        $handledInput = handleFilterInput($_GET);

        //query equipments
        $equipments = queryEquipmentClinicEnvironmentInspection($handledInput["type_of_inspection"], $handledInput["time_inspection"], $handledInput["department_id"],
            $handledInput["searchKeyword"], $handledInput["period_of_clinic_environment_inspection"]);
        $equipments = $equipments->paginate(10);
        $departments = Department::all();


        return view("backends.clinic_environment_inspection.list",
            [
                "equipments" => $equipments,
                "departments" => $departments,

                //data_links
                "type_of_inspection" => $handledInput["type_of_inspection"],
                "time_inspection" => $handledInput["time_inspection"],
                "department_id" => $handledInput["department_id"],
                "searchKeyword" => $handledInput["searchKeyword"],
                "period_of_clinic_environment_inspection" => $handledInput["period_of_clinic_environment_inspection"],

            ]);
    }

    public function store(Equipment $equipment, StoreClinicEnvironmentInspectionRequest $request): RedirectResponse
    {
        checkAuthUserPermission("clinic_environment_inspection.read");

        $validatedAttributes = $request->validated();
        $equipment->clinic_environment_inspections()->create($validatedAttributes);
        updateInspectionDate($equipment, "clinic_environment_inspection", $equipment->last_clinic_environment_inspection, $equipment->period_of_clinic_environment_inspection);

        sendCreatedClinicEnvironmentInspectionNotification($equipment);
        sendUpdatedClinicEnvironmentInspectionEmail($equipment);


        return redirect()->route('clinic_environment_inspection.index')->with('success', 'Thêm thành công');
    }


    public function showHistory(Equipment $equipment)
    {
        checkAuthUserPermission("clinic_environment_inspection.read");
        $equipment = $equipment->load("clinic_environment_inspections", "equipment_department");
        return view("backends.clinic_environment_inspection.history", [
            "equipment" => $equipment,
        ]);
    }


    public function updateHistory(ClinicEnvironmentInspection                   $clinicEnvironmentInspection,
                                  StoreClinicEnvironmentInspectionRequest $request): RedirectResponse
    {
        checkAuthUserPermission("clinic_environment_inspection.read");
        $validatedAttributes = $request->validated();
        $success = $clinicEnvironmentInspection->update($validatedAttributes);

        $equipment = $clinicEnvironmentInspection->equipment;
        $equipment->last_clinic_environment_inspection = $request->time;
        $equipment->next_clinic_environment_inspection =
            Carbon::createFromFormat("Y-m-d",
                $equipment->last_clinic_environment_inspection)
                ->addMonths($equipment->period_of_clinic_environment_inspection)->format("Y-m-d");
        $equipment->save();
        updateInspectionDate($equipment, "clinic_environment_inspection", $equipment->last_clinic_environment_inspection, $equipment->period_of_clinic_environment_inspection);

        sendUpdatedClinicEnvironmentInspectionNotification($equipment);
        sendUpdatedClinicEnvironmentInspectionEmail($equipment);


        if ($success) {
            return redirect()->back()->with('success', 'Cập nhật thành công');
        } else {
            return redirect()->back()->with('success', 'Cập nhật không thành công');
        }
    }


    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(): BinaryFileResponse
    {
        checkAuthUserPermission("clinic_environment_inspection.read");
        //filter
        $handledInput = handleFilterInput($_GET);
        //
        $department_name = " " . (Department::find($handledInput["department_id"])->title ?? "");

        return Excel::download(new ClinicEnvironmentInspectionExport(
            $handledInput["type_of_inspection"],
            $handledInput["time_inspection"],
            $handledInput["department_id"],
            $handledInput["searchKeyword"],
            $handledInput["period_of_clinic_environment_inspection"]
        ), "Danh sách kiểm định môi trường phòng thiết bị" . $department_name . ".xlsx");
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportNextMonth(): BinaryFileResponse
    {
        checkAuthUserPermission("clinic_environment_inspection.read");
        //dd($_GET);
        $start = new Carbon('first day of next month');
        $time_inspection = $start->format('Y-m');
        return Excel::download(new ClinicEnvironmentInspectionExport(
            "next",
            $time_inspection,
            "",
            "",
            "",
        ), "Danh sách kiểm định môi trường phòng thiết bị trong tháng " . $time_inspection . ".xlsx");

    }//
}
