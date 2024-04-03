<?php
namespace App\Http\Controllers\backends;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Maintenance;
use App\Models\MaintenanceAction;
use Carbon\Carbon;

class MaintenanceActionController extends Controller {

    public function index(Request $request, $equip_id){
        $user= Auth::user();
        if($user->can('maintenance_periodic.read')){
            $keyword = isset($request->key) ? $request->key : '';
            $date = isset($request->date) && $request->date != '' ? $request->date : Carbon::now()->format('Y-m-d');
            $user = Auth::user();

            if($keyword != '') {
                $data_link['key'] = $keyword;
            }
            if($date != '') {
                $data_link['date'] = $date;
            }

            $equipment = Equipment::select('id', 'title', 'code', 'serial', 'model', 'last_inspection', 'warehouse', 'manufacturer', 'department_id', 'warranty_date', 'first_information', 'status')->findOrFail($equip_id)->load('equipment_department:id,title');
            $data = [
                'equipment'         => $equipment,
                'maintenances'      => $equipment->maintenances->sortBy('created_at')->simplePaginate(20),
                'keyword'           => $keyword,
                'date'              => $date,
                'month'             => Carbon::parse($date)->format('Y-m'),
                'current_date'      => Carbon::now()->format('Y-m-d'),
                'daysInMonth'       => Carbon::parse($date)->daysInMonth,
                'frequency'         => generate_frequency(),
                'statuses'          => get_statusEquipments(),
                'types'             => generate_maint_action(),
                'data_link'         => $data_link,
                'username'          => $user->displayname ? $user->displayname : $user->name,
            ];
            return view('backends.equipments.maintenance-histories', $data);
        }else{
            abort(403);
        }
    }

    public function store(Request $request, $equip_id, $main_id) {
        $equipment = Equipment::findOrFail($equip_id);
        $maintenance = Maintenance::findOrFail($main_id);
        $types = generate_maint_action();
        $request['created_date'] = isset($request->created_date) && $request->created_date != '' ? $request->created_date : Carbon::now()->format('Y-m-d');
        $request['date_of_action'] = isset($request->date_of_action) && $request->date_of_action != '' ? $request->date_of_action : Carbon::now()->format('Y-m-d');
        $request['code'] = isset($request->code) && $request->code != '' ? $request->code : str_replace('-','',$request->date_of_action).$main_id;
        $request['type'] = in_array($request->type, array_keys($types)) ? $request->type : array_keys($types)[0];

        $rules = [
            'code'=>'required',
            'code'=>'unique:maintenance_actions',
        ];
        $messages = [
            'code.required'=> 'Mã kiểm tra không được để trống!',
            'code.unique'=> 'Mã kiểm tra '.$request->code.' đã tồn tại!',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->back()->withErrors($validator)->withInput();
        else:
            $action = $maintenance->actions()->create([
                                                'code' => $request->code,
                                                'type' => $request->type,
                                                'created_date' => $request->created_date,
                                                'date_of_action' => $request->date_of_action,
                                                'author_id' => Auth::id(),
                                                'note' => $request->note,
                                            ]);
            if($action) $request->session()->flash('success', 'Tạo thành công!');
                else $request->session()->flash('error', 'Tạo thất bại!');
            return redirect()->back();
        endif;
    }
    public function update(Request $request, $equip_id, $main_id) {
        $equipment = Equipment::findOrFail($equip_id);
        $actions = MaintenanceAction::findOrFail($request->action_id);
        $rules = [
            'note'=>'required',
        ];
        $messages = [
            'note.required'=> __('Vui lòng nhập ghi chú!'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('equip_maintenance.history',['equip_id'=>$equip_id, 'main_id'=>$main_id])->withErrors($validator)->withInput();
        else:
            $actions->note = $request->note;
            if($actions->save()) {
                if($actions->wasChanged()) $request->session()->flash('success', 'Cập nhật thành công!');
            }else $request->session()->flash('error', 'Cập nhật thất bại!');
            return redirect()->route('equip_maintenance.history',['equip_id'=>$equip_id, 'main_id'=>$main_id]);
        endif;

    }
}
