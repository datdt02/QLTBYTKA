<?php

namespace App\Http\Controllers\backends;

use App\Exports\EquipmentStatisticsByAccreditationExport;
use App\Exports\EquipmentStatisticsByFacultyExport;
use App\Exports\EquipmentStatisticsByGroupExport;
use App\Exports\EquipmentStatisticsByInfoExport;
use App\Exports\EquipmentStatisticsByJvContractExport;
use App\Exports\JvContractExport;
use App\Exports\EquipmentStatisticsByProExport;
use App\Exports\EquipmentStatisticsByRiskExport;
use App\Exports\EquipmentStatisticsByStatusExport;
use App\Exports\EquipmentStatisticsBySuppliesExport;
use App\Exports\EquipmentStatisticsByTypeExport;
use App\Exports\EquipmentStatisticsByWarrantyDateExport;
use App\Exports\EquipmentStatisticsByYearManufactureExport;
use App\Exports\EquipmentStatisticsByYearUseExport;
use App\Http\Controllers\Controller;
use App\Models\Cates;
use App\Models\Department;
use App\Models\Device;
use App\Models\Eqsupplie;
use App\Models\Equipment;
use App\Models\Project;
use App\Models\Supplie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class StatisticController extends Controller
{
    public function infoEquip(Request $request)
    {
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $status_id = isset($request->status_id) ? $request->status_id : '';
        $cate_id = isset($request->cate_id) ? $request->cate_id : '';
        $device_id = isset($request->device_id) ? $request->device_id : '';
        $cates = Cates::select('id', 'title')->get();
        $devices = Device::select('id', 'title')->get();
        $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
        $equipments = Equipment::with('equipment_department', 'equipment_unit', 'equipment_cates');
        $cate_query = function ($query) use ($cate_id) {
            return $query->select('equipment_cates.id', 'equipment_cates.title')->where('equipment_cates.id', $cate_id);
        };
        $device_query = function ($query) use ($device_id) {
            return $query->select('devices.id', 'devices.title')->where('devices.id', $device_id);
        };
        if ($status_id != '') $equipments = $equipments->where('equipments.status', 'like', '%' . $status_id . '%');
        if ($cate_id != '') $equipments = $equipments->whereHas('equipment_cates', $cate_query);
        if ($device_id != '') $equipments = $equipments->whereHas('equipment_device', $device_query);
        if ($keyword != '') {
            $equipments = $equipments->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('code', 'like', '%' . $keyword . '%')
                    ->orWhere('model', 'like', '%' . $keyword . '%')
                    ->orWhere('serial', 'like', '%' . $keyword . '%')
                    ->orWhere('manufacturer', 'like', '%' . $keyword . '%')
                    ->orWhere('origin', 'like', '%' . $keyword . '%')
                    ->orWhere('year_manufacture', 'like', '%' . $keyword . '%')
                    ->orWhere('year_use', 'like', '%' . $keyword . '%');
            });
        }
        if ($user->can('statistical.show_all') && $user->can('statistical.info')) {
            //if authenticated user has permission to read all equipments
            $department_id = isset($request->department_id) ? $request->department_id : '';
            $departments = Department::select('id', 'title')->get();
            $equipments_query = function ($query) use ($department_id) {
                return $query->select('departments.id', 'departments.title')->where('departments.id', $department_id);
            };
            if ($department_id != '') $equipments = $equipments->whereHas('equipment_department', $equipments_query);
        } elseif ($user->can('statistical.info')) {
            //if authenticated user only has permissions to read all equipments from his/her department
            //ex: nvkp
            $department_id = $user->department_id;
            $departments = Department::where('id', $department_id)->select('id', 'title')->first();
            $equipments_query = function ($query) use ($department_id) {
                return $query->select('departments.id', 'departments.title')->where('departments.id', $department_id);
            };
            $equipments = $equipments->whereHas('equipment_department', $equipments_query);
        } else {
            abort(403);
        }
        $equipments = $equipments->orderby('created_at', 'desc')->paginate($number);
        $total = $equipments->total();
        $data = [
            'user' => $user,
            'equipments' => $equipments,
            'cates' => $cates,
            'departments' => $departments,
            'devices' => $devices,
            'number' => $number,
            'keyword' => $keyword,
            'status_id' => $status_id,
            'cate_id' => $cate_id,
            'department_id' => $department_id,
            'device_id' => $device_id,
            'total' => $total,
        ];
        return view('backends.statistical.list_info', $data);
    }

    public function exportInfo(Request $request)
    {
        $cate_id = isset($request->cate_id) ? $request->cate_id : '';
        $status_id = isset($request->status_id) ? $request->status_id : '';
        $department_id = isset($request->department_id) ? $request->department_id : '';
        $device_id = isset($request->device_id) ? $request->device_id : '';
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new EquipmentStatisticsByInfoExport($cate_id, $status_id, $department_id, $device_id, $key), 'Thống kê thiết bị theo thông tin ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

    public function departments(Request $request)
    {
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $status_id = isset($request->status_id) ? $request->status_id : '';
        $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
        $equipments = Equipment::query();
        if ($keyword != '') $equipments = $equipments->where('equipments.title', 'like', '%' . $keyword . '%');
        if ($status_id != '') $equipments = $equipments->where('equipments.status', 'like', '%' . $status_id . '%');
        if ($user->can('statistical.show_all') && $user->can('statistical.department')) {
            $department_id = isset($request->department_id) ? $request->department_id : '';
            $departments = Department::select('id', 'title')->get();
            $equipments_query = function ($query) use ($department_id) {
                return $query->select('departments.id', 'departments.title')->where('departments.id', $department_id);
            };
            if ($department_id != '') $equipments = $equipments->whereHas('equipment_department', $equipments_query);
        } elseif ($user->can('statistical.department')) {
            $department_id = $user->department_id;
            $departments = Department::where('id', $department_id)->select('id', 'title')->first();
            $equipments_query = function ($query) use ($department_id) {
                return $query->select('departments.id', 'departments.title')->where('departments.id', $department_id);
            };
            $equipments = $equipments->whereHas('equipment_department', $equipments_query);
        } else {
            abort(403);
        }
        $equipments = $equipments->orderby('department_id', 'asc')->paginate($number);
        $total = $equipments->total();
        $data = [
            'user' => $user,
            'equipments' => $equipments,
            'total' => $total,
            'number' => $number,
            'departments' => $departments,
            'keyword' => $keyword,
            'status_id' => $status_id,
            'department_id' => $department_id
        ];
        return view('backends.statistical.list_departments', $data);
    }

    public function exportDepartments(Request $request)
    {
        $department_id = isset($request->department_id) ? $request->department_id : '';
        $department_title = $department_id != '' ? Department::find($department_id)->title : '';
        $status_id = isset($request->status_id) ? $request->status_id : '';
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new EquipmentStatisticsByFacultyExport($department_id, $status_id, $key), 'Thống kê thiết bị theo khoa: ' . $department_title . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

    public function classify(Request $request)
    {
        $user = Auth::user();
        if ($user->can('statistical.classify')) {
            $keyword = isset($request->key) ? $request->key : '';
            $cate_name = Cates::select('id', 'title')->get();
            $device_name = Device::select('id', 'title')->get();
            $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
            $classify = isset($_GET['classify']) ? ($_GET['classify']) : '';
            if ($classify == 'type') {
                $device_id = isset($request->device_id) ? $request->device_id : '';
                $equipments_query = function ($query) use ($device_id) {
                    if ($device_id != '') {
                        return $query->select('devices.id', 'devices.title')
                            ->where('devices.id', $device_id);
                    } else {
                        return $query->select('devices.id', 'devices.title');
                    }
                };
                $equipments = Equipment::with('equipment_unit');
                if ($keyword != '') $equipments = $equipments->where('equipments.title', 'like', '%' . $keyword . '%');
                $equipments = $equipments->with(['equipment_device' => $equipments_query])
                    ->whereHas('equipment_device', $equipments_query)
                    ->orderby('equipments.devices_id', 'asc')->paginate($number);
                $total = $equipments->total();
                $data = [
                    'equipments' => $equipments,
                    'device_name' => $device_name,
                    'keyword' => $keyword,
                    'device_id' => $device_id,
                    'number' => $number,
                    'classify' => $classify,
                    'total' => $total
                ];
            } elseif ($classify == 'status') {
                $status_id = isset($request->status_id) ? $request->status_id : '';
                $equipments = Equipment::with('equipment_unit');
                if ($keyword != '') $equipments = $equipments->where('equipments.title', 'like', '%' . $keyword . '%');
                if ($status_id != '') $equipments = $equipments->where('equipments.status', 'like', '%' . $status_id . '%');
                $equipments = $equipments->where('equipments.status', '!=', null)->orderby('equipments.status', 'asc')->paginate($number);
                $total = $equipments->total();
                $data = [
                    'equipments' => $equipments,
                    'keyword' => $keyword,
                    'status_id' => $status_id,
                    'number' => $number,
                    'classify' => $classify,
                    'total' => $total
                ];
            } else {
                $cate_id = isset($request->cate_id) ? $request->cate_id : '';
                $equipments_query = function ($query) use ($cate_id) {
                    if ($cate_id != '') {
                        return $query->select('equipment_cates.id', 'equipment_cates.title')
                            ->where('equipment_cates.id', $cate_id);
                    } else {
                        return $query->select('equipment_cates.id', 'equipment_cates.title');
                    }
                };
                $equipments = Equipment::with('equipment_unit');
                if ($keyword != '') $equipments = $equipments->where('equipments.title', 'like', '%' . $keyword . '%');
                $equipments = $equipments->with(['equipment_cates' => $equipments_query])
                    ->whereHas('equipment_cates', $equipments_query)
                    ->orderby('equipments.cate_id', 'asc')->paginate($number);
                $total = $equipments->total();
                $data = [
                    'equipments' => $equipments,
                    'cate_name' => $cate_name,
                    'keyword' => $keyword,
                    'cate_id' => $cate_id,
                    'number' => $number,
                    'classify' => $classify,
                    'total' => $total
                ];
            }
            return view('backends.statistical.list_classify', $data);
        } else {
            abort(403);
        }
    }

    public function exportGroups(Request $request)
    {
        $cate_id = isset($request->cate_id) ? $request->cate_id : '';
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new EquipmentStatisticsByGroupExport($cate_id, $key), 'Thống kê thiết bị theo nhóm ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

    public function exportTypes(Request $request)
    {
        $device_id = isset($request->device_id) ? $request->device_id : '';
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new EquipmentStatisticsByTypeExport($device_id, $key), 'Thống kê thiết bị theo loại ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

    public function exportStatus(Request $request)
    {
        $status_id = isset($request->status_id) ? $request->status_id : '';
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new EquipmentStatisticsByStatusExport($status_id, $key), 'Thống kê thiết bị theo trạng thái ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

    public function yearManufacture(Request $request)
    {
        $user = Auth::user();
        if ($user->can('statistical.year')) {
            $keyword = isset($request->key) ? $request->key : '';
            $use_manu = isset($request->use_manu) ? $request->use_manu : '';
            $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
            $year = isset($_GET['year']) ? ($_GET['year']) : '';
            $equipments = Equipment::query();
            if ($year == 'use') {
                if ($keyword != '') $equipments = $equipments->where('equipments.title', 'like', '%' . $keyword . '%');
                if ($use_manu != '') $equipments = $equipments->where('equipments.year_use', $use_manu);
                $equipments = $equipments->where('equipments.year_use', '!=', null)->orderby('equipments.year_use', 'asc')->paginate($number);
                $total = $equipments->total();
                $data = [
                    'equipments' => $equipments,
                    'keyword' => $keyword,
                    'use_manu' => $use_manu,
                    'year' => $year,
                    'number' => $number,
                    'total' => $total
                ];
            } else {
                if ($keyword != '') $equipments = $equipments->where('equipments.title', 'like', '%' . $keyword . '%');
                if ($use_manu != '') $equipments = $equipments->where('equipments.year_manufacture', $use_manu);
                $equipments = $equipments->where('equipments.year_manufacture', '!=', null)->orderby('equipments.year_manufacture', 'asc')->paginate($number);
                $total = $equipments->total();
                $data = [
                    'equipments' => $equipments,
                    'keyword' => $keyword,
                    'use_manu' => $use_manu,
                    'year' => $year,
                    'number' => $number,
                    'total' => $total
                ];
            }
            return view('backends.statistical.list_year', $data);
        } else {
            abort(403);
        }
    }

    public function exportYearUse(Request $request)
    {
        $year = isset($request->year) ? $request->year : '';
        $use_manu = isset($request->use_manu) ? $request->use_manu : '';
        $key = isset($request->key) ? $request->key : '';
        if ($year != '') {
            return Excel::download(new EquipmentStatisticsByYearUseExport($year, $use_manu, $key), 'Thống kê thiết bị theo năm sử dụng ' . Carbon::now()->format('d-m-Y') . '.xlsx');
        } else {
            return Excel::download(new EquipmentStatisticsByYearUseExport($year, $use_manu, $key), 'Thống kê thiết bị theo năm sản xuất ' . Carbon::now()->format('d-m-Y') . '.xlsx');
        }
    }

    public function risk(Request $request)
    {
        $user = Auth::user();
        if ($user->can('statistical.risk')) {
            $keyword = isset($request->key) ? $request->key : '';
            $risk = isset($request->risk) ? $request->risk : '';
            $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
            $equipments = Equipment::query();
            if ($keyword != '') $equipments = $equipments->where('equipments.title', 'like', '%' . $keyword . '%');
            if ($risk != '') $equipments = $equipments->where('equipments.risk', $risk);
            $equipments = $equipments->where('equipments.risk', '!=', null)->orderby('equipments.risk', 'asc')->paginate($number);
            $total = $equipments->total();
            $data = [
                'equipments' => $equipments,
                'keyword' => $keyword,
                'risk' => $risk,
                'number' => $number,
                'total' => $total
            ];
            return view('backends.statistical.list_risk', $data);
        } else {
            abort(403);
        }
    }

    public function exportRisk(Request $request)
    {
        $risk = isset($request->risk) ? $request->risk : '';
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new EquipmentStatisticsByRiskExport($risk, $key), 'Thống kê thiết bị mức độ rủi ro ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

    public function project(Request $request)
    {
        $user = Auth::user();
        if ($user->can('statistical.project')) {
            $keyword = isset($request->key) ? $request->key : '';
            $pro = isset($request->pro) ? $request->pro : '';
            $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
            $projects = Project::select('id', 'title')->get();
            $equipments = Equipment::with('project', 'equipment_department', 'equipment_unit');
            if ($keyword != '') $equipments = $equipments->where('equipments.title', 'like', '%' . $keyword . '%');
            if ($pro != '') $equipments = $equipments->where('equipments.bid_project_id', $pro);
            $equipments = $equipments->where('equipments.bid_project_id', '!=', null)->paginate($number);
            $total = $equipments->total();
            $data = [
                'equipments' => $equipments,
                'keyword' => $keyword,
                'pro' => $pro,
                'projects' => $projects,
                'number' => $number,
                'total' => $total
            ];
            return view('backends.statistical.list_project', $data);
        } else {
            abort(403);
        }
    }

    public function exportProject(Request $request)
    {
        $pro = isset($request->pro) ? $request->pro : '';
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new EquipmentStatisticsByProExport($pro, $key), 'Thống kê thiết bị theo dự án ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

    public function accreditation(Request $request)
    {
        $user = Auth::user();
        if ($user->can('statistical.accreditation')) {
            $keyword = isset($request->key) ? $request->key : '';
            $current_month = isset($request->month) ? date_format(date_create($request->month), 'Y-m') . '-01' : Carbon::now()->format('Y-m-d');
            $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
            $equipments = Equipment::query();
            if ($keyword != '') $equipments = $equipments->where('equipments.title', 'like', '%' . $keyword . '%');
            $equipments = $equipments->whereRaw('TIMESTAMPDIFF(MONTH, last_inspection, "' . $current_month . '")%regular_inspection = 0')
                //->whereRaw('TIMESTAMPDIFF(MONTH, last_inspection, "'.$current_month.'") != 0')//does not take the current month
                ->where('last_inspection', '!=', null)->orderby('last_inspection', 'asc')->paginate($number);
            $total = $equipments->total();
            $data = [
                'equipments' => $equipments,
                'keyword' => $keyword,
                'number' => $number,
                'current_month' => $current_month,
                'total' => $total
            ];
            return view('backends.statistical.list_accreditation', $data);
        } else {
            abort(403);
        }
    }

    public function exportAccreditation(Request $request)
    {
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new EquipmentStatisticsByAccreditationExport($key), 'Thống kê thiết theo kiểm định ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

    public function warrantyDate(Request $request)
    {
        $user = Auth::user();
        if ($user->can('statistical.warranty_date')) {
            $keyword = isset($request->key) ? $request->key : '';
            $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m') . '-01';
            $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-t') : Carbon::now()->format('Y-m-d');
            $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
            $equipments = Equipment::query();
            if ($keyword != '') $equipments = $equipments->where('equipments.title', 'like', '%' . $keyword . '%');
            if ($startDate == '') {
                $equipments = $equipments->whereDate('warranty_date', '<=', $endDate);
            } else {
                $equipments = $equipments->whereDate('warranty_date', '>=', $startDate)->whereDate('warranty_date', '<=', $endDate);
            }
            $equipments = $equipments->orderby('created_at', 'desc')->paginate($number);
            $total = $equipments->total();
            $data = [
                'equipments' => $equipments,
                'keyword' => $keyword,
                'number' => $number,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'total' => $total
            ];
            return view('backends.statistical.list_warranty_date', $data);
        } else {
            abort(403);
        }
    }

    public function exportWarrantyDate(Request $request)
    {
        $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m') . '-01';
        $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-t') : Carbon::now()->format('Y-m-d');
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new EquipmentStatisticsByWarrantyDateExport($startDate, $endDate, $key), 'Thống kê thiết bị hết hạn bảo hành ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

    public function supplies(Request $request)
    {
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $supplie_id = isset($request->supplie_id) ? $request->supplie_id : '';
        $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
        $supplies = Supplie::select('id', 'title')->get();
        $eqsupplies = Eqsupplie::query();
        $supplie_query = function ($query) use ($supplie_id) {
            if ($supplie_id != '') {
                return $query->select('supplies.id', 'supplies.title')
                    ->where('supplies.id', $supplie_id);
            } else {
                return $query->select('supplies.id', 'supplies.title');
            }
        };
        if ($user->can('statistical.show_all') && $user->can('statistical.supplies')) {
            $department_id = isset($request->department_id) ? $request->department_id : '';
            $departments = Department::select('id', 'title')->get();
        } elseif ($user->can('statistical.supplies')) {
            $department_id = $user->department_id;
            $departments = Department::where('id', $department_id)->select('id', 'title')->first();
        } else {
            abort(403);
        }
        $depart_query = function ($query) use ($department_id) {
            return $query->select('id', 'title')->where('departments.id', $department_id);
        };
        if ($department_id != '') $eqsupplies = $eqsupplies->whereHas('supplie_devices.equipment_department', $depart_query);
        if ($keyword != '') $eqsupplies = $eqsupplies->where('equipment_supplies.title', 'like', '%' . $keyword . '%');
        $eqsupplies = $eqsupplies->whereHas('eqsupplie_supplie', $supplie_query)
            ->orderby('equipment_supplies.supplie_id', 'asc')->paginate($number);
        $total = $eqsupplies->total();
        $data = [
            'user' => $user,
            'number' => $number,
            'total' => $total,
            'keyword' => $keyword,
            'supplie_id' => $supplie_id,
            'supplies' => $supplies,
            'departments' => $departments,
            'department_id' => $department_id,
            'eqsupplies' => $eqsupplies
        ];
        return view('backends.statistical.list_supplies', $data);
    }

    public function exportSupplies(Request $request)
    {
        $department_id = isset($request->department_id) ? $request->department_id : '';
        $supplie_id = isset($request->supplie_id) ? $request->supplie_id : '';
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new EquipmentStatisticsBySuppliesExport($department_id, $supplie_id, $key), 'Thống kê vật tư ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

    public function jvContract(){
        $equipments = Equipment::with("equipment_department", "equipment_unit");

        $current_date = Carbon::now()->format("Y-m-d");
        $_GET["current_date"] = $_GET["current_date"] ?? $current_date;
        $_GET["searchKeyword"] = $_GET["searchKeyword"] ?? "";
        $equipments = $equipments->title($_GET["searchKeyword"])->jvContract($_GET["current_date"]);
        $equipments = $equipments->paginate(10);

        return view("backends.statistical.list_jv_contract",
        [
            "equipments" => $equipments,
            "current_date" => $_GET["current_date"],
            "searchKeyword" => $_GET["searchKeyword"],
        ]);
    }
    public function jvContractExport(){
        $equipments = Equipment::with("equipment_department", "equipment_unit");

        $current_date = Carbon::now()->format("Y-m-d");
        $_GET["current_date"] = $_GET["current_date"] ?? $current_date;
        $_GET["searchKeyword"] = $_GET["searchKeyword"] ?? "";

        return Excel::download(new EquipmentStatisticsByJvContractExport($_GET["current_date"], $_GET["searchKeyword"]),
            "Thống kê thiết bị theo HĐ LDLK.xlsx" );

    }
}
