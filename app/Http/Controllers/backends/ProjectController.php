<?php
namespace App\Http\Controllers\backends;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Device;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class ProjectController extends Controller {
    public function index(Request  $request){
        $user = Auth::user();
        $projects = Project::query();
        $keyword = isset($request->key) ? $request->key : '';
        if($user->can('project.read')){
            if($keyword != ''){
                $projects = $projects->where(function ($query) use ($keyword) {
                    $query->where('title','like','%'.$keyword.'%')
                          ->Orwhere('procurement','like','%'.$keyword.'%');
                });
            }
            $projects = $projects->orderBy('created_at', 'desc')->paginate(10);
            return view('backends.projects.list', compact('projects','keyword'));
        }else{
            if($keyword != ''){
                $projects = $projects = $projects->where(function ($query) use ($keyword) {
                        $query->where('title','like','%'.$keyword.'%')
                              ->Orwhere('procurement','like','%'.$keyword.'%');
                    });
            }
            $projects = $projects->where('author_id',$user->id)->orderBy('created_at', 'desc')->paginate(10);
            return view('backends.projects.list', compact('projects','keyword'));
        }
    }
    public function create(){
        $user= Auth::user();
        if($user->can('create', Project::class)){ 
            $devices = Device::all();
            return view('backends.projects.create',compact('devices'));
        }else{
            abort(403);
        }
    }
    public function store(Request  $request){
        $rules = [
            'title'=>'required',
            'procurement'=>'required',
            'decision'=>'required',
            'note'=>'required',
            'status'=>'required',
        ];
        $messages = [
            'title.required'=>'Please enter title',
            'procurement.required'=>'Please enter procurement',
            'decision.required'=>'Please enter decision',
            'note.required'=>'Please choose note',
            'status.required'=>'Please enter status',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('project.create')->withErrors($validator)->withInput();
        else:
        $request['author_id']= Auth::id();
        $atribute = $request->all();
        Project::create($atribute);
        return redirect()->route('project.index')->with('success','Thêm thành công');
        endif;
    }
    public function edit($id){
        $user = Auth::user();
        $projects = Project::findOrFail($id);
        if($user->can('update', $projects)){
            $devices = Device::all();
            return view('backends.projects.edit',compact('projects','devices'));
        }else{
          abort(403);
        }
    }
    public function update(Request  $request , $id){
        $rules = [
            'title'=>'required',
            'procurement'=>'required',
            'decision'=>'required',
            'note'=>'required',
            'status'=>'required',
        ];
        $messages = [
            'title.required'=>'Please enter title',
            'procurement.required'=>'Please enter procurement',
            'decision.required'=>'Please enter decision',
            'note.required'=>'Please choose note',
            'status.required'=>'Please enter status',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()):
            return redirect()->route('project.edit',$id)->withErrors($validator)->withInput();
        else:
        $projects = Project::findOrFail($id);
        $atribute = $request->all();
        $projects->update($atribute);
        if($projects){
            if($projects->wasChanged())
                return redirect()->route('project.edit',$id)->with('success','Cập nhật thành công');
            else 
                return redirect()->route('project.edit',$id);
        }else{
            return redirect()->route('project.edit',$id)->with('error','Cập nhật không thành công');
        }
    endif;
    }
    public function destroy($id){
        $user = Auth::user();
        $projects = Project::findOrFail($id);
        if ($user->can('delete', $projects)) {
            $projects->delete();
            return redirect()->route('project.index')->with('success','Xóa thành công');
        }else{
          abort(403);
        }
    }
}