<?php
namespace App\Http\Controllers\backends;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Eqsupplie;
use App\Models\Equipment;
use App\Models\Provider;
use App\Models\SupplieDevice;
use App\Models\User;
use App\Models\Cates;
use App\Models\Unit;
use App\Models\Department;
use App\Models\Device;
use App\Models\Supplie;
use App\Models\Action;
use Illuminate\Support\Facades\Validator;
use App\Exports\EqsuppliesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EqsuppliesImport;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Validation\Rule;
class EqsupplieController extends Controller {
    public function index(Request  $request){
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $providers_key = isset($request->provider_key) ? $request->provider_key : '';
        $statuss_key = isset($request->status_key) ? $request->status_key : '';
        $supplies_key = isset($request->supplie_key) ? $request->supplie_key : '';
        $eqsupplies = Eqsupplie::query();
        $provider_name = Provider::select('id','title')->provider()->get();
        $supplie_name = Supplie::select('id','title')->get();
        $cur_time = Carbon::now()->format('Y-m-d');
        if($keyword != ''){
            $eqsupplies = $eqsupplies->where(function ($query) use ($keyword) {
                $query->where('title','like','%'.$keyword.'%')
                    ->orWhere('code','like','%'.$keyword.'%')
                    ->orWhere('model','like','%'.$keyword.'%')
                    ->orWhere('serial','like','%'.$keyword.'%');
                });
        }
        if($supplies_key != ''){
            $eqsupplies = $eqsupplies->where('supplie_id',$supplies_key);
        }
        if($statuss_key != ''){
            $eqsupplies = $eqsupplies->where('status',$statuss_key);
        }
        if($providers_key != ''){
            $eqsupplies = $eqsupplies->where('provider_id',$providers_key);
        }
        $eqsupplies = $eqsupplies->orderBy('created_at', 'desc')->paginate(15);
        $data= [];
        $data['eqsupplies'] = $eqsupplies;
        $data['keyword'] = $keyword;
        $data['provider_name'] = $provider_name;
        $data['providers_key'] = $providers_key;
        $data['supplies_key'] = $supplies_key;
        $data['statuss_key'] = $statuss_key;
        $data['supplie_name'] = $supplie_name;
        $data['cur_time'] = $cur_time;
        return view('backends.eqsupplies.list', $data);
    }
    public function indexCompatibleDevice(Request  $request) {
        $user = Auth::user();
        if($user->can('eqsupplie.read')){
            $keyword = isset($request->key) ? $request->key : '';
            $status = isset($request->status) ? $request->status : '';
            $departments_key = isset($request->department_key) ? $request->department_key : '';
            $cates_key = isset($request->cate_key) ? $request->cate_key : '';
            $devices_key = isset($request->device_key) ? $request->device_key : '';
            $department_name = Department::select('id','title')->get();
            $user_name = User::select('id','name')->get();
            $cate_name = Cates::select('id','title')->get();
            $device_name = Device::select('id','title')->get();
            $equipments = Equipment::query();
            $cur_time = Carbon::now()->format('Y-m-d');
            $order = '';
            $sort = '';
            if($keyword != ''){
                $equipments = $equipments->where(function ($query) use ($keyword) {
                $query->where('title','like','%'.$keyword.'%')
                    ->orWhere('code','like','%'.$keyword.'%')
                    ->orWhere('model','like','%'.$keyword.'%')
                    ->orWhere('serial','like','%'.$keyword.'%');
                });
            }
            if($status != ''){
                $equipments = $equipments->where('status',$status);
            }
            if($departments_key != ''){
                $equipments = $equipments->where('department_id',$departments_key);
            }
            if($cates_key != ''){
                $equipments = $equipments->where('cate_id',$cates_key);
            }
            if($devices_key != ''){
                $equipments = $equipments->whereHas('equipment_device', function($query) use ($devices_key) {
        			$query->where('device_id',$devices_key);
    			});    
            }
            $equipments = $equipments->device()->paginate(15);
                return view('backends.eqsupplies.compatible',
                compact('equipments',
                'keyword',
                'sort','order',
                'status',
                'department_name','departments_key',
                'cate_name','cates_key',
                'device_name','devices_key',
                'user_name','cur_time',
                'user',
            ));
        }else{
            abort(403);
        }
    }

