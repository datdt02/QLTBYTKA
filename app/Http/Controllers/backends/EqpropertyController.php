<?php

namespace App\Http\Controllers\backends;

use App\Exports\EquipmentsExport;
use App\Http\Controllers\Controller;
use App\Imports\EquipmentsImport;
use App\Imports\UpdateEquipmentImport;
use App\Models\Cates;
use App\Models\Department;
use App\Models\Department_User;
use App\Models\Device;
use App\Models\Eqsupplie;
use App\Models\Eqproperty;
use App\Models\Equipment;
use App\Models\Project;
use App\Models\Provider;
use App\Models\Supplie;
use App\Models\Unit;
use App\Models\User;
use App\Notifications\HanOverNotifications;
use App\Notifications\ReportFailureNotifications;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Spatie\Activitylog\Models\Activity;

class EqpropertyController extends Controller
{
    public function convert()
    { // convert data to department_user table
        Department_User::truncate();
        $users = User::all();
        foreach ($users as $user) {
            $department_user = new Department_User;
            $department_user->user_id = $user->id;
            $department_user->department_id = $user->department_id;
            $department_user->save();
        }
    }

    public function change_critical_level()
    {
        # code...
        $equipments = Equipment::whereIn('status', ['was_broken', 'corrected'])->get();
        //$equipments['critical_level'] = 'Cần sửa';
        foreach ($equipments as $equipment) {
            $equipment['critical_level'] == "Bình thường" ? "Cần sửa" : "Cần sửa ngay";
            $equipment->update();
        }
        return redirect()->route('equipment.index');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $status = isset($request->status) ? $request->status : '';
        $departments_key = isset($request->department_key) ? $request->department_key : '';
        $cates_key = isset($request->cate_key) ? $request->cate_key : '';
        $devices_key = isset($request->device_key) ? $request->device_key : '';
        $user_name = User::select('id', 'name')->get();
        $cate_name = Cates::select('id', 'title')->get();
        $device_name = Device::select('id', 'title')->get();
        $number = isset($_GET['per_page']) ? ($_GET['per_page']) : '10';
        $equipments = Eqproperty::with('eqproperty_department');
        $cur_time = Carbon::now()->format('Y-m-d');
        $order = '';
        $sort = '';
        if ($keyword != '') {
            $equipments = $equipments->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('code', 'like', '%' . $keyword . '%')
                    ->orWhere('model', 'like', '%' . $keyword . '%')
                    ->orWhere('serial', 'like', '%' . $keyword . '%')
                    ->orWhere->hashCode($keyword);
            });
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
        if ($request->sortByTitle && in_array($request->sortByTitle, ['asc', 'a'])) {
            $equipments = $equipments->orderBy('title', $request->sortByTitle);
            $sort = 'sortByTitle';
            $order = $request->sortByTitle;
        }
        if ($request->sortByModel && in_array($request->sortByModel, ['asc', 'desc'])) {
            $equipments = $equipments->orderBy('model', $request->sortByModel);
            $sort = 'sortByModel';
            $order = $request->sortByModel;
        }
        if ($request->sortBySeria && in_array($request->sortBySeria, ['asc', 'desc'])) {
            $equipments = $equipments->orderBy('serial', $request->sortBySeria);
            $sort = 'sortBySeria';
            $order = $request->sortBySeria;
        }
        if ($request->sortByStatus && in_array($request->sortByStatus, ['asc', 'desc'])) {
            $equipments = $equipments->orderBy('status', $request->sortByStatus);
            $sort = 'sortByStatus';
            $order = $request->sortByStatus;
        }
        if ($request->sortByCode && in_array($request->sortByCode, ['asc', 'desc'])) {
            $equipments = $equipments->orderBy('code', $request->sortByCode);
            $sort = 'sortByCode';
            $order = $request->sortByCode;
        }
        if ($request->sortByDepartment && in_array($request->sortByDepartment, ['asc', 'desc'])) {
            $equipments = $equipments->orderBy('code', $request->sortByDepartment);
            $sort = 'sortByDepartment';
            $order = $request->sortByDepartment;
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
        $equipments = $equipments->orderBy('created_at', 'desc')->paginate($number);
        $total = $equipments->total();
        return view(
            'backends.properties.list',
            compact(
                'equipments',
                'keyword',
                'sort',
                'order',
                'status',
                'department_name',
                'departments_key',
                'cate_name',
                'cates_key',
                'device_name',
                'devices_key',
                'user_name',
                'cur_time',
                'user',
                'number',
                'total'
            )
        );
    }

