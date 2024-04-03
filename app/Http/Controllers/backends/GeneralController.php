<?php
namespace App\Http\Controllers\backends;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\Cates;
use App\Models\Device;
use App\Models\Eqsupplie;
use App\Models\Supplie;
use App\Models\Liquidation;
use App\Models\Provider;
use App\Models\Transfer;
use App\Models\Maintenance;
use App\Models\MaintenanceAction;
use App\Models\ScheduleRepair;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportDeviceImportList;
use App\Exports\ExportMaterialImportList;
use App\Exports\ExportRepairRequestList;
use App\Exports\ExportLiquidationList;
use App\Exports\ExportSupplieDepartmentList;
use App\Exports\ExportTransferEquipmentList;
use App\Exports\ExportMaintenanceEquipmentList;
use Carbon\Carbon;
class GeneralController extends Controller {
	public function inputDepartment(Request $request){
        $user = Auth::user();
        if($user->can('general.equipment')){
    		$keyword = isset($request->key) ? $request->key : '';
    		$departments_id = isset($request->departments_id) ? $request->departments_id : '';
    		$provider_id = isset($request->provider_id) ? $request->provider_id : '';
            $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m').'-01';
            $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-d') : Carbon::now()->format('Y-m-d');
    		$departments = Department::select('id','title')->get();
    		$providers = Provider::select('id','title')->get();
    		$number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
            $equipments = Equipment::query();
            $equipments_query = function ($query) use ($departments_id) {
                return $query->select('departments.id','departments.title')->where('departments.id',$departments_id);
            };
            $provider_query = function ($query) use ($provider_id) {
                return $query->select('providers.id','providers.title')->where('providers.id',$provider_id);
            };
            if($startDate == ''){
                $equipments= $equipments->whereDate('warehouse', '<=', $endDate);
            }else{
                $equipments= $equipments->whereDate('warehouse', '>=', $startDate)->whereDate('warehouse', '<=', $endDate);
            }
            if($keyword != '') $equipments= $equipments->where('equipments.title','like','%'.$keyword.'%');
            if($departments_id != '') $equipments= $equipments->whereHas('equipment_department', $equipments_query);
            if($provider_id != '')$equipments= $equipments->whereHas('equipment_provider', $provider_query);
            $equipments= $equipments->orderby('department_id','asc')->paginate($number);
            $total= $equipments->total();
    		$data=[
                'keyword'=>$keyword,
                'departments_id'=>$departments_id,
                'provider_id'=>$provider_id,
                'startDate'=>$startDate,
                'endDate'=>$endDate,
                'departments'=>$departments,
                'providers'=>$providers,
                'equipments'=>$equipments,
                'number'=>$number,
                'total'=>$total,
            ];
    		return view('backends.general.input_department',$data);
        }else{ abort(403); }
	}
    public function exportInputDepartment(Request $request) {
        $departments_id = isset($request->departments_id) ? $request->departments_id : '';
        $provider_id = isset($request->provider_id) ? $request->provider_id : '';
        $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m').'-01';
        $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-d') : Carbon::now()->format('Y-m-d');
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new ExportDeviceImportList($departments_id,$provider_id,$startDate,$endDate,$key), 'Báo cáo bảng kê nhập thiết bị ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }
	public function inputSupplies(Request $request){
        $user = Auth::user();
        if($user->can('general.supplie')){
    		$keyword = isset($request->key) ? $request->key : '';
    		$supplie_id = isset($request->supplie_id) ? $request->supplie_id : '';
    		$provider_id = isset($request->provider_id) ? $request->provider_id : '';
            $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m').'-01';
            $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-d') : Carbon::now()->format('Y-m-d');
    		$supplies = Supplie::select('id','title')->get();
    		$providers = Provider::select('id','title')->get();
    		$number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
            $eqsupplies = Eqsupplie::query();
            $supplie_query = function ($query) use ($supplie_id){
                return $query->select('supplies.id','supplies.title')->where('supplies.id',$supplie_id);
            };
            $provider_query = function ($query) use ($provider_id) {
                return $query->select('providers.id','providers.title')->where('providers.id',$provider_id);
            };
            if($startDate == ''){
                $eqsupplies= $eqsupplies->whereDate('warehouse', '<=', $endDate);
            }else{
                $eqsupplies= $eqsupplies->whereDate('warehouse', '>=', $startDate)->whereDate('warehouse', '<=', $endDate);
            }
            if($keyword != '') $eqsupplies= $eqsupplies->where('equipment_supplies.title','like','%'.$keyword.'%');
            if($supplie_id != '') $eqsupplies= $eqsupplies->whereHas('eqsupplie_supplie', $supplie_query);
            if($provider_id != '')$eqsupplies= $eqsupplies->whereHas('eqsupplie_provider', $provider_query);
            $eqsupplies= $eqsupplies->orderby('supplie_id','asc')->paginate($number);
            $total= $eqsupplies->total();
    		$data=[
                'keyword'=>$keyword,
                'supplie_id'=>$supplie_id,
                'provider_id'=>$provider_id,
                'startDate'=>$startDate,
                'endDate'=>$endDate,
                'supplies'=>$supplies,
                'providers'=>$providers,
                'eqsupplies'=>$eqsupplies,
                'number'=>$number,
                'total'=>$total,
            ];
    		return view('backends.general.input_supplies',$data);
        }else{ abort(403); }
	}
    public function exportInputSupplie(Request $request) {
        $supplie_id = isset($request->supplie_id) ? $request->supplie_id : '';
        $provider_id = isset($request->provider_id) ? $request->provider_id : '';
        $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m').'-01';
        $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-d') : Carbon::now()->format('Y-m-d');
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new ExportMaterialImportList($supplie_id,$provider_id,$startDate,$endDate,$key), 'Báo cáo bảng kê nhập vật tư ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }
    public function Liquidations(Request $request) {
        $user = Auth::user();
        if($user->can('general.liquidation')){
            $keyword = isset($request->key) ? $request->key : '';
            $status_key = isset($request->status_key) ? $request->status_key : '';
            $departments_id = isset($request->departments_id) ? $request->departments_id : '';
            $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m').'-01';
            $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-d') : Carbon::now()->format('Y-m-d');
            $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
            $departments = Department::select('id','title')->get();
            $equipments = Equipment::query();
            $equipments_query = function ($query) use ($departments_id) {
                return $query->select('departments.id','departments.title')->where('departments.id',$departments_id);
            };
            $liqui_query = function ($query) use ($startDate, $endDate) {
                if($startDate == '') return $query->whereDate('liquidations.created_at', '<=', $endDate);
                else return $query->whereDate('liquidations.created_at', '>=', $startDate)->whereDate('liquidations.created_at', '<=', $endDate);
            };
            if($keyword != '') $equipments= $equipments->where('equipments.title','like','%'.$keyword.'%');
            if($departments_id != '')$equipments= $equipments->whereHas('equipment_department', $equipments_query);
            $equipments = $equipments->withCount('liquidations')->whereHas('liquidations', $liqui_query)->paginate($number);
            $total= $equipments->total();
            $data=[
                'keyword'=>$keyword,
                'status_key'=>$status_key,
                'departments_id'=>$departments_id,
                'startDate'=>$startDate,
                'endDate'=>$endDate,
                'departments'=>$departments,
                'equipments'=>$equipments,
                'number'=>$number,
                'total'=>$total,
            ];
            return view('backends.general.list_liquidation',$data);
        }else{ abort(403); }
    }
    public function exportLiquidations(Request $request) {
        $departments_id = isset($request->departments_id) ? $request->departments_id : '';
        $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m').'-01';
        $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-d') : Carbon::now()->format('Y-m-d');
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new ExportLiquidationList($departments_id,$startDate,$endDate,$key), 'Báo cáo bảng kê thanh lý thiết bị' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }
    public function scheduleRepairs(Request $request){
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $department_id = isset($request->department_id) ? $request->department_id : '';
        $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m').'-01';
        $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-d') : Carbon::now()->format('Y-m-d');
        $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
        $repairs = ScheduleRepair::query();
        if($startDate == ''){
            $repairs = $repairs->whereDate('schedule_repairs.planning_date', '<=', $endDate);
        }else{
            $repairs = $repairs->whereDate('schedule_repairs.planning_date', '>=', $startDate)->whereDate('schedule_repairs.planning_date', '<=', $endDate);
        }
        $equipments_query = function ($query) use ($keyword) {
            return $query->select('equipments.id','equipments.title')->where('equipments.title','like','%'.$keyword.'%');
        };
        if($keyword != '') $repairs= $repairs->whereHas('equipment', $equipments_query);
        if($user->can('general.show_all') && $user->can('general.repair')){
            $departments = Department::select('id','title')->get();
            $depart_query = function ($query) use ($department_id) {
                return $query->select('departments.id','departments.title')->where('departments.id',$department_id);
            };
            if($department_id != '')$repairs= $repairs->whereHas('equipment.equipment_department',$depart_query);

        }elseif($user->can('general.repair')) {
            $department_id = $user->department_id;
            $departments = Department::where('id',$department_id)->select('id','title')->first();
            $repairs= $repairs->whereHas('equipment.equipment_department', function ($query) use ($department_id) {
                $query->where('departments.id',$department_id);
            });
        }else{
            abort(403);
        }
        $repairs = $repairs->orderby('planning_date', 'asc')->paginate($number);
        $total= $repairs->total();
        $data=[
            'user'=>$user,
            'keyword'=>$keyword,
            'department_id'=>$department_id,
            'startDate'=>$startDate,
            'endDate'=>$endDate,
            'departments'=>$departments,
            'repairs'=>$repairs,
            'number'=>$number,
            'total'=>$total,
        ];
        //dd($data['repairs'][0]->equipment->equipment_department);
        return view('backends.general.schedule_repairs',$data);
    }
    public function exportScheduleRepairs(Request $request) {
        $department_id = isset($request->department_id) ? $request->department_id : '';
        $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m').'-01';
        $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-d') : Carbon::now()->format('Y-m-d');
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new ExportRepairRequestList($department_id,$startDate,$endDate,$key), 'Báo cáo bảng kê yêu cầu sửa chữa ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

    public function maintenanceEquipment(Request $request){
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $department_id = isset($request->department_id) ? $request->department_id : '';
        $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m').'-01';
        $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-d') : Carbon::now()->format('Y-m-d');
        $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
        $equipments = Equipment::query();
        if($keyword != '') $equipments= $equipments->where('equipments.title','like','%'.$keyword.'%');
        $maint_query = function ($query) use ($startDate, $endDate) {
            if($startDate == '') return $query->whereDate('maintenances.start_date', '<=', $endDate);
            else return $query->whereDate('maintenances.start_date', '>=', $startDate)->whereDate('maintenances.start_date', '<=', $endDate);
        };
        if($user->can('general.show_all') && $user->can('general.maintenance')){
            $departments = Department::select('id','title')->get();
            $equipments_query = function ($query) use ($department_id) {
                return $query->select('departments.id','departments.title')->where('departments.id',$department_id);
            };
            if($department_id != '')$equipments= $equipments->whereHas('equipment_department', $equipments_query);

        }elseif($user->can('general.maintenance')) {
            $department_id = $user->department_id;
            $departments = Department::where('id',$department_id)->select('id','title')->first();
            $equipments= $equipments->whereHas('equipment_department', function ($query) use ($department_id) {
                $query->where('departments.id',$department_id);
            });
        }else{ abort(403); }
        $equipments = $equipments->withCount('maintenances')->whereHas('maintenances', $maint_query)->paginate($number);
        $total= $equipments->total();
        $data=[
            'user'=>$user,
            'keyword'=>$keyword,
            'department_id'=>$department_id,
            'startDate'=>$startDate,
            'endDate'=>$endDate,
            'departments'=>$departments,
            'equipments'=>$equipments,
            'number'=>$number,
            'total'=>$total,
        ];
        return view('backends.general.list_maintenance',$data);
    }
    public function exportMaintenanceEquipment(Request $request){
        $department_id = isset($request->department_id) ? $request->department_id : '';
        $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m').'-01';
        $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-d') : Carbon::now()->format('Y-m-d');
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new ExportMaintenanceEquipmentList($department_id, $startDate, $endDate, $key), 'Báo cáo bảng kê yêu cầu bảo dưỡng' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }
    public function transferEquipment(Request $request){
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $status_key = isset($request->status_key) ? $request->status_key : '';
        $department_id = isset($request->department_id) ? $request->department_id : '';
        $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m').'-01';
        $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-d') : Carbon::now()->format('Y-m-d');
        $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
        $transfers = Transfer::query();
        if($startDate == '') $transfers= $transfers->whereDate('transfers.created_at', '<=', $endDate);
            else $transfers= $transfers->whereDate('transfers.created_at', '>=', $startDate)->whereDate('transfers.created_at', '<=', $endDate);
        if($status_key != '') $transfers= $transfers->where('transfers.status', $status_key);
        $eq_query = function ($query) use ($keyword) {
            return $query->select('equipments.id','equipments.title')->where('equipments.title','like','%'.$keyword.'%');
        };
        if($keyword != '') $transfers= $transfers->whereHas('transfer_equipment', $eq_query);

        if($user->can('general.show_all') && $user->can('general.transfer')){
            $departments = Department::select('id','title')->get();
            $depart_query = function ($query) use ($department_id) {
                return $query->select('id','title')->where('departments.id', $department_id);
            };
            if($department_id != '') $transfers= $transfers->whereHas('transfer_department', $depart_query);

        }elseif($user->can('general.transfer')) {
            $department_id = $user->department_id;
            $departments = Department::where('id',$department_id)->select('id','title')->first();
            $transfers= $transfers->whereHas('transfer_department', function ($query) use ($department_id) {
                $query->where('departments.id',$department_id);
            });
        }else{ abort(403); }
        $transfers = $transfers->orderBy('transfers.status','desc')->paginate($number);
        $total= $transfers->total();
        $data=[
            'user'=>$user,
            'keyword'=>$keyword,
            'status_key'=>$status_key,
            'department_id'=>$department_id,
            'startDate'=>$startDate,
            'endDate'=>$endDate,
            'departments'=>$departments,
            'transfers'=>$transfers,
            'number'=>$number,
            'total'=>$total,
        ];
        return view('backends.general.list_transfer',$data);
    }
    public function exportTransferEquipment(Request $request){
        $department_id = isset($request->department_id) ? $request->department_id : '';
        $status_key = isset($request->status_key) ? $request->status_key : '';
        $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m').'-01';
        $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-d') : Carbon::now()->format('Y-m-d');
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new ExportTransferEquipmentList($department_id, $status_key, $startDate, $endDate, $key), 'Báo cáo bảng kê điều chuyển thiết bị' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }
    public function suppliesDepartment(Request $request) {
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $supplie_id = isset($request->supplie_id) ? $request->supplie_id : '';
        $department_id = isset($request->department_id) ? $request->department_id : '';
        $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m').'-01';
        $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-d') : Carbon::now()->format('Y-m-d');
        $supplies = Supplie::select('id','title')->get();
        $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
        $eqsupplies = Eqsupplie::query();
        $supplie_query = function ($query) use ($supplie_id){
            return $query->select('supplies.id','supplies.title')->where('supplies.id',$supplie_id);
        };
        if($startDate == '') $eqsupplies= $eqsupplies->whereDate('warehouse', '<=', $endDate);
            else $eqsupplies= $eqsupplies->whereDate('warehouse', '>=', $startDate)->whereDate('warehouse', '<=', $endDate);

        if($keyword != '') $eqsupplies= $eqsupplies->where('equipment_supplies.title','like','%'.$keyword.'%');
        if($supplie_id != '') $eqsupplies= $eqsupplies->whereHas('eqsupplie_supplie', $supplie_query);
        if($user->can('general.show_all') && $user->can('general.supplie_department')){
            $departments = Department::select('id','title')->get();
            $depart_query = function ($query) use ($department_id) {
                return $query->select('id','title')->where('departments.id', $department_id);
            };
            if($department_id != '')$eqsupplies= $eqsupplies->whereHas('supplie_devices.equipment_department', $depart_query);

        }elseif($user->can('general.supplie_department')) {
            $department_id = $user->department_id;
            $departments = Department::where('id',$department_id)->select('id','title')->first();
            $eqsupplies= $eqsupplies->whereHas('supplie_devices.equipment_department', function ($query) use ($department_id) {
                $query->where('departments.id',$department_id);
            });
        }else{ abort(403); }
        $eqsupplies= $eqsupplies->orderby('supplie_id','asc')->paginate($number);
        $total= $eqsupplies->total();
        $data=[
            'user'=>$user,
            'keyword'=>$keyword,
            'supplie_id'=>$supplie_id,
            'department_id'=>$department_id,
            'startDate'=>$startDate,
            'endDate'=>$endDate,
            'supplies'=>$supplies,
            'departments'=>$departments,
            'eqsupplies'=>$eqsupplies,
            'number'=>$number,
            'total'=>$total,
        ];
        return view('backends.general.supplie_department',$data);
    }
    public function exportSupplieDepartment(Request $request) {
        $department_id = isset($request->department_id) ? $request->department_id : '';
        $supplie_id = isset($request->supplie_id) ? $request->supplie_id : '';
        $startDate = isset($request->startDate) ? date_format(date_create($request->startDate), 'Y-m-d') : Carbon::now()->format('Y-m').'-01';
        $endDate = isset($request->endDate) ? date_format(date_create($request->endDate), 'Y-m-d') : Carbon::now()->format('Y-m-d');
        $key = isset($request->key) ? $request->key : '';
        return Excel::download(new ExportSupplieDepartmentList($department_id, $supplie_id, $startDate, $endDate, $key), 'Báo cáo bảng kê vật tư theo khoa phòng' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }

}
