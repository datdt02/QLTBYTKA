<?php
namespace App\Http\Controllers\backends;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class SupplieController extends Controller {
    public function index(Request  $request){
        $user = Auth::user();
        $keyword = isset($request->key) ? $request->key : '';
        $supplies = Supplie::query();
        if($user->can('supplie.read')){
            if($keyword != ''){
                $supplies = $supplies->where('title','like','%'.$keyword.'%');
            }
            $supplies = $supplies->orderBy('created_at', 'desc')->paginate(10);
            return view('backends.supplies.list', compact('supplies','keyword'));
        }else{
            if($keyword != ''){
                $supplies = $supplies->where('title','like','%'.$keyword.'%');
            }
            $supplies = $supplies->where('author_id',$user->id)->orderBy('created_at', 'desc')->paginate(10);
            return view('backends.supplies.list', compact('supplies','keyword'));
        }
    }
    public function create(){
        $user= Auth::user();
        if($user->can('create', Supplie::class)){ 
            return view('backends.supplies.create');
        }else{
            abort(403);
        }
    }
    public function store(Request  $request){
        $rules = [
            'title'=>'required',
            'code'=>'required',
        ];
        $messages = [
            'title.required'=>'Please enter title',
            'code.required'=>'Please enter code',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('supplie.create')->withErrors($validator)->withInput();
        else:
        $request['author_id']= Auth::id();
        $atribute = $request->all();
        Supplie::create($atribute);
        return redirect()->route('supplie.index')->with('success','Thêm thành công');
        endif;
    }
    public function edit($id){
        $user = Auth::user();
        $supplies = Supplie::findOrFail($id);
        if($user->can('update', $supplies)) {
            return view('backends.supplies.edit',compact('supplies'));
        }else{
          abort(403);
        }
    }
    public function update(Request  $request , $id){
        $rules = [
            'title'=>'required',
            'code'=>'required',
        ];
        $messages = [
            'title.required'=>'Please enter title',
            'code.required'=>'Please enter code',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('supplie.edit',$id)->withErrors($validator)->withInput();
        else:
        $supplies = Supplie::findOrFail($id);
        $atribute = $request->all();
        $supplies->update($atribute);
        if($supplies){
            if($supplies->wasChanged())
                return redirect()->route('supplie.edit',$id)->with('success','Cập nhật thành công');
            else 
                return redirect()->route('supplie.edit',$id);
        }else{
            return redirect()->route('supplie.edit',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function destroy($id){
        $user = Auth::user();
        $supplies = Supplie::findOrFail($id);
        if ($user->can('delete', $supplies)) {
            $supplies->delete();
            return redirect()->route('supplie.index')->with('success','Xóa thành công');
        }else{
          abort(403);
        }
    }
}