<?php

namespace App\Http\Controllers\backends;

//include $_SERVER['DOCUMENT_ROOT'] . "\..\app\helpers\LicenseRenewalOfRadiationWorkHelper.php";
if(env("APP_ENV") == "production"){
    include __DIR__ . "/../../../helpers/LicenseRenewalOfRadiationWorkHelper.php";
}
else{
    include __DIR__ . "/../../../helpers/LicenseRenewalOfRadiationWorkHelper.php";
}

use App\Exports\LicenseRenewalOfRadiationWorkExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLicenseRenewalOfRadiationWorkRequest;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\LicenseRenewalOfRadiationWork;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\RedirectResponse;
use PhpOffice\PhpSpreadsheet\Exception;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LicenseRenewalOfRadiationWorkController extends Controller
{
    public function index()
    {
        checkAuthUserPermission("license_renewal_of_radiation_work.read");

        //data_links
        $handledInput = handleFilterInput($_GET);

        //query equipments
        $equipments = queryEquipmentLicenseRenewalOfRadiationWork($handledInput["type_of_inspection"], $handledInput["time_inspection"], $handledInput["department_id"],
            $handledInput["searchKeyword"], $handledInput["period_of_license_renewal_of_radiation_work"]);
        $equipments = $equipments->paginate(10);
        $departments = Department::all();


        return view("backends.license_renewal_of_radiation_work.list",
            [
                "equipments" => $equipments,
                "departments" => $departments,

                //data_links
                "type_of_inspection" => $handledInput["type_of_inspection"],
                "time_inspection" => $handledInput["time_inspection"],
                "department_id" => $handledInput["department_id"],
                "searchKeyword" => $handledInput["searchKeyword"],
                "period_of_license_renewal_of_radiation_work" => $handledInput["period_of_license_renewal_of_radiation_work"],

            ]);
    }

    public function store(Equipment $equipment, StoreLicenseRenewalOfRadiationWorkRequest $request): RedirectResponse
    {
        checkAuthUserPermission("license_renewal_of_radiation_work.read");

        $validatedAttributes = $request->validated();
        $equipment->license_renewal_of_radiation_works()->create($validatedAttributes);
        updateInspectionDate($equipment, "license_renewal_of_radiation_work", $equipment->last_license_renewal_of_radiation_work, $equipment->period_of_license_renewal_of_radiation_work);

        sendCreatedLicenseRenewalOfRadiationWorkNotification($equipment);
        sendUpdatedLicenseRenewalOfRadiationWorkEmail($equipment);


        return redirect()->route('license_renewal_of_radiation_work.index')->with('success', 'Thêm thành công');
    }


    public function showHistory(Equipment $equipment)
    {
        checkAuthUserPermission("license_renewal_of_radiation_work.read");
        $equipment = $equipment->load("license_renewal_of_radiation_works", "equipment_department");
        return view("backends.license_renewal_of_radiation_work.history", [
            "equipment" => $equipment,
        ]);
    }


    public function updateHistory(LicenseRenewalOfRadiationWork                   $clinicEnvironmentInspection,
                                  StoreLicenseRenewalOfRadiationWorkRequest $request): RedirectResponse
    {
        checkAuthUserPermission("license_renewal_of_radiation_work.read");
        $validatedAttributes = $request->validated();
        $success = $clinicEnvironmentInspection->update($validatedAttributes);

        $equipment = $clinicEnvironmentInspection->equipment;
        $equipment->last_license_renewal_of_radiation_work = $request->time;
        $equipment->next_license_renewal_of_radiation_work =
            Carbon::createFromFormat("Y-m-d",
                $equipment->last_license_renewal_of_radiation_work)
                ->addMonths($equipment->period_of_license_renewal_of_radiation_work)->format("Y-m-d");
        $equipment->save();
        updateInspectionDate($equipment, "license_renewal_of_radiation_work", $equipment->last_license_renewal_of_radiation_work, $equipment->period_of_license_renewal_of_radiation_work);

        sendUpdatedLicenseRenewalOfRadiationWorkNotification($equipment);
        sendUpdatedLicenseRenewalOfRadiationWorkEmail($equipment);


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
        checkAuthUserPermission("license_renewal_of_radiation_work.read");
        //filter
        $handledInput = handleFilterInput($_GET);
        //
        $department_name = " " . (Department::find($handledInput["department_id"])->title ?? "");

        return Excel::download(new LicenseRenewalOfRadiationWorkExport(
            $handledInput["type_of_inspection"],
            $handledInput["time_inspection"],
            $handledInput["department_id"],
            $handledInput["searchKeyword"],
            $handledInput["period_of_license_renewal_of_radiation_work"]
        ), "Danh sách Gia hạn giấy phép tiến hành CV bức xạ thiết bị" . $department_name . ".xlsx");
    }

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportNextMonth(): BinaryFileResponse
    {
        checkAuthUserPermission("license_renewal_of_radiation_work.read");
        //dd($_GET);
        $start = new Carbon('first day of next month');
        $time_inspection = $start->format('Y-m');
        return Excel::download(new LicenseRenewalOfRadiationWorkExport(
            "next",
            $time_inspection,
            "",
            "",
            "",
        ), "Danh sách Gia hạn giấy phép tiến hành CV bức xạ thiết bị trong tháng " . $time_inspection . ".xlsx");

    }//
}