    public function indexMedical(Request $request)
    {
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $status = isset($request->status) ? $request->status : '';
        $departments_key = isset($request->department_key) ? $request->department_key : '';
        $cates_key = isset($request->cate_key) ? $request->cate_key : '';
        $devices_key = isset($request->device_key) ? $request->device_key : '';
        $department_name = Department::select('id', 'title')->get();
        $user_name = User::select('id', 'name')->get();
        $cate_name = Cates::select('id', 'title')->get();
        $device_name = Device::select('id', 'title')->get();
        $equipments = Equipment::query();
        $order = '';
        $sort = '';
        if ($keyword != '') {
            $equipments = $equipments->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('code', 'like', '%' . $keyword . '%')
                    ->orWhere('model', 'like', '%' . $keyword . '%')
                    ->orWhere('serial', 'like', '%' . $keyword . '%');
            });
        }
        if ($status != '') {
            $equipments = $equipments->where('status', $status);
        }
        if ($departments_key != '') {
            $equipments = $equipments->where('department_id', $departments_key);
        }
        if ($cates_key != '') {
            $equipments = $equipments->where('cate_id', $cates_key);
        }
        if ($devices_key != '') {
            $equipments = $equipments->whereHas('equipment_device', function ($query) use ($devices_key) {
                $query->where('device_id', $devices_key);
            });
        }
        $equipments = $equipments->where('department_id', $user->department_id)->paginate(15);
        //dd($equipments);
        return view(
            'backends.equipments.mediacal',
            compact(
                'equipments',
                'keyword',
                'sort',
                'order',
                'status',
                'department_name',
                'departments_key',
                'cate_name',
                'cates_key',
                'device_name',
                'devices_key',
                'user_name',
                'user',
            )
        );
    }

    public function indexGuarantee(Request $request)
    {
        $keyword = isset($request->key) ? $request->key : '';
        $equipments = Equipment::query();
        if ($keyword != '') {
            $equipments = $equipments->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('code', 'like', '%' . $keyword . '%')
                    ->orWhere('model', 'like', '%' . $keyword . '%')
                    ->orWhere('serial', 'like', '%' . $keyword . '%');
            });
        }
        $equipments = $equipments->orderBy('created_at', 'desc')->paginate(15);
        return view('backends.guarantees.list', compact('equipments', 'keyword'));
    }

    public function showHistory()
    {
        $user = Auth::user();
        if ($user->can('history_status.read')) {
            $activities = Activity::where("description", "updated")
                ->where("subject_type", "App\Models\Equipment")
                ->whereJsonContains('properties->attributes->type', 'devices')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            return view('backends.equipments.history', compact('activities'));
        } else {
            abort(403);
        }
    }

    public function destroyHistory($id)
    {
        $user = Auth::user();
        if ($user->can('history_status.delete')) {
            $activities = Activity::findOrFail($id);
            $activities->delete();
            return redirect()->route('equipment.history')->with('success', 'Xóa thành công');
        } else {
            abort(403);
        }
    }

    public function deleteChooseHistory(Request $request)
    {
        $items = explode(",", $request->items);
        if (count($items) > 0) {
            $request->session()->flash('success', 'Xóa thành công!');
            Activity::destroy($items);
        } else {
            $request->session()->flash('error', 'Có lỗi!');
        }
        return redirect()->route('equipment.history');
    }

    public function show(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->can('eqproperty.show')) {
            $equipments = Eqproperty::findOrFail($id);
            $activities = Activity::with('causer')->where("subject_type", "App\Models\Eqproperty")
                ->where("subject_id", $equipments->id)->orderBy('created_at', 'desc')->paginate(30);
            return view('backends.properties.show', compact('equipments', 'activities'));
        } else {
            abort(403);
        }
    }

    public function showPdf($id)
    {
        $equipments = Eqproperty::findOrFail($id);
        $activities = Activity::where("subject_type", "App\Models\Eqproperty")->where("subject_id", $equipments->id)->orderBy('created_at', 'desc')->get();
        $pdf = PDF::loadView('backends.equipments.pdf', compact('equipments', 'activities'));
        return $pdf->download('' . $equipments->title . '.pdf');
        //return view('backends.equipments.pdf', compact('equipments', 'activities'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->can('create', Eqproperty::class)) {
            $maintenances = Provider::select('id', 'title', 'type')->maintenance()->get();
            $providers = Provider::select('id', 'title', 'type')->provider()->get();
            $repairs = Provider::select('id', 'title', 'type')->repair()->get();
            $users = User::select('id', 'name')->get();
            $users_vt = User::select('id', 'name')->where('department_id', $user->department_id)->get();
            $cates = Cates::select('id', 'title')->get();
            $units = Unit::select('id', 'title')->get();
            $departments = Department::select('id', 'title')->get();
            $devices = Device::select('id', 'title')->get();
            $projects = Project::select('id', 'title')->get();
            $cur_day = Carbon::now()->format('Y-m-d');
            return view('backends.properties.create', compact(
                'maintenances',
                'providers',
                'repairs',
                'users',
                'cates',
                'units',
                'departments',
                'devices',
                'projects',
                'cur_day',
                'users_vt'
            ));
        } else {
            abort(403);
        }
    }

    public function createSupplie($id)
    {
        $user = Auth::user();
        if ($user->can('equipment.create_supplie')) {
            $equipments = Equipment::findOrFail($id);
            $maintenances = Provider::select('id', 'title', 'type')->maintenance()->get();
            $providers = Provider::select('id', 'title', 'type')->provider()->get();
            $repairs = Provider::select('id', 'title', 'type')->repair()->get();
            $users = User::select('id', 'name')->get();
            $units = Unit::select('id', 'title')->get();
            $departments = Department::select('id', 'title')->get();
            $supplies = Supplie::select('id', 'title')->get();
            $cur_day = Carbon::now()->format('Y-m-d');
            return view('backends.equipments.createsupplie', compact(
                'maintenances',
                'providers',
                'repairs',
                'users',
                'units',
                'departments',
                'supplies',
                'equipments',
                'cur_day'
            ));
        } else {
            abort(403);
        }
    }

    public function storeSupplie(Request $request)
    {
        $rules = [
            'title' => 'required',
            'supplie_id' => 'required',
            'amount' => 'required|min:0',
            'unit_id' => 'required',
            'import_price' => 'required',
            'used_amount' => 'numeric|max:' . intval($request->amount) . '| min:0',
        ];
        $messages = [
            'title.required' => 'Vui lòng nhập tên thiết bị!',
            'supplie_id.required' => 'Vui lòng nhập loại vật tư!',
            'amount.required' => 'Vui lòng nhập số lượng!',
            'amount.min' => 'Vui lòng nhập số lượng không được nhỏ hơn 0 !',
            'unit_id.required' => 'Vui lòng nhập đơn vị tính!',
            'import_price.required' => 'Vui lòng nhập giá nhập!',
            'used_amount.max' => 'Số lượng dùng không được nhập vượt quá số lượng !',
            'used_amount.min' => 'Số lượng dùng không được nhập nhỏ hơn 0 !',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) :
            return redirect()->back()->withErrors($validator)->withInput();
        else :
            $atribute = $request->all();
            $eqsupplies = Eqsupplie::create($atribute);
            $eqsupplies->save();
            // mã code
            $padded_supplie_id = Str::padLeft($eqsupplies->id, 5, 'VT');
            $newYear = Carbon::now()->format('dmY');
            $eqsupplies['code'] = $newYear . '-' . $padded_supplie_id;
            $eqsupplies->save();
            // n-n
            $eqsupplies->supplie_devices()->attach($request->supplie_devices, ['note' => 'spelled_by_device', 'amount' => $request->used_amount, 'user_id' => Auth::user()->id]);
            if ($eqsupplies) {
                return redirect()->back()->with('success', 'Thêm thành công');
            } else {
                return redirect()->back()->with('success', 'Thêm không thành công');
            }
        endif;
    }

    public function store(Request $request)
    {

        //dd($request);
        $rules = [
            'title' => 'required',
            // 'status'=>'required',
            'unit_id' => 'required',
            'amount' => 'required|numeric|min:0',
            'serial' => 'required|unique:equipments,serial',
            'code' => 'unique:equipments,code',
            'model' => 'required',
            'manufacturer' => 'required',
            'origin' => 'required',
            'year_manufacture' => 'required',
            // 'regular_inspection' => 'required',
        ];
        $messages = [
            'title.required' => 'Vui lòng nhập tên thiết bị!',
            //'status.required'=>'Vui lòng chọn trạng thái của thiết bị!',
            'unit_id.required' => 'Vui lòng nhập đơn vị tính!',
            'amount.unique' => 'Vui lòng nhập số lượng!',
            'code.unique' => 'Mã hoá TB đã tổn tại!',
            'amount.min' => 'Số lượng không được nhỏ hơn 0!',
            'serial.required' => 'Vui lòng nhập số serial !',
            'serial.unique' => 'Số serial đã tồn tại !',
            'model.required' => 'Vui lòng nhập model!',
            'manufacturer.required' => 'Vui lòng nhập hãng sản xuất!',
            'origin.required' => 'Vui lòng nhập xuất xứ!',
            'year_manufacture.required' => 'Vui lòng nhập năm sản xuất!',
            // 'regular_inspection.required'=>'Vui lòng nhập kiểm định định kỳ!',
            'first_value.min' => 'Giá trị ban đầu không được nhỏ hơn 0!',
            'first_value.max' => 'Giá trị ban đầu không được lớn hơn 100!',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) :
            return redirect()->route('equipment.create')->withErrors($validator)->withInput();
        else :
            $atribute = $request->all();

            $atribute['status'] = 'not_handed';

            $atribute['department_id'] = Auth::user()->department_id;
            $equipments = Eqproperty::create($atribute);
            $cates = Cates::where('id', $request->cate_id)->select("id", "code")->first();
            $devices = Device::where('id', $request->devices_id)->select("id", "code")->first();
            $padded_cates = Str::padLeft(isset($cates->code) ? $cates->code : '', 1, 'X');
            $padded_devices = Str::padLeft(isset($devices->code) ? $devices->code : '', 6, 'X');
            $padded_equipments_id = Str::padLeft($equipments->id, 6, 'X');
            $newYear = Carbon::now()->format('dmY');

            // $equipments["regular_inspection"] = $atribute["regular_inspection"];
            $equipments["regular_maintenance"] = $atribute["regular_maintenance"];
            $equipments['code'] = $padded_cates . '-' . $padded_devices . '-' . $newYear . '-' . $padded_equipments_id;

            $equipments->save();
            // Attachment
            $user = Auth::user();
            if ($request->attachment && $request->attachment != '' && is_array(explode(',', $request->attachment))) {
                $attachments = array_filter(explode(',', $request->attachment));
                if (!$user->can('media.read')) {
                    foreach ($attachments as $item) {
                        if (!$user->medias->contains($item)) $attachments = array_diff($attachments, [$item]);
                    }
                }
                $equipments->attachments()->attach($attachments);
            }
            if ($equipments) {
                return redirect()->route('eqproperty.index')->with('success', 'Thêm thành công');
            } else {
                return redirect()->route('eqproperty.index')->with('error', 'Thêm không thành công');
            }
        endif;
    }

    public function edit($id)
    {
        $user = Auth::user();
        $equipments = Eqproperty::with('attachments:id,title,path,type')->findOrFail($id);
        // echo '<pre>';
        // var_dump($user->can('update', $equipments));
        // echo '</pre>';
        //     die();
        if ($user->can('update', $equipments)) {
            $maintenances = Provider::select('id', 'title', 'type')->maintenance()->get();
            $providers = Provider::select('id', 'title', 'type')->provider()->get();
            $repairs = Provider::select('id', 'title', 'type')->repair()->get();
            $users = User::select('id', 'name')->get();
            $cates = Cates::select('id', 'title')->get();
            $units = Unit::select('id', 'title')->get();
            $departments = Department::select('id', 'title')->get();
            $devices = Device::select('id', 'title')->get();
            $projects = Project::select('id', 'title')->get();
            $cur_day = Carbon::now()->format('Y-m-d');
            $users_vt = User::select('id', 'name')->where('department_id', $user->department_id)->get();
            // var_dump($equipments->equipment_user_use);
            // die();
            $array_user_use = '';
            if(!empty($equipments->equipment_user_use)) {
                $array_user_use = $equipments->equipment_user_use->pluck('id')->toArray();
            }
            $array_user_training = $equipments->equipment_user_training ? $equipments->equipment_user_training->pluck('id')->toArray() : '';
            return view('backends.properties.edit', compact(
                'equipments',
                'maintenances',
                'providers',
                'repairs',
                'users',
                'cates',
                'units',
                'departments',
                'devices',
                'array_user_use',
                'array_user_training',
                'projects',
                'cur_day',
                'users_vt'
            ));
        } else {
            abort(403);
        }
    }

    public function update(Request $request, $id)
    {
        $equipments = EqProperty::findOrFail($id);
        $rules = [
            'title' => 'required',
            'unit_id' => 'required',
            'amount' => 'required|numeric|min:0',
            'serial' => ['required', Rule::unique('equipments')->ignore($equipments->id)],
            'code' => [Rule::unique('equipments')->ignore($equipments->id)],
            'model' => 'required',
            'manufacturer' => 'required',
            'origin' => 'required',
            'year_manufacture' => 'required',
            // 'regular_inspection' => 'required',
        ];
        $messages = [
            'title.required' => 'Vui lòng nhập tên thiết bị!',
            'unit_id.required' => 'Vui lòng nhập đơn vị tính!',
            'amount.min' => 'Số lượng không được nhỏ hơn 0!',
            'serial.required' => 'Vui lòng nhập số serial !',
            'serial.unique' => 'Số serial đã tồn tại !',
            'code.unique' => 'Mã hoá TB đã tồn tại !',
            'model.required' => 'Vui lòng nhập model!',
            'manufacturer.required' => 'Vui lòng nhập hãng sản xuất!',
            'origin.required' => 'Vui lòng nhập xuất xứ!',
            'year_manufacture.required' => 'Vui lòng nhập năm sản xuất!',
            // 'regular_inspection.required'=>'Vui lòng nhập kiểm định định kỳ!',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) :
            return redirect()->route('eqproperty.edit', $id)->withErrors($validator)->withInput();
        else :
            $atribute = $request->all();
            

            $equipments->update($atribute);

            // dd($equipments);
            $equipments["regular_inspection"] = $atribute["regular_inspection"];
            $equipments["regular_maintenance"] = $atribute["regular_maintenance"];
            $cates = Cates::where('id', $request->cate_id)->select("id", "code")->first();
            $devices = Device::where('id', $request->devices_id)->select("id", "code")->first();
            $padded_cates = Str::padLeft(isset($cates->code) ? $cates->code : '', 1, 'X');
            $padded_devices = Str::padLeft(isset($devices->code) ? $devices->code : '', 6, 'X');
            $padded_equipments_id = Str::padLeft($equipments->id, 6, 'X');
            $newYear = Carbon::now()->format('dmY');
            $equipments['code'] = $padded_cates . '-' . $padded_devices . '-' . $newYear . '-' . $padded_equipments_id;

            $equipments->save();
            // $equipments->equipment_user_use()->sync($request->equipment_user_use);
            // $equipments->equipment_user_training()->sync($request->equipment_user_training);
            if ($equipments) {
                if ($equipments->wasChanged()) {

                    return redirect()->route('eqproperty.edit', $id)->with('success', 'Cập nhật thành công');
                }
                else
                    return redirect()->route('eqproperty.edit', $id);
            } else {
                return redirect()->route('eqproperty.edit', $id)->with('error', 'Cập nhật không thành công');
            }
        endif;
    }

    public function updateHandOver(Request $request, $id)
    {
        $rules = [
            'department_id' => 'required',
            'officer_department_charge_id' => 'required',
        ];
        $messages = [
            'department_id.required' => 'Vui lòng nhập khoa phòng ban !',
            'officer_department_charge_id.required' => 'Vui lòng chọn người phụ trách khoa !',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) :
            return redirect()->back()->withErrors($validator)->withInput();
        else :
            $equipments = Eqproperty::findOrFail($id);
            $equipments->department_id = $request->department_id;
            $equipments->officer_department_charge_id = $request->officer_department_charge_id;
            $equipments->date_delivery = $request->date_delivery;
            $equipments['status'] = "active";
            $equipments->save();
            $equipments->eqproperty_user_use()->attach($request->equipment_user_use);
            if ($request->attachment && $request->attachment != '' && is_array(explode(',', $request->attachment)))
                $equipments->hand_over()->attach(array_filter(explode(',', $request->attachment)), ['type' => 'hand_over']);

            $array_user = getUserPhcToNotify($equipments->id);
            // dd($array_user);
            if ($array_user != null) {
                foreach ($array_user as $key => $value) {
                    $user = User::findOrFail($value);
                    $user->notify(new HanOverNotifications($equipments));
                }
            }
            if ($equipments) {
                if ($equipments->wasChanged()) {
                    return redirect()->back()->with('success', 'Đã bàn giao thiết bị ' . $equipments->title . ' ');
                } else {
                    return redirect()->back();
                }
            } else {
                return redirect()->back()->with('error', 'Cập nhật không thành công');
            }
        endif;
    }

    public function selectHandOver(Request $request)
    {
        $users = User::select('id', 'name', 'department_id')->where('department_id', $request->id)->get();
        $html = '<select  class="select2 form-control" name="officer_department_charge_id">';
        if ($users) {
            foreach ($users as $item) {
                $html .= '<option value="' . $item->id . '">' . $item->name . '</option>';
            }
        }
        $user_use = User::select('id', 'name', 'department_id')->get();
        $html_user_use = '<label class="control-label">' . __('CB sử dụng') . '</label>';
        $html_user_use .= '<select  class="select2 form-control" name="equipment_user_use[]"  multiple="multiple">';
        if ($user_use) {
            foreach ($user_use as $item) {
                $html_user_use .= '<option value="' . $item->id . '"' . (($item->department_id == $request->id ? ' selected' : '')) . '>' . $item->name . '</option>';
            }
        }
        return response()->json([
            'check' => 'true',
            'html' => $html,
            'html_user_use' => $html_user_use,
        ]);
    }

    public function updateCorrected(Request $request, $id)
    {
        $equipments = Equipment::findOrFail($id);
        $equipments->status = $request->status;
        $equipments->save();
        if ($equipments) {
            if ($equipments->wasChanged('status')) {
                activity()->causedBy(Auth::user())->performedOn($equipments)->withProperties(['attributes' => $equipments])->log($equipments->status);
                return redirect()->back()->with('success', 'Cập nhật thành công');
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back()->with('error', 'Cập nhật không thành công');
        }
    }

    public function updateInactive(Request $request, $id)
    {
        $equipments = Equipment::findOrFail($id);
        $equipments['liquidation_date'] = $request->liquidation_date;
        $equipments['status'] = "liquidated";
        $equipments->save();
        if ($equipments) {
            if ($equipments->wasChanged('status')) {
                activity()->causedBy(Auth::user())->performedOn($equipments)->withProperties(['attributes' => $equipments])->log($equipments->status);
                return redirect()->back()->with('success', 'Cập nhật thành công');
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back()->with('error', 'Cập nhật không thành công');
        }
    }

    public function updateWasBroken(Request $request, $id)
    {
        $equipments = Eqproperty::findOrFail($id);

        $equipments['critical_level'] = $request->critical_level;
        $equipments['status'] = "was_broken";
        $equipments->save();
        // $x = Department::findOrFail($equipments->department_id)->email;

        if ($equipments) {
            if ($equipments->wasChanged('status')) {
                // dd(1);
                $equipments['date_failure'] = Carbon::now()->toDateTimeString();
                $equipments->update($request->only('date_failure', 'reason'));
                $equipments->eqproperty_user_use()->attach($request->equipment_user_use);
                if ($request->file && $request->file != '' && is_array(explode(',', $request->file)))
                    $equipments->was_broken()->attach(array_filter(explode(',', $request->file)), ['type' => 'was_broken']);
                $equipments->save();
                $user = Auth::user();
                $content = '';
                $content .= '<div class="content">
                                <h4>' . __('Thông tin thiết bị báo hỏng') . '</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipments->title . '</td></tr>
                                        <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipments->hash_code . '</td></tr>
                                        <tr><td>' . __('Model: ') . '</td><td>' . $equipments->model . '</td></tr>
                                        <tr><td>' . __('Serial: ') . '</td><td>' . $equipments->serial . '</td></tr>
                                        <tr><td>' . __('Lý do hỏng: ') . '</td><td>' . $equipments->reason . '</td></tr>
                                        <tr><td>' . __('Mức độ quan trọng: ') . '</td><td>' . $equipments->critical_level . '</td></tr>
                                        <tr><td>' . __('Người báo hỏng: ') . '</td><td>' . $user->name . '</td></tr>
                                        <tr><td>' . __('Nhân viên khoa: ') . '</td><td>' . $user->user_department->title . '</td></tr>
                                        <tr><td>' . __('Thời gian báo hỏng: ') . '</td><td>' . $equipments->date_failure . '</td></tr>
                                    </tbody>
                                </table>
                            </div>';
                //send email
                $array_emails = getUserPhcToMail($equipments->id);
                $data = array('email' => $array_emails, 'equipments_department' => $equipments->eqproperty_department, 'from' => 'phongvt.ttb.bvkienan@gmail.com', 'content' => $content, 'title' => $equipments->title);
                Mail::send('mails.fail', compact('data'), function ($message) use ($data) {
                    $message->to('dat.dt11122002@gmail.com')
                        ->from($data['from'], '[Thông báo sửa chữa]')
                        ->subject('[Báo hỏng] ' . $data['equipments_department']->title . ' báo hỏng thiết bị [' . $data['title'] . ']');
                });
                $array_user = getUserPhcToNotify($equipments->id);
                $array_user = ['0'=>243];
                if ($array_user != null) {
                    foreach ($array_user as $key => $value) {
                        $user = User::findOrFail($value);
                        $user->notify(new ReportFailureNotifications($equipments));
                    }
                }
                activity()->causedBy(Auth::user())->performedOn($equipments)->withProperties(['attributes' => $equipments])->log($equipments->status);
                return redirect()->back()->with('success', 'Đã báo hỏng thiết bị ' . $equipments->title);
            } else {
                return redirect()->back()->with('error', 'Thiết bị ' . $equipments->title . ' đã được báo hỏng trước đó. Vui lòng không báo hỏng lại');
            }
        } else {
            return redirect()->back()->with('error', 'Cập nhật không thành công');
        }
    }

    public function updateWasBrokenDevice(Request $request, $id)
    {
        $equipments = Equipment::findOrFail($id);
        $equipments['status'] = "corrected";
        $equipments->save();
        if ($equipments) {
            if ($equipments->wasChanged('status')) {
                return redirect()->back()->with('success', 'Cập nhật thành công');
            } else {
                return redirect()->back()->with('error', 'Cập nhật không thành công');
            }
        } else {
            return redirect()->back()->with('error', 'Cập nhật không thành công');
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $equipments = Eqproperty::findOrFail($id);
        if ($user->can('delete', $equipments)) {
            $equipments->delete();
            $equipments->attachments()->detach();
            return redirect()->route('eqproperty.index')->with('success', 'Xóa thành công');
        } else {
            abort(403);
        }
    }

    public function select(Request $request)
    {
        $devices = Device::select('id', 'title', 'cat_id')->where('cat_id', $request->id)->get();
        $html_devices = '<label class="control-label">' . __('Loại thiết bị') . ' <small></small></label>';
        $html_devices .= '<select  class="select2 form-control" name="devices_id">';
        if ($devices) {
            foreach ($devices as $item) {
                $html_devices .= '<option value="' . $item->id . '">' . $item->title . '</option>';
            }
        }
        $users = User::select('id', 'name', 'department_id')->where('department_id', $request->id)->get();
        $html_officer_department_charge_device = '<label class="control-label">' . __('CB khoa phòng phụ trách') . ' <small></small></label>';
        $html_officer_department_charge_device .= '<select  class="select2 form-control" name="officer_department_charge_id">';
        if ($users) {
            foreach ($users as $item) {
                $html_officer_department_charge_device .= '<option value="' . $item->id . '">' . $item->name . '</option>';
            }
        }
        $user_use = User::select('id', 'name', 'department_id')->get();
        $html_user_use_device = '<label class="control-label">' . __('CB sử dụng') . '</label>';
        $html_user_use_device .= '<select  class="select2 form-control" name="equipment_user_use[]"  multiple="multiple">';
        if ($user_use) {
            foreach ($user_use as $item) {
                $html_user_use_device .= '<option value="' . $item->id . '"' . (($item->department_id == $request->id ? ' selected' : '')) . '>' . $item->name . '</option>';
            }
        }
        $html_user_training_device = '<label class="control-label">' . __('CB được đào tạo') . '</label>';
        $html_user_training_device .= '<select  class="select2 form-control" name="equipment_user_training[]"  multiple="multiple">';
        if ($user_use) {
            foreach ($user_use as $item) {
                $html_user_training_device .= '<option value="' . $item->id . '"' . (($item->department_id == $request->id ? ' selected' : '')) . '>' . $item->name . '</option>';
            }
        }
        return response()->json([
            'check' => 'true',
            'html_devices' => $html_devices,
            'html_officer_department_charge_device' => $html_officer_department_charge_device,
            'html_user_use_device' => $html_user_use_device,
            'html_user_training_device' => $html_user_training_device
        ]);
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $department_id = '';
        if (!($user->can('equipment.show_all'))) {
            $department_id = $user->department_id;
        }
        $departments_id = isset($request->departments_id) ? $request->departments_id : $department_id;
        // dd($departments_id);

        $cate_id = isset($request->cate_id) ? $request->cate_id : '';
        $device_id = isset($request->device_id) ? $request->device_id : '';
        $status_id = isset($request->status_id) ? $request->status_id : '';
        $key = isset($request->key) ? $request->key : '';
        $user = Auth::user();
        if ($user->can('equipment.export')) {
            return Excel::download(new EquipmentsExport($departments_id, $key, $cate_id, $device_id, $status_id), 'Danh sách thiết bị ' . Carbon::now()->format('d-m-Y') . '.xlsx');
        } else {
            abort(403);
        }
    }

    public function import(Request $request)
    {
        $rules = [
            'department_id' => 'required',
        ];
        $messages = [
            'department_id.required' => 'Vui lòng chọn khoa - phòng ban!',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->route('equipment.listimport')->withErrors($validator)->withInput();
        } else {
            if ($request->hasFile('equipment_file')) {
                $department_id = $request->department_id;
                $status = $request->status;
                $import = new EquipmentsImport;

                $import = Excel::import($import, request()->file('equipment_file'));
                if ($import) {
                    return redirect()->route('equipment.index')->with('success', 'Import thiết bị thành công');
                } else {
                    return redirect()->route('equipment.index')->with('success', 'Import thiết bị thành công');
                }
            }
        }
    }

    public function listImport()
    {
        $user = Auth::user();
        if ($user->can('imports.equipment')) {
            $departments = Department::select('id', 'title')->get();
            return view('backends.equipments.listimport', compact('departments'));
        } else {
            abort(403);
        }
    }
    public function updateHashCodeImport(Request $request){
        if (!$request->hasFile('update_file')) {
            return redirect()->route('equipment.index')->with('error', 'Bạn chưa chọn tệp nào');
        }
        $import = new UpdateEquipmentImport();
        Excel::import($import, request()->file('update_file'));
        return redirect()->route('equipment.index')->with('success', 'Cập nhật thông tin thiết bị thành công');
    }

    public function getUserForSend(){
        $arrayUser = getUserPhcToNotify(3897);
        return view('backends.properties.check', compact('arrayUser'));
    }
}
