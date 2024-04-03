<?php

namespace App\Http\Controllers\backends;

//include $_SERVER['DOCUMENT_ROOT'] . "\..\app\helpers\RadiationInspectionHelper.php";
if(env("APP_ENV") == "production"){
    include __DIR__ . "/../../../helpers/RadiationInspectionHelper.php";
}
else{
    include __DIR__ . "\..\..\..\helpers\RadiationInspectionHelper.php";
}

use App\Exports\RadiationInspectionExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExternalQualityAssessmentRequest;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\RadiationInspection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RadiationInspectionController extends Controller
{
    public function index()
    {
        checkAuthUserPermission("radiation_inspection.read");

        //data_links
        $handledInput = handleFilterInput($_GET);

        //query equipments
        $equipments = queryEquipmentRadiationInspection($handledInput["type_of_inspection"], $handledInput["time_inspection"], $handledInput["department_id"],
            $handledInput["searchKeyword"], $handledInput["periodic_radiation_inspection"]);
        $equipments = $equipments->paginate(10);
        $departments = Department::all();


        return view("backends.radiation_inspection.list",
            [
                "equipments" => $equipments,
                "departments" => $departments,

                //data_links
                "type_of_inspection" => $handledInput["type_of_inspection"],
                "time_inspection" => $handledInput["time_inspection"],
                "department_id" => $handledInput["department_id"],
                "searchKeyword" => $handledInput["searchKeyword"],
                "periodic_radiation_inspection" => $handledInput["periodic_radiation_inspection"],

            ]);
    }

    public function store(Equipment $equipment, StoreExternalQualityAssessmentRequest $request): \Illuminate\Http\RedirectResponse
    {
        //dd($request->validated());
        checkAuthUserPermission("radiation_inspection.read");

        $validatedAttributes = $request->validated();
        $equipment->radiation_inspections()->create($validatedAttributes);
        updateAllInspectionDate($equipment, $validatedAttributes["time"],
            $equipment->periodic_radiation_inspection, "", "", "", "");

        sendCreatedRadiationInspectionEmail($equipment);

        sendCreatedRadiationInspectionNotification($equipment);

        return redirect()->route('radiation_inspection.index')->with('success', 'Thêm thành công');
    }

    public function showHistory(Equipment $equipment)
    {
        checkAuthUserPermission("radiation_inspection.read");
        $equipment = $equipment->load("radiation_inspections", "equipment_department");
        return view("backends.radiation_inspection.history", [
            "equipment" => $equipment,
        ]);
    }

    public function updateHistory(RadiationInspection                   $radiationInspection,
                                  StoreExternalQualityAssessmentRequest $request): \Illuminate\Http\RedirectResponse
    {
        checkAuthUserPermission("radiation_inspection.read");
        $validatedAttributes = $request->validated();
        $success = $radiationInspection->update($validatedAttributes);

        $equipment = $radiationInspection->equipment;
        $equipment->last_radiation_inspection = $request->time;
        $equipment->next_radiation_inspection =
            Carbon::createFromFormat("Y-m-d",
                $equipment->last_radiation_inspection)
                ->addMonths($equipment->periodic_radiation_inspection)->format("Y-m-d");
        $equipment->save();

        sendUpdatedRadiationInspectionNotification($equipment);
        sendUpdatedRadiationInspectionEmail($equipment);


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
    public function exportRadiationInspection(): BinaryFileResponse
    {
        checkAuthUserPermission("radiation_inspection.read");
        //filter
        $handledInput = handleFilterInput($_GET);
        //
        $department_name = " " . (Department::find($handledInput["department_id"])->title ?? "");

        return \Excel::download(new RadiationInspectionExport(
            $handledInput["type_of_inspection"],
            $handledInput["time_inspection"],
            $handledInput["department_id"],
            $handledInput["searchKeyword"],
            $handledInput["periodic_radiation_inspection"]
        ), "Danh sách kiểm xạ thiết bị" . $department_name . ".xlsx");
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportRadiationInspectionNextMonth(): BinaryFileResponse
    {
        checkAuthUserPermission("radiation_inspection.read");
        //dd($_GET);
        $start = new Carbon('first day of next month');
        $time_inspection = $start->format('Y-m');
        return \Excel::download(new RadiationInspectionExport(
            "next",
            $time_inspection,
            "",
            "",
            "",
        ), "Danh sách kiểm xạ thiết bị trong tháng " . $time_inspection . ".xlsx");

    }

}
