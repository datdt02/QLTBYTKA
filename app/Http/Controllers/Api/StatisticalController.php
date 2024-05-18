<?php

namespace App\Http\Controllers\Api;

//include $_SERVER['DOCUMENT_ROOT'] . "\..\app\helpers\RadiationInspectionHelper.php";
if (env("APP_ENV") == "production") {
    include __DIR__ . "/../../../helpers/RadiationInspectionHelper.php";
} else {
    include __DIR__ . "/../../../helpers/RadiationInspectionHelper.php";
}

use App\Http\Controllers\Controller;
use App\Http\Requests\StatisticalByInfoRequest;
use App\Models\Department;
use App\Models\Equipment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StatisticalController extends Controller
{
    public function statisticByInfo(StatisticalByInfoRequest $request)
    {
        //PARAMETER           | USAGE
        //keyword             | title, serial, code, model, manufacturer, origin, yearUse, yearManufacture
        //categoryId             | cate (nhom thiet bi)
        //deviceId           | deviceId (loai thiet bi)
        //status           | status
        //risk                | risk
        //bidProjectId      | project
        //accrediationDate  | month of this date
        //department_id       |
        //startDate, endDate  | warranty_date

        try {
            $user = auth()->user();
            $equipments = Equipment::with('equipment_department', 'equipment_unit', 'equipment_cates');
            $status403 = response()->json(
                [
                    'message' => 'You do not have permission to do that',
                ],
                403
            );
            //query scope
            $equipments = $equipments
                ->orWhere(function (Builder $query) use ($request) {
                    $keyword = $request->keyword;
                    $query->title($keyword)
                        ->orWhere->serial($keyword)
                        ->orWhere->code($keyword)
                        ->orWhere->model($keyword)
                        ->orWhere->manufacturer($keyword)
                        ->orWhere->origin($keyword);
                });
            //by info
            if (!$user->can('statistical.show_all') && !$user->can('statistical.info')) {
                return $status403;
            }
            //classify
            if ($request->categoryId != '') {
                if ($user->can('statistical.classify')) {
                    $equipments = $equipments->cate($request->categoryId)
                        ->device($request->deviceId)
                        ->status($request->status);
                    dd($request->status);
                } else {
                    return $status403;
                }
            }
            //by year use
            if ($request->yearManufacture != '' || $request->yearUse != '') {
                if ($user->can('statistical.year')) {
                    $equipments = $equipments->orWhere->yearUse($request->yearUse)
                        ->orWhere->yearManufacture($request->yearManufacture);
                } else {
                    return $status403;
                }
            }
            //by risk
            if ($request->risk != '') {
                if ($user->can('statistical.risk')) {
                    $equipments = $equipments->risk($request->risk);
                } else {
                    return $status403;
                }
            }
            //by project
            if ($request->bidProjectId != '') {
                if ($user->can('statistical.project')) {
                    $equipments = $equipments->project($request->bidProjectId);
                } else {
                    return $status403;
                }
            }
            //by accrediationDate
            if ($request->accrediationDate != '') { //check permission
                if ($user->can('statistical.accreditation')) {
                    $equipments = $equipments->accrediationDate($request->accrediationDate);
                } else {
                    return $status403;
                }
            }
            //statistic by department
            if ($request->departmentId != '') { //check permission
                if (!$user->can('statistical.show_all') && !$user->can('statistical.department')) {
                    return $status403;
                }
                if ($user->can('statistical.show_all') && $user->can('statistical.department')) {
                    //if authenticated user has permission to read all equipments
                    $equipments = $equipments->department($request->departmentId);
                } else if ($user->can('statistical.info')) {
                    //if authenticated user only has permissions to read all equipments from his/her department
                    //ex: nvkp
                    $equipments = $equipments->department($user->departmentId);
                }
            }
            //statistic by warranty_date
            if ($request->startDate != '' && $request->endDate != '') { //check permission
                if (!$user->can('statistical.warranty_date')) {
                    return $status403;
                } else {
                    $equipments = $equipments->warrantyDate($request->startDate, $request->endDate);
                }
            }
            $equipments = $equipments->get();
            return response()->json(
                [
                    'equipments' => $equipments,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Server not responding',
                    'error' => $e
                ],
                500
            );
        }
    }

    public function countBrokenAndRepairingEquipmentEachDepartment()
    {
        $departments = Department::with("department_equipment")->get();
        $brokenEquipmentsCountOnEachDepartment = array();
        foreach ($departments as $department) {
            $repairingEquipmentsCount = Equipment::where("department_id", $department->id)->where("status", "corrected")->count();
            $brokenEquipmentsCount = Equipment::where("department_id", $department->id)->where("status", "was_broken")->count();
            $brokenEquipmentsCountOnDepartment = array();
            $brokenEquipmentsCountOnDepartment["departmentId"] = $department->id;
            $brokenEquipmentsCountOnDepartment["departmentTitle"] = $department->title;
            $brokenEquipmentsCountOnDepartment["brokenEquipmentsCount"] = $brokenEquipmentsCount;
            $brokenEquipmentsCountOnDepartment["repairingEquipmentsCount"] = $repairingEquipmentsCount;
            /*$brokenEquipmentsCountOnDepartmentJson = json_encode($brokenEquipmentsCountOnDepartment);
            $brokenEquipmentsCountOnEachDepartment[] = $brokenEquipmentsCountOnDepartmentJson;*/
            $brokenEquipmentsCountOnEachDepartment[] = $brokenEquipmentsCountOnDepartment;
        }
        return response()->json($brokenEquipmentsCountOnEachDepartment);

    }
}
