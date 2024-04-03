<?php
namespace App\Http\Controllers\backends;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Device;
use App\Models\Supplie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class UnitController extends Controller {
    public function index(Request  $request){
        $user = Auth::user();
        $units = Unit::query();
        $keyword = isset($request->key) ? $request->key : '';
        if($user->can('unit.read')){
            if($keyword != ''){
                $units = $units->where('title','like','%'.$keyword.'%');
            }
        }else{
            if($keyword != ''){
                $units = $units->where('title','like','%'.$keyword.'%');
            }
            $units = $units->where('author_id',$user->id);
        }
        $units = $units->orderBy('created_at', 'desc')->paginate(10);
        return view('backends.units.list', compact('units','keyword'));

    }
    public function create(){
        $user= Auth::user();
        if($user->can('create', Unit::class)){ 
            $devices = Device::all();
            $supplies = Supplie::all();
            return view('backends.units.create',compact('devices','supplies'));
        }else{
            abort(403);
        }
        
    }
    public function store(Request  $request){
        $rules = [
            'title'=>'required',
        ];
        $messages = [
            'title.required'=>'Please enter title',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('unit.create')->withErrors($validator)->withInput();
        else:
        $request['author_id']= Auth::id();
        $atribute = $request->all();
        Unit::create($atribute);
        return redirect()->route('unit.index')->with('success','Thêm thành công');
        endif;
    }
    public function edit($id){
        $user = Auth::user();
        $units = Unit::findOrFail($id);
        if($user->can('update', $units)) {
            $devices = Device::all();
            $supplies = Supplie::all();
            return view('backends.units.edit',compact('units','devices','supplies'));
        }else{
          abort(403);
        }
        
    }
    public function update(Request  $request , $id){
        $rules = [
            'title'=>'required',
        ];
        $messages = [
            'title.required'=>'Please enter title',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('supplie.edit',$id)->withErrors($validator)->withInput();
        else:
        $units = Unit::findOrFail($id);
        $atribute = $request->all();
        $units->update($atribute);
        if($units){
            if($units->wasChanged())
                return redirect()->route('unit.edit',$id)->with('success','Cập nhật thành công');
            else 
                return redirect()->route('unit.edit',$id);
        }else{
            return redirect()->route('unit.edit',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function destroy($id){
        $user = Auth::user();
        $units = Unit::findOrFail($id);
        if ($user->can('delete', $units)) {
            $units->delete();
            return redirect()->route('supplie.index')->with('success','Xóa thành công');
        }else{
          abort(403);
        }
        
    }
}