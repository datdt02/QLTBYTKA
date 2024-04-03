<?php
namespace App\Http\Controllers\backends;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cates;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class CatesController extends Controller {
    public function index(Request  $request){
        $user = Auth::user();
        $equipment_cates = Cates::query();
        $keyword = isset($request->key) ? $request->key : '';
        if($user->can('equipment_cate.read')){
            if($keyword != ''){
                $equipment_cates = $equipment_cates->where('title','like','%'.$keyword.'%'); 
            }
        }else{
            $equipment_cates = $equipment_cates->where('author_id',$user->id);
            if($keyword != ''){
                $equipment_cates = $equipment_cates->where('title','like','%'.$keyword.'%');
            }
        }
        $equipment_cates = $equipment_cates->latest()->paginate(10);
        return view('backends.cates.list', compact('equipment_cates','keyword'));
    }
    public function create(){
        $user = Auth::user();
        if($user->can('create', Cates::class)) {
            return view('backends.cates.create');
        }else{
          abort(403);
        }
    }
    public function store(Request  $request)
    {
        $rules = [
            'title'=>'required',
            'code'=>'required',
        ];
        $messages = [
            'title.required'=>'Vui lòng nhập tên nhóm thiết bị',
            'code.required'=>'Vui lòng nhập mã nhóm thiết bị',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('equipment_cate.create')->withErrors($validator)->withInput();
        else:
        $request['author_id'] = Auth::id();
        $atribute = $request->all();
        Cates::create($atribute);
        return redirect()->route('equipment_cate.index')->with('success','Thêm thành công');
        endif;
    }
    public function edit($id){
        $user = Auth::user();
        $equipment_cates = Cates::findOrFail($id);
        if($user->can('update', $equipment_cates)) {
            return view('backends.cates.edit',compact('equipment_cates'));
        }else{
          abort(403);
        }
    }
    public function update(Request  $request , $id)
    {
        $rules = [
            'title'=>'required',
            'code'=>'required',
        ];
        $messages = [
            'title.required'=>'Vui lòng nhập tên nhóm thiết bị',
            'code.required'=>'Vui lòng nhập mã nhóm thiết bị',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('equipment_cate.edit',$id)->withErrors($validator)->withInput();
        else:
        $equipment_cates = Cates::findOrFail($id);
        $atribute = $request->all();
        $equipment_cates->update($atribute);
        if($equipment_cates){
            if($equipment_cates->wasChanged())
                return redirect()->route('equipment_cate.edit',$id)->with('success','Cập nhật thành công');
            else 
                return redirect()->route('equipment_cate.edit',$id);
        }else{
            return redirect()->route('equipment_cate.edit',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function destroy($id){
        $user = Auth::user();
        $equipment_cates = Cates::findOrFail($id);
        if ($user->can('delete', $equipment_cates)) {
            $equipment_cates->delete();
            return redirect()->route('equipment_cate.index')->with('success','Xóa thành công');
        }else{
          abort(403);
        }
    }
}