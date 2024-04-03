<?php

namespace App\Http\Controllers\backends;


//include $_SERVER['DOCUMENT_ROOT'] . "\..\app\helpers\ExternalQualityAssessmentHelper.php";
if(env("APP_ENV") == "production"){
    include __DIR__ . "/../../../helpers/ExternalQualityAssessmentHelper.php";
}
else{
    include __DIR__ . "\..\..\..\helpers\ExternalQualityAssessmentHelper.php";
}



use App\Exports\ExternalQualityAssessmentExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExternalQualityAssessmentRequest;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\ExternalQualityAssessment;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class ExternalQualityAssessmentController extends Controller
{
    public function index()
    {
        checkAuthUserPermission("external_quality_assessment.read");

        //data_links
        $handledInput = handleFilterInput($_GET);

        //query equipments
        $equipments = queryEquipmentExternalQualityAssessment($handledInput["type_of_inspection"], $handledInput["time_inspection"], $handledInput["department_id"],
            $handledInput["searchKeyword"], $handledInput["period_of_external_quality_assessment"]);
        $equipments = $equipments->paginate(10);
        $departments = Department::all();


        return view("backends.external_quality_assessment.list",
            [
                "equipments" => $equipments,
                "departments" => $departments,

                //data_links
                "type_of_inspection" => $handledInput["type_of_inspection"],
                "time_inspection" => $handledInput["time_inspection"],
                "department_id" => $handledInput["department_id"],
                "searchKeyword" => $handledInput["searchKeyword"],
                "period_of_external_quality_assessment" => $handledInput["period_of_external_quality_assessment"],

            ]);
    }

    public function store(Equipment $equipment, StoreExternalQualityAssessmentRequest $request): RedirectResponse
    {
        checkAuthUserPermission("external_quality_assessment.read");

        $validatedAttributes = $request->validated();
        $equipment->external_quality_assessments()->create($validatedAttributes);
        updateInspectionDate($equipment, "external_quality_assessment", $equipment->last_external_quality_assessment, $equipment->period_of_external_quality_assessment);

        sendUpdatedExternalQualityAssessmentEmail($equipment);

        sendCreatedExternalQualityAssessmentNotification($equipment);

        return redirect()->route('external_quality_assessment.index')->with('success', 'Thêm thành công');
    }


    public function showHistory(Equipment $equipment)
    {
        checkAuthUserPermission("external_quality_assessment.read");
        $equipment = $equipment->load("external_quality_assessments", "equipment_department");
        return view("backends.external_quality_assessment.history", [
            "equipment" => $equipment,
        ]);
    }


    public function updateHistory(ExternalQualityAssessment                   $externalQualityAssessment,
                                  StoreExternalQualityAssessmentRequest $request): RedirectResponse
    {
        checkAuthUserPermission("external_quality_assessment.read");
        $validatedAttributes = $request->validated();
        $success = $externalQualityAssessment->update($validatedAttributes);

        $equipment = $externalQualityAssessment->equipment;
        $equipment->last_external_quality_assessment = $request->time;
        $equipment->next_external_quality_assessment =
            Carbon::createFromFormat("Y-m-d",
                $equipment->last_external_quality_assessment)
                ->addMonths($equipment->period_of_external_quality_assessment)->format("Y-m-d");
        $equipment->save();
        updateInspectionDate($equipment, "external_quality_assessment", $equipment->last_external_quality_assessment, $equipment->period_of_external_quality_assessment);

        sendUpdatedExternalQualityAssessmentNotification($equipment);
        sendUpdatedExternalQualityAssessmentEmail($equipment);


        if ($success) {
            return redirect()->back()->with('success', 'Cập nhật thành công');
        } else {
            return redirect()->back()->with('success', 'Cập nhật không thành công');
        }
    }


    public function export(): BinaryFileResponse
    {
        checkAuthUserPermission("external_quality_assessment.read");
        //filter
        $handledInput = handleFilterInput($_GET);
        //
        $department_name = " " . (Department::find($handledInput["department_id"])->title ?? "");

        return \Excel::download(new ExternalQualityAssessmentExport(
            $handledInput["type_of_inspection"],
            $handledInput["time_inspection"],
            $handledInput["department_id"],
            $handledInput["searchKeyword"],
            $handledInput["period_of_external_quality_assessment"]
        ), "Danh sách ngoại kiểm thiết bị" . $department_name . ".xlsx");
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportNextMonth(): BinaryFileResponse
    {
        checkAuthUserPermission("external_quality_assessment.read");
        //dd($_GET);
        $start = new Carbon('first day of next month');
        $time_inspection = $start->format('Y-m');
        return \Excel::download(new ExternalQualityAssessmentExport(
            "next",
            $time_inspection,
            "",
            "",
            "",
        ), "Danh sách ngoại kiểm thiết bị trong tháng " . $time_inspection . ".xlsx");

    }
}
