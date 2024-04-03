<?php

namespace App\Http\Controllers\backends;

//include $_SERVER['DOCUMENT_ROOT'] . "\..\app\helpers\MaintenanceHelper.php";
$path = realpath(__DIR__ . '/../../../helpers/MaintenanceHelper.php');

if(env("APP_ENV") == "production"){
    include $path;
}
else{
    include $path;
}


use App\Exports\ExportMaintenanceList;
use App\Http\Controllers\Controller;
use App\Models\Cates;
use App\Models\Department;
use App\Models\Device;
use App\Models\Equipment;
use App\Models\Maintenance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MaintenanceController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $time_nextMainte = isset($request->time_nextMainte) ? $request->time_nextMainte : '';

        $start = date_format(date_create($time_nextMainte), 'Y-m-d');
        $end = date("Y-m-d", strtotime("+" . "1" . "months", strtotime($start)));


        $keyword = isset($request->key) ? $request->key : '';
        $status = isset($request->status) ? $request->status : '';
        $departments_key = isset($request->department_key) ? $request->department_key : '';
        $cates_key = isset($request->cate_key) ? $request->cate_key : '';
        $devices_key = isset($request->device_key) ? $request->device_key : '';
        $user_name = User::select('id', 'name')->get();
        $cate_name = Cates::select('id', 'title')->get();
        $device_name = Device::select('id', 'title')->get();
        $equipments = Equipment::with('equipment_department');
        $equipments = $equipments->select(
            'id',
            'title',
            'code',
            'model',
            'serial',
            'hash_code',
            'department_id',
            'status',
            'cate_id',
            'devices_id',
            'regular_maintenance',
            'last_maintenance',
            'next_maintenance'
        );

        if ($time_nextMainte != '') {
            $equipments = $equipments->whereBetween('next_maintenance', [$start, $end]);
        }

        if ($keyword != '') {
            $equipments = $equipments->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('code', 'like', '%' . $keyword . '%')
                    ->orWhere('model', 'like', '%' . $keyword . '%')
                    ->orWhere('serial', 'like', '%' . $keyword . '%');
            });
            $data_link['keyword'] = $keyword;
        }
        if ($status != '') {
            $equipments = $equipments->where('status', $status);
        }

        if ($cates_key != '') {
            $equipments = $equipments->where('cate_id', $cates_key);
        }
        if ($devices_key != '') {
            $equipments = $equipments->where('devices_id', $devices_key);
        }
        if ($user->can('equipment.show_all')) {
            $department_name = Department::select('id', 'title')->get();
            if ($departments_key != '') {
                $equipments = $equipments->where('department_id', $departments_key);
            }
        } else {
            $department_name = Department::where('id', $user->department_id)->select('id', 'title')->get();
            $equipments = $equipments->where('department_id', $user->department_id);
            if ($departments_key != '') {
                $equipments = $equipments->where('department_id', $departments_key);
            }
        }

        $equipments = $equipments->whereNotIn('status', ['inactive', 'liquidated'])->orderBy('next_maintenance', 'desc')->paginate(20);

        $data = [
            'equipments' => $equipments,
            'time_nextMainte' => $time_nextMainte,
            'keyword' => $keyword,
            'status' => $status,
            'departments_key' => $departments_key,
            'cates_key' => $cates_key,
            'devices_key' => $devices_key,
            'department_name' => $department_name,
            'cate_name' => $cate_name,
            'device_name' => $device_name,
        ];
        return view('backends.equipments.maintenances', $data);
    }


    public function exportEquipMainte(Request $request)
    {
        $keyword = isset($request->keyword) ? $request->keyword : '';
        $departments_key = isset($request->departments_key) ? $request->departments_key : '';
        $status = isset($request->status) ? $request->status : '';
        $cates_key = isset($request->cates_key) ? $request->cates_key : '';
        $devices_key = isset($request->devices_key) ? $request->devices_key : '';
        $time_nextMainte = isset($request->time_nextMainte) ? $request->time_nextMainte : '';
        // dd($request);
        $department_name = "";
        if ($departments_key != '') {
            $department_name = Department::findOrFail($departments_key)->title;
        }

        return Excel::download(new ExportMaintenanceList(
            $keyword,
            $departments_key,
            $status,
            $cates_key,
            $devices_key,
            $time_nextMainte
        ), 'Danh sách thiết bị ' . $department_name . ' bảo dưỡng tiếp theo : ' . $time_nextMainte . '.xlsx');
    }

    public function create(Request $request, $equip_id)
    {
        $user = Auth::user();
        if ($user->can('create', Maintenance::class)) {
            $equipment = Equipment::select('id', 'title', 'code', 'model', 'serial')->findOrFail($equip_id);
            $maintenances = $equipment->maintenances->sortBy('created_at')->simplePaginate(10);
            $data = [
                'equipment' => $equipment,
                'maintenances' => $maintenances,
                // 'frequency' => generate_frequency(),
            ];
            return view('backends.equipments.maintenance_create', $data);
        } else {
            abort(403);
        }
    }

    public function store(Request $request, $equip_id)
    {
        $equipment = Equipment::findOrFail($equip_id);
        $rules = [
            'start_date' => 'required',
        ];
        $messages = [
            'start_date.required' => __('Nhập thời gian bảo dưỡng, bảo trì!'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) :
            return redirect()->route('equip_maintenance.create', ['equip_id' => $equip_id])->withErrors($validator)->withInput();
        else :
            // $array_frq = array_keys(generate_frequency());
            // $request['frequency'] = in_array($request->frequency, $array_frq) ? $request->frequency : $array_frq[0];
            $request['provider'] = $request->provider ?? "";
            $request['start_date'] = $request->start_date != NULL ? $request->start_date : Carbon::now()->format('Y-m-d');
            $request['author_id'] = Auth::id();

            $equipment['last_maintenance'] = $request->start_date;
            if ($equipment['regular_maintenance']) {

                $m = $equipment['regular_maintenance'];

                $equipment['next_maintenance'] = date("Y-m-d", strtotime("+" . $m . "months", strtotime($equipment['last_maintenance'])));
            };
            $equipment->save();


            $result = $equipment->maintenances()->create($request->only(['provider', 'start_date', 'note', 'author_id']));

            if ($result) {
                $request->session()->flash('success', 'Tạo thành công!');

                sendCreatedMaintenanceNotification($equipment);

                sendCreatedMaintenanceEmail($equipment);

            } else $request->session()->flash('error', 'Tạo thất bại!');
            return redirect()->route('equip_maintenance.create', ['equip_id' => $equip_id]);
        endif;
    }

    public function showHistories(Request $request, $equip_id)
    {
        $user = Auth::user();
        if ($user->can('create', Maintenance::class)) {
            $equipment = Equipment::select('id', 'title', 'code', 'model', 'serial')->findOrFail($equip_id);
            $maintenances = $equipment->maintenances->sortBy('created_at')->simplePaginate(10);
            $data = [
                'equipment' => $equipment,
                'maintenances' => $maintenances,
            ];
            return view('backends.equipments.maintenance_showHistories', $data);
        } else {
            abort(403);
        }
    }

    public function edit(Request $request, $equip_id, $main_id)
    {
        $user = Auth::user();
        $maintenance = Maintenance::findOrFail($main_id);
        if ($user->can('update', $maintenance)) {
            $equipment = Equipment::findOrFail($equip_id);
            $data = [
                'equipment' => $equipment,
                'maintenance' => $maintenance,
                // 'frequency'     => generate_frequency(),
            ];

            return view('backends.equipments.maintenance_edit', $data);
        } else {
            abort(403);
        }
    }

    public function update(Request $request, $equip_id, $main_id)
    {
        $equipment = Equipment::findOrFail($equip_id);
        $maintenance = Maintenance::findOrFail($main_id);
        $rules = [
            'start_date' => 'required',
        ];
        $messages = [
            'start_date.required' => __('Nhập thời gian bảo dưỡng, bảo trì!'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) :
            return redirect()->route('equip_maintenance.edit', ['equip_id' => $equip_id, 'main_id' => $main_id])->withErrors($validator)->withInput();
        else :
            $maintenance['provider'] = $request->provider ?? "";
            $maintenance->start_date = $request->start_date != NULL ? $request->start_date : Carbon::now()->format('Y-m-d');
            $maintenance->note = $request->note;
            if ($maintenance->save()) {
                if ($maintenance->wasChanged()) {
                    $equipment['last_maintenance'] = $request->start_date;
                    if ($equipment['regular_maintenance']) {

                        $m = $equipment['regular_maintenance'];

                        $equipment['next_maintenance'] = date("Y-m-d", strtotime("+" . $m . "months", strtotime($equipment['last_maintenance'])));
                    };
                    $equipment->save();

                    sendUpdatedMaintenanceNotification($equipment);

                    sendUpdatedMaintenanceEmail($equipment);


                    $request->session()->flash('success', 'Cập nhật thành công!');
                }
            } else $request->session()->flash('error', 'Cập nhật thất bại!');
            return redirect()->route('equip_maintenance.edit', ['equip_id' => $equip_id, 'main_id' => $main_id]);
        endif;
    }

    public function destroy(Request $request, $equip_id, $main_id)
    {
        $user = Auth::user();
        $maintenance = Maintenance::findOrFail($main_id);
        if ($user->can('delete', $maintenance)) {
            if ($maintenance->delete()) $request->session()->flash('success', 'Xoá thành công!');
            else $request->session()->flash('error', 'Xoá thất bại!');
            return redirect()->route('equip_maintenance.create', ['equip_id' => $equip_id]);
        } else {
            abort(403);
        }
    }
}
