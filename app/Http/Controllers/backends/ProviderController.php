<?php
namespace App\Http\Controllers\backends;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provider;
use App\Models\Cates;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class ProviderController extends Controller {
    public function index(Request  $request){
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $providers = Provider::query();
        if($user->can('provider.read')){
            if($keyword != ''){
                $providers = $providers->where(function ($query) use ($keyword) {
                $query->where('title','like','%'.$keyword.'%')
                    ->orWhere('tax_code','like','%'.$keyword.'%')
                    ->orWhere('note','like','%'.$keyword.'%')
                    ->orWhere('contact','like','%'.$keyword.'%')
                    ->orWhere('address','like','%'.$keyword.'%')
                    ->orWhere('email','like','%'.$keyword.'%');
                });
            }
            $providers = $providers->provider()->orderBy('created_at', 'desc')->paginate(5);
            return view('backends.providers.list',compact('providers','keyword'));
        }else{
            if($keyword != ''){
                $providers = $providers->where(function ($query) use ($keyword) {
                $query->where('title','like','%'.$keyword.'%')
                    ->orWhere('tax_code','like','%'.$keyword.'%')
                    ->orWhere('note','like','%'.$keyword.'%')
                    ->orWhere('contact','like','%'.$keyword.'%')
                    ->orWhere('address','like','%'.$keyword.'%')
                    ->orWhere('email','like','%'.$keyword.'%');
                });
            }
            $providers = $providers->where('author_id',$user->id)->provider()->orderBy('created_at', 'desc')->paginate(5);
            return view('backends.providers.list',compact('providers','keyword'));
        }
    }
    public function create(){
        $user = Auth::user();
        if ($user->can('provider.create')) {
            $equipments = Cates::all();
            return view('backends.providers.create',compact('equipments'));
        }else{
          abort(403);
        }
    }
    public function store(Request  $request){
        $rules = [
            'title'=>'required',
            'tax_code'=>'required',
            'fields_operation'=>'required',
            'phone'=>'required',
            'contact'=>'required',
            'email' => [
                'required','email',Rule::unique('providers')->where(function($query) {
                  $query->where('type', '=', 'providers');
              })
            ],
            'address'=>'required',
        ];
        $messages = [
            'title.required'=>'Vui lòng nhập tên thiết bị !',
            'tax_code.required'=>'Vui lòng nhập mã số thuế !',
            'fields_operation.required'=>'Vui lòng nhập lĩnh vực hoạt động !',
            'phone.required'=>'Vui lòng nhập số điện thoại !',
            'contact.required'=>'Vui lòng nhập liên hệ !',
            'email.required'=>'Vui lòng nhập email !',
            'email.unique'=>'Email đã tồn tại !',
            'address.required'=>'Vui lòng nhập địa chỉ !',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('provider.create')->withErrors($validator)->withInput();
        else:
        $request['author_id'] = Auth::id(); 
        $atribute = $request->all();
        $atribute['type'] = 'providers';
        $atribute['fields_operation'] = json_encode($request->fields_operation);
        $providers = Provider::create($atribute);
        $providers->equipment_cates()->attach($request->equipment_cates);
        if($providers){
            return redirect()->route('provider.index')->with('success','Thêm thành công');
        }else{
            return redirect()->route('provider.index')->with('success','Thêm không thành công');
        }
        endif;
    }
    public function edit($id){
        $user = Auth::user();
        $providers = Provider::findOrFail($id);
        if($user->can('update', $providers)) {
            $equipments = Cates::select('id','title')->get();
            $array = $providers->equipment_cates->pluck('id')->toArray();
            return view('backends.providers.edit',compact('providers','equipments', 'array'));
        }else{
          abort(403);
        }
    }
    public function update(Request  $request , $id){
        $rules = [
            'title'=>'required',
            'tax_code'=>'required',
            'fields_operation'=>'required',
            'phone'=>'required',
            'contact'=>'required',
            'email' => [
                'required','email',Rule::unique('providers')->where(function($query) use ($id) {
                  $query->where('type', '=', 'providers')
                  ->where('id', '!=', $id);
              })
            ],
            'address'=>'required',
        ];
        $messages = [
            'title.required'=>'Vui lòng nhập tên thiết bị !',
            'tax_code.required'=>'Vui lòng nhập mã số thuế !',
            'fields_operation.required'=>'Vui lòng nhập lĩnh vực hoạt động !',
            'phone.required'=>'Vui lòng nhập số điện thoại !',
            'contact.required'=>'Vui lòng nhập liên hệ !',
            'email.required'=>'Vui lòng nhập email !',
            'email.unique'=>'Email đã tồn tại !',
            'address.required'=>'Vui lòng nhập địa chỉ !',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('provider.edit',$id)->withErrors($validator)->withInput();
        else:
        $providers = Provider::findOrFail($id);
        $atribute = $request->all();
        $atribute['fields_operation'] = json_encode($request->fields_operation);
        $providers->update($atribute);
        $providers->equipment_cates()->sync($request->equipment_cates);
        if($providers){
            if($providers->wasChanged())
                return redirect()->route('provider.edit',$id)->with('success','Cập nhật thành công');
            else 
                return redirect()->route('provider.edit',$id); 
        }else{
            return redirect()->route('provider.edit',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function destroy($id){
        $user = Auth::user();
        $providers = Provider::findOrFail($id);
        if ($user->can('delete', $providers)) {
            $providers->delete();
            return redirect()->route('provider.index')->with('success','Xóa thành công');
        }else{
          abort(403);
        }
    }
    public function indexMaintenance(Request  $request){
        $user = Auth::user();
        if($user->can('maintenance.read')){
            $keyword = isset($request->key) ? $request->key : '';
            $maintenances = Provider::query();
            if($keyword != ''){
                $maintenances = $maintenances->where(function ($query) use ($keyword) {
                $query->where('title','like','%'.$keyword.'%')
                    ->orWhere('tax_code','like','%'.$keyword.'%')
                    ->orWhere('note','like','%'.$keyword.'%')
                    ->orWhere('contact','like','%'.$keyword.'%')
                    ->orWhere('address','like','%'.$keyword.'%')
                    ->orWhere('email','like','%'.$keyword.'%');
                });
            }
            $maintenances = $maintenances->maintenance()->orderBy('created_at', 'desc')->paginate(5);
            return view('backends.maintenances.list',compact('maintenances','keyword'));
        }else{
            $keyword = isset($request->key) ? $request->key : '';
            $maintenances = Provider::where('author_id',$user->id);
            if($keyword != ''){
                $maintenances = $maintenances->where(function ($query) use ($keyword) {
                $query->where('title','like','%'.$keyword.'%')
                    ->orWhere('tax_code','like','%'.$keyword.'%')
                    ->orWhere('note','like','%'.$keyword.'%')
                    ->orWhere('contact','like','%'.$keyword.'%')
                    ->orWhere('address','like','%'.$keyword.'%')
                    ->orWhere('email','like','%'.$keyword.'%');
                });
            }
            $maintenances = $maintenances->maintenance()->orderBy('created_at', 'desc')->paginate(5);
            return view('backends.maintenances.list',compact('maintenances','keyword'));
        }
    }
    public function createMaintenance(){
        $user = Auth::user();
        if($user->can('maintenance.create')) {
            $equipments = Cates::all();
            return view('backends.maintenances.create',compact('equipments'));
        }else{
          abort(403);
        }
    }
    public function storeMaintenance(Request  $request){
        $rules = [
            'title'=>'required',
            'tax_code'=>'required',
            'fields_operation'=>'required',
            'note'=>'required',
            'contact'=>'required',
            'email'=>'required',
            'address'=>'required',
        ];
        $messages = [
            'title.required'=>'Vui lòng nhập tên thiết bị !',
            'tax_code.required'=>'Vui lòng nhập mã số thuế !',
            'fields_operation.required'=>'Vui lòng nhập lĩnh vực hoạt động !',
            'phone.required'=>'Vui lòng nhập số điện thoại !',
            'contact.required'=>'Vui lòng nhập liên hệ !',
            'email.required'=>'Vui lòng nhập email !',
            'address.required'=>'Vui lòng nhập địa chỉ !',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('maintenance.create')->withErrors($validator)->withInput();
        else:
        $request['author_id'] = Auth::id(); 
        $atribute = $request->all();
        $atribute['fields_operation'] = json_encode($request->fields_operation);
        $atribute['type'] = 'maintenances';
        $maintenance = Provider::create($atribute);
        $maintenance->equipment_cates()->attach($request->equipment_cates);
        return redirect()->route('maintenance.index')->with('success','Thêm thành công');
        endif;
    }
    public function editMaintenance($id){
        $user = Auth::user();
        $maintenances = Provider::findOrFail($id);
        if($user->can('update', $maintenances)) {
            $equipments = Cates::select('id','title')->get();
            $array = $maintenances->equipment_cates->pluck('id')->toArray();
            return view('backends.maintenances.edit',compact('maintenances','equipments','array'));
        }else{
          abort(403);
        } 
    }
    public function updateMaintenance(Request $request , $id){
        $rules = [
            'title'=>'required',
            'tax_code'=>'required',
            'fields_operation'=>'required',
            'note'=>'required',
            'contact'=>'required',
            'email'=>'required',
            'address'=>'required',
        ];
        $messages = [
            'title.required'=>'Vui lòng nhập tên thiết bị !',
            'tax_code.required'=>'Vui lòng nhập mã số thuế !',
            'fields_operation.required'=>'Vui lòng nhập lĩnh vực hoạt động !',
            'phone.required'=>'Vui lòng nhập số điện thoại !',
            'contact.required'=>'Vui lòng nhập liên hệ !',
            'email.required'=>'Vui lòng nhập email !',
            'address.required'=>'Vui lòng nhập địa chỉ !',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('maintenance.edit',$id)->withErrors($validator)->withInput();
        else:
        $maintenances = Provider::findOrFail($id);
        $atribute = $request->all();
        $atribute['fields_operation'] = json_encode($request->fields_operation);
        $maintenances->update($atribute);
        $maintenances->equipment_cates()->sync($request->equipment_cates);
        if($maintenances){
            if($maintenances->wasChanged())
            return redirect()->route('maintenance.edit',$id)->with('success','Cập nhật thành công');
        else 
            return redirect()->route('maintenance.edit',$id); 
        }else{
            return redirect()->route('maintenance.edit',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function destroyMaintenance($id){
        $user = Auth::user();
        $maintenances = Provider::findOrFail($id);
        if($user->can('delete', $maintenances)) {
            $maintenances->delete();
            return redirect()->route('maintenance.index')->with('success','Xóa thành công');
        }else{
          abort(403);
        }
    }
    public function indexRepair(Request  $request){
        $user = Auth::user();
        if($user->can('repair.read')){
            $keyword = isset($request->key) ? $request->key : '';
            $repairs = Provider::query();
            if($keyword != ''){
                $repairs = $repairs->where(function ($query) use ($keyword) {
                $query->where('title','like','%'.$keyword.'%')
                    ->orWhere('tax_code','like','%'.$keyword.'%')
                    ->orWhere('note','like','%'.$keyword.'%')
                    ->orWhere('contact','like','%'.$keyword.'%')
                    ->orWhere('address','like','%'.$keyword.'%')
                    ->orWhere('email','like','%'.$keyword.'%');
                });
            }
            $repairs = $repairs->repair()->orderBy('created_at', 'desc')->paginate(5);
            return view('backends.repairs.list',compact('repairs','keyword'));
        }else{
            $keyword = isset($request->key) ? $request->key : '';
            $repairs = Provider::where('author_id',$user->id);
            if($keyword != ''){
                $repairs = $repairs->where(function ($query) use ($keyword) {
                $query->where('title','like','%'.$keyword.'%')
                    ->orWhere('tax_code','like','%'.$keyword.'%')
                    ->orWhere('note','like','%'.$keyword.'%')
                    ->orWhere('contact','like','%'.$keyword.'%')
                    ->orWhere('address','like','%'.$keyword.'%')
                    ->orWhere('email','like','%'.$keyword.'%');
                });
            }
            $repairs = $repairs->repair()->orderBy('created_at', 'desc')->paginate(5);
            return view('backends.repairs.list',compact('repairs','keyword'));
        }
    }
    public function createRepair(){
        $user = Auth::user();
        if($user->can('repair.create')) {
            $equipments = Cates::all();
            return view('backends.repairs.create',compact('equipments')); 
        }else{
          abort(403);
        }
    }
    public function storeRepair(Request  $request){
        $rules = [
            'title'=>'required',
            'tax_code'=>'required',
            'fields_operation'=>'required',
            'note'=>'required',
            'contact'=>'required',
            'email'=>'required',
            'address'=>'required',
        ];
        $messages = [
            'title.required'=>'Vui lòng nhập tên thiết bị !',
            'tax_code.required'=>'Vui lòng nhập mã số thuế !',
            'fields_operation.required'=>'Vui lòng nhập lĩnh vực hoạt động !',
            'phone.required'=>'Vui lòng nhập số điện thoại !',
            'contact.required'=>'Vui lòng nhập liên hệ !',
            'email.required'=>'Vui lòng nhập email !',
            'address.required'=>'Vui lòng nhập địa chỉ !',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('repair.create')->withErrors($validator)->withInput();
        else:
        $request['author_id'] = Auth::id();
        $atribute = $request->all();
        $atribute['fields_operation'] = json_encode($request->fields_operation);
        $atribute['type'] = 'repairs';
        $repair = Provider::create($atribute);
        $repair->equipment_cates()->attach($request->equipment_cates);
        return redirect()->route('repair.index')->with('success','Thêm thành công');
        endif;
    }
    public function editRepair($id){
        $user = Auth::user();
        $repairs = Provider::findOrFail($id);
        if($user->can('update', $repairs)) {
            $equipments = Cates::select('id','title')->get();
            $array = $repairs->equipment_cates->pluck('id')->toArray();
            return view('backends.repairs.edit',compact('repairs','equipments','array'));
        }else{
          abort(403);
        } 
    }
    public function updateRepair(Request  $request , $id){
        $rules = [
            'title'=>'required',
            'tax_code'=>'required',
            'fields_operation'=>'required',
            'note'=>'required',
            'contact'=>'required',
            'email'=>'required',
            'address'=>'required',
        ];
        $messages = [
            'title.required'=>'Vui lòng nhập tên thiết bị !',
            'tax_code.required'=>'Vui lòng nhập mã số thuế !',
            'fields_operation.required'=>'Vui lòng nhập lĩnh vực hoạt động !',
            'phone.required'=>'Vui lòng nhập số điện thoại !',
            'contact.required'=>'Vui lòng nhập liên hệ !',
            'email.required'=>'Vui lòng nhập email !',
            'address.required'=>'Vui lòng nhập địa chỉ !',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('repair.edit',$id)->withErrors($validator)->withInput();
        else:
        $repairs = Provider::findOrFail($id);
        $atribute = $request->all();
        $atribute['fields_operation'] = json_encode($request->fields_operation);
        $repairs->update($atribute);
        $repairs->equipment_cates()->sync($request->equipment_cates);
        if($repairs){
            if($repairs->wasChanged())
                return redirect()->route('repair.edit',$id)->with('success','Cập nhật thành công');
            else 
                return redirect()->route('repair.edit',$id); 
        }else{
            return redirect()->route('repair.edit',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function destroyRepair($id){
        $user = Auth::user();
        $repair = Provider::findOrFail($id);
        if($user->can('delete', $repair)) {
            $repair->delete();
            return redirect()->route('repair.index')->with('success','Xóa thành công');
        }else{
          abort(403);
        }
    }
}