    public function show($id) {
        $eqsupplies = Eqsupplie::findOrFail($id);
        return view('backends.profiles.show', compact('eqsupplies'));
    }
    public function create(){
        $user= Auth::user();
        if($user->can('eqsupplie.create_input', Eqsupplie::class)){ 
            $maintenances = Provider::select('id','title','type')->maintenance()->get();
            $providers = Provider::select('id','title','type')->provider()->get();
            $repairs = Provider::select('id','title','type')->repair()->get();
            $users = User::select('id','name')->get();
            $units = Unit::select('id','title')->get();
            $departments = Department::select('id','title')->get();
            $supplies = Supplie::select('id','title')->get();
            $cur_day = Carbon::now()->format('Y-m-d'); 
            $data= [];
            $data['maintenances'] = $maintenances;
            $data['providers'] = $providers;
            $data['repairs'] = $repairs;
            $data['users'] = $users;
            $data['units'] = $units;
            $data['departments'] = $departments;
            $data['supplies'] = $supplies;
            $data['cur_day'] = $cur_day;
            return view('backends.eqsupplies.create',$data);
        }else{
            abort(403);
        }
    }
    public function store(Request  $request){
        $rules = [
            'title'=>'required',
            'supplie_id'=>'required',
            'serial'=>'required|unique:equipments,serial',
            'amount'=>'required|min:0',
            'unit_id'=>'required',
        ];
        $messages = [
            'title.required'=>'Vui lòng nhập tên thiết bị!',
            'supplie_id.required'=>'Vui lòng nhập loại vật tư!',
            'serial.unique'=>'Số serial đã tồn tại !',
            'amount.required'=>'Vui lòng nhập số lượng !',
            'amount.min'=>'Số lượng không được nhỏ hơn 0!',
            'unit_id.required'=>'Vui lòng nhập đơn vị tính !',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('eqsupplie.create')->withErrors($validator)->withInput();
        else:
        $atribute = $request->all();
        $eqsupplies = Eqsupplie::create($atribute);
        if($eqsupplies){
            return redirect()->route('eqsupplie.index')->with('success','Thêm thành công');
        }else{
            return redirect()->route('eqsupplie.index')->with('success','Thêm không thành công');
        }
        endif;
    }
    public function storeCompatible(Request  $request, $id){
        $eqsupplies = Eqsupplie::findOrFail($id);
        $rules = [
            'deviced'=>'required',
        ];
        $messages = [
            'deviced.required'=>'Vui lòng chọn thiết bị tương thích vật tư!',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->back()->withErrors($validator)->withInput();
        else:
        $equipments = $request->deviced;
        $array_success = array();
        foreach ($equipments as $equip_id) {
            if($eqsupplies->supplie_devices->contains($equip_id)){
            }else{
                $eqsupplies->supplie_devices()->attach($equip_id ,['user_id' => Auth::user()->id,'note' =>'supplies_can_equipment']);
                if($eqsupplies->supplie_devices()->exists($equip_id)) $array_success[] = $equip_id;
            }
        }
        if(count($array_success) > 0){
            return redirect()->back()->with('success','Đã lưu thông tin vật tư và  '.count($array_success).' thiết bị có thể tương thích.');
        }else{
            return redirect()->back()->with('error','Thêm không thành công thiết bị đã tồn tại ! ');
        }
        endif;
    }
    public function destroyCompatible($id){
        $eqsupplies = Equipment::findOrFail($id);
        $eqsupplies->device_supplies()->detach();
        return redirect()->back()->with('success','Xóa thành công');
    }
    public function edit($id){
        $user = Auth::user();
        $eqsupplies = Eqsupplie::findOrFail($id);
        if($user->can('update', $eqsupplies)){
            $maintenances = Provider::select('id','title','type')->maintenance()->get();
            $providers = Provider::select('id','title','type')->provider()->get();
            $repairs = Provider::select('id','title','type')->repair()->get();
            $users = User::select('id','name')->get();
            $units = Unit::select('id','title')->get();
            $departments = Department::select('id','title')->get();
            $supplies = Supplie::select('id','title')->get();
            $cur_day = Carbon::now()->format('Y-m-d'); 
            $data = [
                'eqsupplies' => $eqsupplies,
                'maintenances' => $maintenances,
                'providers' => $providers,
                'repairs' => $repairs,
                'users' => $users,
                'units' => $units,
                'departments' => $departments,
                'supplies' => $supplies,
                'cur_day' => $cur_day,
            ];
            return view('backends.eqsupplies.edit',$data);
        }else{
          abort(403);
        }
    }
    public function update(Request  $request , $id){
        $eqsupplies = Eqsupplie::findOrFail($id);
        $rules = [
            'title'=>'required',
            'supplie_id'=>'required',
            'amount'=>'required|min:0',
            'serial'=>['required',Rule::unique('equipment_supplies')->ignore($eqsupplies->id)],
            'unit_id'=>'required',
        ];
        $messages = [
            'title.required'=>'Vui lòng nhập tên thiết bị!',
            'supplie_id.required'=>'Vui lòng nhập loại vật tư!',
            'amount.required'=>'Vui lòng nhập số lượng !',
            'serial.unique'=>'Số serial đã tồn tại !',
            'amount.min'=>'Số lượng không được nhỏ hơn 0!',
            'unit_id.required'=>'Vui lòng nhập đơn vị tính !',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('eqsupplie.edit',$id)->withErrors($validator)->withInput();
        else:
        $atribute = $request->all();
        $eqsupplies->update($atribute);
        if($eqsupplies){
            if($eqsupplies->wasChanged())
                return redirect()->route('eqsupplie.edit',$id)->with('success','Cập nhật thành công');
            else 
                return redirect()->route('eqsupplie.edit',$id); 
        }else{
            return redirect()->route('eqsupplie.edit',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function destroy($id){
        $user = Auth::user();
        $eqsupplies = Eqsupplie::findOrFail($id);
        if ($user->can('delete', $eqsupplies)) {
            $eqsupplies->delete();
            return redirect()->route('eqsupplie.index')->with('success','Xóa thành công');
        }else{
          abort(403);
        }

    }
    public function export() 
    {
        return Excel::download(new EqsuppliesExport, 'Danh sách vật tư ' . Carbon::now()->format('d-m-Y') . '.xlsx');
    }
    public function updateAmount(Request  $request , $id)
    {
        $rules = [
			'amount'=>'required',
        ];
        $messages = [
			'amount.required'=>'Vui lòng nhập số lượng',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->back()->withErrors($validator)->withInput();
		else:
        $eqsupplies = Eqsupplie::findOrFail($id);
        $eqsupplies->amount = intval($eqsupplies->amount) + intval($request->amount);
        $eqsupplies->save();
        if($eqsupplies){
            if($eqsupplies->wasChanged())
                return redirect()->back()->with('success','Cập nhật thành công');
            else 
                return redirect()->back();
        }else{
            return redirect()->back()->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function updateUsed(Request  $request , $id, $equip_id)
    {
        $eqsupplies = Eqsupplie::findOrFail($id);
        $rules = [
            'used_amount'=>'required|numeric|max:'.intval($eqsupplies->remaining_amount()).'|min:0',
            'date_compatible' => 'required',
        ];
        $messages = [
            'used_amount.required'=>'Vui lòng nhập số lượng!',
            'used_amount.max'=>'Số lượng vật tư hiện tại ít hơn '.intval($eqsupplies->remaining_amount()).' , vui lòng kiểm tra lại thông tin vật tư ',
            'used_amount.min'=>'Vui lòng nhập số lượng không nhỏ hơn 0!',
            'date_compatible.required'=>'Vui lòng nhập ngày bàn giao!',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->back()->withErrors($validator)->withInput();
        else:
        $sum_used_amount =  $eqsupplies->supplie_devices()->where('device_id',$equip_id)->first()->pivot->amount;
        $eqsupplies->supplie_devices()->updateExistingPivot($equip_id, ['amount' => intval($sum_used_amount) + intval($request->used_amount),
        'date_delivery' => $request->date_compatible,
        'user_id' => Auth::user()->id,
        ]);
        if($eqsupplies){
            return redirect()->route('eqsupplie.index',$id)->with('success','Bàn giao vật tư thành công!' );
        }else{
            return redirect()->route('eqsupplie.index',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function showCompatible(Request $request ,$id) {
        $user = Auth::user();
        if($user->can('eqsupplie.read')){
            $eqsupplies = Eqsupplie::findOrFail($id);
            $keyword = isset($request->key) ? $request->key : '';
            $status = isset($request->status) ? $request->status : '';
            $departments_key = isset($request->department_key) ? $request->department_key : '';
            $cates_key = isset($request->cate_key) ? $request->cate_key : '';
            $devices_key = isset($request->device_key) ? $request->device_key : '';
            $department_name = Department::select('id','title')->get();
            $user_name = User::select('id','name')->get();
            $cate_name = Cates::select('id','title')->get();
            $device_name = Device::select('id','title')->get();
            $cur_time = Carbon::now()->format('Y-m-d');
            $equipments = Equipment::query();
            if($keyword != ''){
                $equipments = $equipments->where(function ($query) use ($keyword) {
                $query->where('title','like','%'.$keyword.'%')
                    ->orWhere('code','like','%'.$keyword.'%')
                    ->orWhere('model','like','%'.$keyword.'%')
                    ->orWhere('year_manufacture','like','%'.$keyword.'%')
                    ->orWhere('serial','like','%'.$keyword.'%');
                });
            }
            if($departments_key != ''){
                $equipments = $equipments->where('department_id',$departments_key);
            }
            if($cates_key != ''){
                $equipments = $equipments->where('cate_id',$cates_key);
            }
            if($devices_key != ''){
                $equipments = $equipments->whereHas('equipment_device', function($query) use ($devices_key) {
        			$query->where('device_id',$devices_key);
    			});    
            }
            $equipments = $equipments->device()->orderBy('created_at', 'desc')->get();
                return view('backends.eqsupplies.compatible',
                compact('equipments',
                'keyword',
                'status',
                'department_name','departments_key',
                'cate_name','cates_key',
                'device_name','devices_key',
                'user_name',
                'user','cur_time',
                'eqsupplies'
            ));
        }else{
            abort(403);
        }
    }
    public function listImport(){
        $user = Auth::user();
        if($user->can('imports.supplie')){
            $departments = Department::select('id','title')->get();
            return view('backends.eqsupplies.listimport', compact('departments'));
        }else{
            abort(403);
        }
       
    }
    public function import(Request $request) 
    {   
        $import = new EqsuppliesImport;
        $import = Excel::import($import, request()->file('eqsupplie_file'));
        if($import){
            return redirect()->route('eqsupplie.listimport')->with('success','Import thành công');
        }else{
            return redirect()->route('eqsupplie.listimport')->with('error','Import không thành công');
        }
    }
}