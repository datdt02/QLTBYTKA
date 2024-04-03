<?php
namespace App\Http\Controllers\backends;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Cates;
use App\Models\Device;
use App\Models\Equipment;
use App\Models\Guarantee;
use App\Models\Department;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportGuarateeList;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class GuaranteeController extends Controller {

    public function index(Request $request){
        $key = isset($request->key) ? $request->key : '';
        $status = isset($request->status) ? $request->status : '';
        $departments_key = isset($request->department_key) ? $request->department_key : '';
        $cate_key = isset($request->cate_key) ? $request->cate_key : '';
        $device_key = isset($request->device_key) ? $request->device_key : '';
        $time_guarantee = isset($request->time_guarantee) ? $request->time_guarantee : '';

        $start = date_format(date_create($time_guarantee), 'Y-m-d');

        $end =  date("Y-m-d", strtotime("+"."1"."months", strtotime($start)));  // ngày tháng



        $department_name = Department::select('id','title')->get();
        $user_name = User::select('id','name')->get();
        $cate_name = Cates::select('id','title')->get();
        $device_name = Device::select('id','title')->get();
        $equipments = Equipment::with('equipment_department');
        if($key != ''){
            $equipments = $equipments->where(function ($query) use ($key) {
                                                $query->where('title','like','%'.$key.'%')
                                                    ->orWhere('code','like','%'.$key.'%')
                                                    ->orWhere('model','like','%'.$key.'%')
                                                    ->orWhere('serial','like','%'.$key.'%');
                                                });
        }

        if($time_guarantee != ''){
            $equipments = $equipments->whereBetween('warranty_date',[$start , $end]);
        }

        if($status != '') {
            $equipments = $equipments->where('status',$status);
        }
        if($departments_key != '') {
            $equipments = $equipments->where('department_id',$departments_key);
        }
        if($cate_key != '') {
            $equipments = $equipments->where('cate_id',$cate_key);
        }
        if($device_key != ''){
            $equipments = $equipments->where('devices_id',$device_key);
        }
        $equipments = $equipments->whereNotIn('status',['inactive','liquidated'])->orderBy('created_at', 'desc')->latest()->paginate(15);

        $data = [
            'equipments'        => $equipments,
            'key'               => $key,
            'status'            => $status,
            'departments_key'   => $departments_key,
            'cate_key'         => $cate_key,
            'device_key'       => $device_key,
            'department_name'   => $department_name,
            'cate_name'         => $cate_name,
            'device_name'       => $device_name,
            'time_guarantee'    => $time_guarantee,
        ];
        return view('backends.guarantees.list', $data);
    }


    public function exportEquipGuara(Request $request) {


        $time_guarantee = isset($request->time_guarantee) ? $request->time_guarantee : '';
        $key = isset($request->key) ? $request->key : '';
        $departments_key = isset($request->departments_key) ? $request->departments_key : '';
        $status = isset($request->status) ? $request->status : '';
        $cate_key = isset($request->cate_key) ? $request->cate_key : '';
        $device_key = isset($request->device_key) ? $request->device_key : '';
        // dd($request);
        $department_name = "";
        if($departments_key != '') {
            $department_name = Department::findOrFail($departments_key)->title;
        }


        return Excel::download(new ExportGuarateeList(
            $time_guarantee,$departments_key,$status,$key, $cate_key,$device_key), 'Danh sách thiết bị '. $department_name .' sẽ hết hạn bảo hành: ' . $time_guarantee . '.xlsx');

    }

    public function store(Request $request,$id){
        $rules = [

        ];
        $messages = [

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->back()->withErrors($validator)->withInput();
        else:
            $equipments = Equipment::findOrFail($id);
            $atribute = $request->all();
            $atribute['equipment_id'] =  $equipments->id;
            Guarantee::create($atribute);
            return redirect()->route('guarantee.index')->with('success','Thêm thành công');
        endif;
    }

    public function edit($id){
        $equipments = Equipment::findOrFail($id);
        return view('backends.guarantees.edit',compact('equipments'));
    }

    public function update(Request $request,$id){
        $rules = [
            'time'=>'required',
            'provider'=>'required',
            'content'=>'required',
        ];
        $messages = [
            'time.required'=>'Vui lòng chọn thời gian bảo hành',
            'provider.required'=>'Vui lòng nhập đơn vị thực hiện',
            'content.required'=>'Vui lòng nhập nội dung bảo hành',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->back()->withErrors($validator)->withInput();
        else:
            $guarantee = Guarantee::findOrFail($id);
            $atribute = $request->all();
            $guarantee->update($atribute);
            if($guarantee){
                if($guarantee->wasChanged()){
                    return redirect()->back()->with('success','Cập nhật thành công');
                }else{
                    return redirect()->back();
                }
            }else{
                return redirect()->back()->with('error','Cập nhật không thành công');
            }
        endif;
    }


    public function destroy($id){
        $equipments = Guarantee::findOrFail($id);
        $equipments->delete();
        return redirect()->back()->with('success','Xóa thành công');
    }

}
